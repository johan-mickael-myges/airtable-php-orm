<?php

namespace Airtable\ORM\Entity\Attribute;

use Airtable\ORM\Entity\Attribute\Exception\AirtablePrimaryKeyNotFoundException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

interface EntityPrimaryKeyAttributeFinderInterface
{
	/**
	 * @throws ReflectionException
	 * @throws AirtablePrimaryKeyNotFoundException
	 */
	public function get(string $entityClass): ReflectionProperty;
}