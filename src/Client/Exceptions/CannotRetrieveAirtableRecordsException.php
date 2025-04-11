<?php

namespace Airtable\ORM\Client\Exceptions;

use Exception;

class CannotRetrieveAirtableRecordsException extends Exception
{
	public function __construct(
		string $message = 'Failed to retrieve records from Airtable',
		int $code = 0,
		Exception $previous = null,
	) {
		parent::__construct($message, $code, $previous);
	}
}