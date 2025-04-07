<?php

namespace Airtable\ORM\Entity\Attribute;

use Airtable\ORM\Annotation\AirtablePrimaryKey;
use Airtable\ORM\Entity\Attribute\Exception\AirtablePrimaryKeyNotFoundException;
use ReflectionClass;
use ReflectionProperty;

class EntityPrimaryKeyAttributeFinder implements EntityPrimaryKeyAttributeFinderInterface
{
	/**
	 * @inheritDoc
	 */
	public function get(string $entityClass): ReflectionProperty {
		$entityReflection = new ReflectionClass($entityClass);
		$entityProperties = $entityReflection->getProperties();

		foreach ($entityProperties as $entityProperty) {
			$entityPropertyAttributes = $entityProperty->getAttributes(AirtablePrimaryKey::class);

			if (!empty($entityPropertyAttributes)) {
				return $entityProperty;
			}
		}

		throw new AirtablePrimaryKeyNotFoundException($entityClass);
	}
}