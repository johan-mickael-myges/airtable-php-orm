<?php

namespace App\Airtable\Exceptions;

use Exception;

class InvalidAirtableResponseException extends Exception
{
	public function __construct(
		string $message = 'Invalid response from Airtable',
		int $code = 0,
		Exception $previous = null,
	) {
		parent::__construct($message, $code, $previous);
	}
}