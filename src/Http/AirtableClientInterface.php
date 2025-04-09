<?php

namespace Airtable\ORM\Client;

interface AirtableClientInterface
{
	/**
	 * Retrieves multiple records from an Airtable table.
	 *
	 * @param string $tableIdOrName The table ID or name (e.g., 'tblqwR8y1wsEVyKjU' or 'Projects')
	 * @param array $queryParameters Optional query parameters (e.g., 'view', 'filterByFormula')
	 * @return array An array of record arrays
	 */
	public function getRecords(string $tableIdOrName, array $queryParameters = []): array;

	/**
	 * Retrieves a single record by its ID.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param string $recordId The record ID (e.g., 'rec123')
	 * @return array The record data
	 */
	public function getRecord(string $tableIdOrName, string $recordId): array;

	/**
	 * Creates a single record in an Airtable table.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param array $fields The fields to create (e.g., ['fldName' => 'Test'])
	 * @return array The created record data
	 */
	public function createRecord(string $tableIdOrName, array $fields): array;

	/**
	 * Creates multiple records in an Airtable table in a single request.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param array $records An array of field arrays (e.g., [['fldName' => 'Test1'], ['fldName' => 'Test2']])
	 * @return array An array of created record data
	 */
	public function createRecords(string $tableIdOrName, array $records): array;

	/**
	 * Updates a single record in an Airtable table.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param string $recordId The record ID to update
	 * @param array $fields The fields to update (e.g., ['fldName' => 'Updated Name'])
	 * @param bool $merge Whether to merge fields (PUT) or replace all (PATCH, default)
	 * @return array The updated record data
	 */
	public function updateRecord(string $tableIdOrName, string $recordId, array $fields, bool $merge = true): array;

	/**
	 * Updates multiple records in an Airtable table in a single request.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param array $records An array of record updates (e.g., [['id' => 'rec123', 'fields' => ['fldName' => 'New']]])
	 * @param bool $merge Whether to merge fields (PUT) or replace all (PATCH, default)
	 * @return array An array of updated record data
	 */
	public function updateRecords(string $tableIdOrName, array $records, bool $merge = true): array;

	/**
	 * Deletes a single record from an Airtable table.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param string $recordId The record ID to delete
	 * @return bool True if deleted successfully
	 */
	public function deleteRecord(string $tableIdOrName, string $recordId): bool;

	/**
	 * Deletes multiple records from an Airtable table in a single request.
	 *
	 * @param string $tableIdOrName The table ID or name
	 * @param array $recordIds An array of record IDs to delete
	 * @return array An array of deletion results (e.g., ['deleted' => true])
	 */
	public function deleteRecords(string $tableIdOrName, array $recordIds): array;
}