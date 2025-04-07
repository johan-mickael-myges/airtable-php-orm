<?php

namespace Airtable\ORM\FieldType;

class MultipleSelectType implements AirtableFieldType
{
	public function convertToPhpValue($value): array
	{
		if (empty($value)) {
			return [];
		}
		return (array) $value;
	}

	public function convertToAirtableValue($value): ?array
	{
		return $value !== null ? (array) $value : null;
	}
}