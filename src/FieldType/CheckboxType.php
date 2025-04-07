<?php

namespace Airtable\ORM\FieldType;

class CheckboxType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?bool
	{
		return $value !== null ? (string) $value : null;
	}

	public function convertToAirtableValue($value): ?bool
	{
		return $value !== null ? (string) $value : null;
	}
}