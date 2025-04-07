<?php

namespace Airtable\ORM\FieldType;

interface AirtableFieldType
{
	/**
	 * Converts an Airtable API value to its PHP representation.
	 */
	public function convertToPhpValue(mixed $value): mixed;

	/**
	 * Converts a PHP value to its Airtable API representation.
	 */
	public function convertToAirtableValue(mixed $value): mixed;
}