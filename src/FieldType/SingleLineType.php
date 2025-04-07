<?php

namespace Airtable\ORM\FieldType;

class SingleLineType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?string
	{
		return $value !== null ? (string) $value : null;
	}

	public function convertToAirtableValue($value): ?string
	{
		return $value !== null ? (string) $value : null;
	}
}