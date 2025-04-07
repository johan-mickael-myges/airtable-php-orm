<?php

namespace Airtable\ORM\Entity;

interface AirtableToPhpEntityFactoryInterface
{
	public function create(string $entityClass, array $fields): object;
}