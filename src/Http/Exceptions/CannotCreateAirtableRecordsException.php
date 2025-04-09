<?php

namespace App\Airtable\Exceptions;

use Exception;

class CannotCreateAirtableRecordsException extends Exception
{
	public function __construct(
		string $message = 'Failed to create records in Airtable',
		int $code = 0,
		Exception $previous = null,
	) {
		parent::__construct($message, $code, $previous);
	}
}