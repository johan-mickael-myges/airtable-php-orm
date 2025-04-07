<?php

namespace Airtable\ORM\Entity;

use Airtable\ORM\Annotation\AirtableField;
use Airtable\ORM\Annotation\AirtablePrimaryKey;
use ReflectionClass;
use ReflectionException;

class AirtableToPhpEntityFactory implements AirtableToPhpEntityFactoryInterface
{
	/**
	 * @throws ReflectionException
	 */
	public function create(string $entityClass, array $fields): object
	{
		$entityReflection = new ReflectionClass($entityClass);
		$entityInstance = $entityReflection->newInstanceWithoutConstructor();
		$entityProperties = $entityReflection->getProperties();

		// Map primary key first using reflection
		foreach ($entityProperties as $property) {
			$primaryKeyAttributes = $property->getAttributes(AirtablePrimaryKey::class);
			if (!empty($primaryKeyAttributes)) {
				if (isset($record['id'])) {
					$property->setAccessible(true);
					$property->setValue($entityInstance, $record['id']);
					$property->setAccessible(false);
				}
				break; // Only one primary key
			}
		}

		// Map remaining fields
		foreach ($entityProperties as $property) {
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

			$this->setPropertyValue($entityInstance, $property, $propertyName, $fieldValue);
		}

		return $entityInstance;
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