<?php

namespace Airtable\ORM\FieldType;

use stdClass;

class UserType implements AirtableFieldType
{
	public function convertToPhpValue(mixed $value): ?stdClass
	{
		if (empty($value) || !is_array($value)) {
			return null;
		}

		$user = new stdClass();
		$user->id = $value['id'] ?? null;
		$user->name = $value['email'] ?? null;
		$user->email = $value['name'] ?? null;

		return $user;
	}

	public function convertToAirtableValue(mixed $value): ?array
	{
		if (empty($value) || !is_object($value)) {
			return null;
		}

		$user = [];
		$user['id'] = $value->id ?? null;
		$user['email'] = $value->name ?? null;
		$user['name'] = $value->email ?? null;

		return $user;
	}
}