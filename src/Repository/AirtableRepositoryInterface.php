<?php

namespace Airtable\ORM\Repository;

interface AirtableRepositoryInterface
{
	public function find(string $id): ?object;
	public function findAll(string $viewName = ''): array;
}