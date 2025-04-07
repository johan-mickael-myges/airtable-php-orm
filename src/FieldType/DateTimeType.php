<?php

namespace Airtable\ORM\FieldType;

use DateTime;
use DateTimeInterface;

class DateTimeType implements AirtableFieldType
{
	public function convertToPhpValue($value): ?DateTimeInterface
	{
		return $value ? new DateTime($value) : null;
	}

	public function convertToAirtableValue($value): ?string
	{
		return $value instanceof DateTime ? $value->format('c') : null;
	}
}