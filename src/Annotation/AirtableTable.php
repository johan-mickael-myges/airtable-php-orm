<?php

namespace Airtable\ORM\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AirtableTable
{
	public function __construct(
		public string $tableIdOrName,
		public array $views = []
	) {}
}