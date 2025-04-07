<?php

namespace Airtable\ORM\Annotation;

use Airtable\ORM\FieldType\SingleLineType;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AirtableField
{
	public function __construct(
		public string $fieldIdOrName,
		public ?string $fieldType = SingleLineType::class,
		public bool $multiple = false,
		public mixed $defaultValue = null,
	) {
	}
}