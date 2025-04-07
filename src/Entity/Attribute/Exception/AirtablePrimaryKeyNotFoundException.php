<?php

namespace Airtable\ORM\Entity\Attribute\Exception;

use LogicException;

class AirtablePrimaryKeyNotFoundException extends LogicException
{
	public function __construct(string $entityClass)
	{
		parent::__construct("Primary key not found in entity class: $entityClass");
	}
}