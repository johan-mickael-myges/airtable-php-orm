<?php

namespace Airtable\ORM\FieldType;

use DateTime;

class IntegerType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?int
	{
		return $value !== null ? (int) $value : null;
	}

	public function convertToAirtableValue($value): ?int
	{
		return $value !== null ? (int) $value : null;
	}
}