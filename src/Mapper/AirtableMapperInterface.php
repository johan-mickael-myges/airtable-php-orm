<?php

namespace Airtable\ORM\Mapper;

use ReflectionException;
use RuntimeException;

interface AirtableMapperInterface
{

	public function mapToEntity(array $record, string $entityClass): object;
	public function mapToFields(object $entity): array;
}