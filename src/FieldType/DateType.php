<?php

namespace Airtable\ORM\FieldType;

use DateTime;
use DateTimeInterface;

class DateType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?DateTimeInterface
	{
		return $value ? new DateTime($value) : null;
	}

	public function convertToAirtableValue($value): ?string
	{
		if ($value instanceof DateTimeInterface) {
			return $value->format('Y-m-d');
		}

		if (is_string($value)) {
			return (new DateTime($value))->format('Y-m-d');
		}

		return null;
	}
}