<?php

namespace Airtable\ORM\Annotation;

use Airtable\ORM\Repository\GenericAirtableRepository;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AirtableRepository
{
	public function __construct(
		public string $class = GenericAirtableRepository::class,
	) {
	}
}