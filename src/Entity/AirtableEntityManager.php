<?php

namespace Airtable\ORM\Entity;

use Airtable\ORM\Annotation\AirtableTable;
use Airtable\ORM\Client\AirtableClientInterface;
use Airtable\ORM\Mapper\GenericAirtableMapper;
use Airtable\ORM\Repository\AirtableRepositoryInterface;
use Airtable\ORM\Repository\GenericAirtableRepository;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class AirtableEntityManager
{
	public function __construct(
		private AirtableClientInterface $client
	) {
	}

	/**
	 * @throws ReflectionException
	 */
	public function getRepository(string $entityClass): AirtableRepositoryInterface
	{
		$reflection = new ReflectionClass($entityClass);

		$tableAttributes = $reflection->getAttributes(AirtableTable::class);
		if (empty($tableAttributes)) {
			throw new RuntimeException("Entity '$entityClass' must have an AirtableTable annotation.");
		}
		$tableIdOrName = $tableAttributes[0]->newInstance()->tableIdOrName;

		$mapper = new GenericAirtableMapper();
		return new GenericAirtableRepository($this->client, $mapper, $entityClass, $tableIdOrName);
	}
}