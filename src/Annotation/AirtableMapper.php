<?php

namespace Airtable\ORM\Annotation;

use Airtable\ORM\Mapper\GenericAirtableMapper;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AirtableMapper
{
	public function __construct(
		public string $class = GenericAirtableMapper::class,
	) {
	}
}