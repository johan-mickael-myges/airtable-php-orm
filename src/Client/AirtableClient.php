<?php

namespace Airtable\ORM\Client;

use Airtable\ORM\Client\Exceptions\AirtableRecordNotFoundException;
use Airtable\ORM\Client\Exceptions\CannotCreateAirtableRecordsException;
use Airtable\ORM\Client\Exceptions\CannotRetrieveAirtableRecordsException;
use Airtable\ORM\Client\Exceptions\InvalidAirtableResponseException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

readonly class AirtableClient implements AirtableClientInterface
{
	public function __construct(
		private HttpClientInterface $httpClient,
		private string $baseUrl,
		private string $apiKey,
		private string $baseId
	) {}

	/**
	 * @inheritDoc
	 */
	public function getRecords(string $tableIdOrName, array $queryParameters = []): array
	{
		try {
			$response = $this->httpClient->request(
				'GET',
				sprintf('%s%s/%s', $this->baseUrl, $this->baseId, urlencode($tableIdOrName)),
				[
					'headers' => $this->getHeaders(),
					'query' => $queryParameters,
				]
			);

			if ($response->getStatusCode() !== 200) {
				throw new CannotRetrieveAirtableRecordsException(
					$response->getContent(false),
					$response->getStatusCode(),
				);
			}

			$responseData = $response->toArray();
			if (!array_key_exists('records', $responseData)) {
				throw new InvalidAirtableResponseException();
			}

			return $responseData['records'];
		} catch (Throwable $exception) {
			throw new CannotRetrieveAirtableRecordsException(
				$exception->getMessage(),
				$exception->getCode(),
				$exception,
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function createRecord(string $tableIdOrName, array $fields): array
	{
		try {
			$response = $this->httpClient->request(
				'POST',
				sprintf('%s%s/%s', $this->baseUrl, $this->baseId, urlencode($tableIdOrName)),
				[
					'headers' => $this->getHeaders(),
					'json' => ['fields' => $fields],
				]
			);

			if ($response->getStatusCode() !== 200) {
				throw new CannotCreateAirtableRecordsException(
					$response->getContent(false),
					$response->getStatusCode(),
				);
			}

			$responseData = $response->toArray();
			if (empty($responseData)) {
				throw new InvalidAirtableResponseException();
			}

			return $responseData;
		} catch (Throwable $exception) {
			throw new CannotCreateAirtableRecordsException(
				$exception->getMessage(),
				$exception->getCode(),
				$exception,
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRecordById(string $tableIdOrName, string $recordId): array
	{
		try {
			$records = $this->getRecords($tableIdOrName, [
				'filterByFormula' => "RECORD_ID()='$recordId'",
			]);
			if (empty($records)) {
				throw new AirtableRecordNotFoundException();
			}
			return $records[0];
		} catch (Throwable $e) {
			throw new CannotRetrieveAirtableRecordsException(
				$e->getMessage(),
				$e->getCode(),
				$e,
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRecordsByIds(string $tableIdOrName, array $recordIds): array
	{
		if (empty($recordIds)) {
			return [];
		}

		$formula = "OR(" . implode(',', array_map(static fn($id) => "RECORD_ID()='$id'", $recordIds)) . ")";
		return $this->getRecords($tableIdOrName, [
			'filterByFormula' => $formula,
		]);
	}

	private function getHeaders(): array
	{
		return [
			'Authorization' => "Bearer {$this->apiKey}",
			'Content-Type' => 'application/json',
		];
	}

	public function getRecord(string $tableIdOrName, string $recordId): array
	{
		// TODO: Implement getRecord() method.
	}

	public function createRecords(string $tableIdOrName, array $records): array
	{
		// TODO: Implement createRecords() method.
	}

	public function updateRecord(string $tableIdOrName, string $recordId, array $fields, bool $merge = true): array
	{
		// TODO: Implement updateRecord() method.
	}

	public function updateRecords(string $tableIdOrName, array $records, bool $merge = true): array
	{
		// TODO: Implement updateRecords() method.
	}

	public function deleteRecord(string $tableIdOrName, string $recordId): bool
	{
		// TODO: Implement deleteRecord() method.
	}

	public function deleteRecords(string $tableIdOrName, array $recordIds): array
	{
		// TODO: Implement deleteRecords() method.
	}
}
