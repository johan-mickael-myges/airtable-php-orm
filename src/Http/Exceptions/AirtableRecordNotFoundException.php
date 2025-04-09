<?php

namespace App\Airtable\Exceptions;

use Exception;

class AirtableRecordNotFoundException extends Exception
{
	public function __construct(
		string $message = 'Airtable record not found',
		int $code = 0,
		Exception $previous = null,
	) {
		parent::__construct($message, $code, $previous);
	}
}
