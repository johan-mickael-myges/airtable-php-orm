<?php

namespace Airtable\ORM\Mapper;

use Airtable\ORM\Annotation\AirtableField;
use Airtable\ORM\Annotation\AirtablePrimaryKey;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use stdClass;

class GenericAirtableMapper implements AirtableMapperInterface
{
	/**
	 * @inheritDoc
	 */
	public function mapToEntity(array $record, string $entityClass): object
	{
		$fields = $record['fields'] ?? [];
		$reflection = new ReflectionClass($entityClass);
		$entity = $reflection->newInstanceWithoutConstructor();
		$properties = $reflection->getProperties();

		// Map primary key first using reflection
		foreach ($properties as $property) {
			$primaryKeyAttributes = $property->getAttributes(AirtablePrimaryKey::class);
			if (!empty($primaryKeyAttributes)) {
				if (isset($record['id'])) {
					$property->setAccessible(true);
					$property->setValue($entity, $record['id']);
					$property->setAccessible(false);
				}
				break; // Only one primary key
			}
		}

		// Map remaining fields
		foreach ($properties as $property) {
			$attributes = $property->getAttributes(AirtableField::class);
			if (empty($attributes)) {
				continue;
			}

			$attribute = $attributes[0]->newInstance();
			$fieldNameOrId = $attribute->fieldIdOrName;
			$fieldTypeClass = $attribute->fieldType;
			$isMultipleField = $attribute->multiple;
			$propertyName = $property->getName();
			$fieldValue = $fields[$fieldNameOrId] ?? null; // No defaultValue here

			if ($fieldValue !== null) {
				$fieldType = new $fieldTypeClass();
				if ($isMultipleField) {
					$fieldValue = array_map([$fieldType, 'convertToPhpValue'], (array) $fieldValue);
				} else {
					$fieldValue = $fieldType->convertToPhpValue($fieldValue);
					if (is_array($fieldValue) && count($fieldValue) === 1) {
						$fieldValue = $fieldValue[0];
					}
				}
			}

			$this->setPropertyValue($entity, $property, $propertyName, $fieldValue);
		}

		return $entity;
	}

	/**
	 * Maps a PHP entity to an array of fields for Airtable.
	 */
	public function mapToFields(object $entity): array
	{
		$reflection = new ReflectionClass($entity);
		$properties = $reflection->getProperties();
		$fields = [];

		foreach ($properties as $property) {
			$attributes = $property->getAttributes(AirtableField::class);
			if (empty($attributes)) {
				continue;
			}

			$attribute = $attributes[0]->newInstance();
			$fieldNameOrId = $attribute->fieldIdOrName;
			$fieldTypeClass = $attribute->fieldType;
			$isArrayField = $attribute->multiple;
			$defaultValue = $attribute->defaultValue;
			$propertyName = $property->getName();

			$fieldValue = $this->getPropertyValue($entity, $property, $propertyName);
			if ($fieldValue === null && !$property->isInitialized($entity)) {
				$fieldValue = $defaultValue; // Apply defaultValue here
			}

			if ($fieldValue !== null) {
				$fieldType = new $fieldTypeClass();
				if ($isArrayField && is_array($fieldValue)) {
					$fields[$fieldNameOrId] = array_map([$fieldType, 'convertToAirtableValue'], $fieldValue);
				} else {
					$fields[$fieldNameOrId] = $fieldType->convertToAirtableValue($fieldValue);
				}
			} else {
				$fields[$fieldNameOrId] = null;
			}
		}

		return $fields;
	}

	/**
	 * Maps a PHP entity to an Airtable-compatible object (id + fields).
	 */
	public function mapToAirtableObject(object $entity): stdClass
	{
		$reflection = new ReflectionClass($entity);
		$properties = $reflection->getProperties();
		$result = new stdClass();
		$fields = [];

		// Extract primary key
		foreach ($properties as $property) {
			$primaryKeyAttributes = $property->getAttributes(AirtablePrimaryKey::class);
			if (!empty($primaryKeyAttributes)) {
				$result->id = $this->getPropertyValue($entity, $property, $property->getName());
				break; // Only one primary key
			}
		}

		// Map fields
		foreach ($properties as $property) {
			$attributes = $property->getAttributes(AirtableField::class);
			if (empty($attributes)) {
				continue;
			}

			$attribute = $attributes[0]->newInstance();
			$fieldNameOrId = $attribute->fieldIdOrName;
			$fieldTypeClass = $attribute->fieldType;
			$isArrayField = $attribute->multiple;
			$defaultValue = $attribute->defaultValue;
			$propertyName = $property->getName();

			$fieldValue = $this->getPropertyValue($entity, $property, $propertyName);
			if ($fieldValue === null && !$property->isInitialized($entity)) {
				$fieldValue = $defaultValue;
				$fields[$fieldNameOrId] = $fieldValue;
			}

			if ($fieldValue !== null) {
				$fieldType = new $fieldTypeClass();
				if ($isArrayField && is_array($fieldValue)) {
					$fields[$fieldNameOrId] = array_map([$fieldType, 'convertToAirtableValue'], $fieldValue);
				} else {
					$fields[$fieldNameOrId] = $fieldType->convertToAirtableValue($fieldValue);
				}
			}
		}

		$result->fields = $fields;
		return $result;
	}

	private function setPropertyValue(object $entity, \ReflectionProperty $property, string $propertyName, $value): void
	{
		if ($property->isPublic()) {
			$entity->$propertyName = $value;
		} else {
			$setterMethod = 'set' . ucfirst($propertyName);
			if (method_exists($entity, $setterMethod)) {
				$entity->$setterMethod($value);
			} else {
				// For readonly properties without setters
				$property->setAccessible(true);
				$property->setValue($entity, $value);
				$property->setAccessible(false);
			}
		}
	}

	private function getPropertyValue(object $entity, \ReflectionProperty $property, string $propertyName)
	{
		if ($property->isPublic() && isset($entity->$propertyName)) {
			return $entity->$propertyName;
		}

		$getterMethod = 'get' . ucfirst($propertyName);
		if (method_exists($entity, $getterMethod)) {
			return $entity->$getterMethod();
		}

		// For properties without getters (e.g., readonly)
		$property->setAccessible(true);
		$value = $property->isInitialized($entity) ? $property->getValue($entity) : null;
		$property->setAccessible(false);
		return $value;
	}
}