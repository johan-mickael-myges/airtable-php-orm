<?php

namespace Airtable\ORM\FieldType;

class NumberType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?float
	{
		return $value !== null ? (float) $value : null;
	}

	public function convertToAirtableValue($value): ?float
	{
		return $value !== null ? (float) $value : null;
	}
}