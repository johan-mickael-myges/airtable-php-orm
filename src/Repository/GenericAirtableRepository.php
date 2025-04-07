<?php

namespace Airtable\ORM\Repository;

use Airtable\ORM\Client\AirtableClientInterface;
use Airtable\ORM\Mapper\AirtableMapperInterface;
use ReflectionClass;
use RuntimeException;

class GenericAirtableRepository implements AirtableRepositoryInterface
{
	public function __construct(
		private AirtableClientInterface $client,
		private AirtableMapperInterface $mapper,
		private string $entityClass,
		private string $tableIdOrName
	) {
	}

	public function find(string $id): ?object
	{
		$records = $this->client->getRecords($this->tableIdOrName, [
			'filterByFormula' => "RECORD_ID()='$id'"
		]);
		return !empty($records) ? $this->mapper->mapToEntity($records[0], $this->entityClass) : null;
	}

	public function findAll(string $viewName = ''): array
	{
		$queryParams = $viewName ? ['view' => $viewName] : [];
		$records = $this->client->getRecords($this->tableIdOrName, $queryParams);
		return array_map(fn($record) => $this->mapper->mapToEntity($record, $this->entityClass), $records);
	}

	public function save(object $entity): void
	{
		$fields = $this->mapper->mapToFields($entity);
		$reflection = new ReflectionClass($this->entityClass);

		if ($reflection->hasMethod('getId') && $entity->getId()) {
			$this->client->updateRecord($this->tableIdOrName, $entity->getId(), $fields);
		} else {
			$response = $this->client->createRecord($this->tableIdOrName, $fields);
			if ($reflection->hasMethod('setId')) {
				$entity->setId($response['id']);
			}
		}
	}

	public function update(object $entity): void
	{
		$reflection = new ReflectionClass($this->entityClass);
		if (!$reflection->hasMethod('getId') || !$entity->getId()) {
			throw new RuntimeException('Entity must have an ID to update.');
		}

		$fields = $this->mapper->mapToFields($entity);
		$this->client->updateRecord($this->tableIdOrName, $entity->getId(), $fields);
	}

	public function delete(string $id): void
	{
		$this->client->deleteRecord($this->tableIdOrName, $id);
	}
}