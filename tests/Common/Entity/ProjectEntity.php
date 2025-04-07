<?php

namespace Airtable\ORM\Tests\Common\Entity;

use Airtable\ORM\Annotation\AirtableField;
use Airtable\ORM\Annotation\AirtablePrimaryKey;
use Airtable\ORM\Annotation\AirtableTable;
use Airtable\ORM\FieldType\DateTimeType;
use Airtable\ORM\FieldType\IntegerType;
use Airtable\ORM\FieldType\SingleLineType;
use DateTime;

#[AirtableTable(tableIdOrName: 'tblTest')]
class ProjectEntity
{
	#[AirtablePrimaryKey]
	private ?string $id = null;

	#[AirtableField(fieldIdOrName: 'Name', fieldType: SingleLineType::class, defaultValue: null)]
	private ?string $name = null;

	#[AirtableField(fieldIdOrName: 'Description', fieldType: SingleLineType::class, defaultValue: null)]
	private ?string $description = null;

	#[AirtableField(fieldIdOrName: 'Created at', fieldType: DateTimeType::class, defaultValue: null)]
	private ?DateTime $createdAt = null;

	#[AirtableField(fieldIdOrName: 'Last modified at', fieldType: DateTimeType::class, defaultValue: null)]
	private ?DateTime $lastModifiedAt = null;

	#[AirtableField(fieldIdOrName: 'Id', fieldType: IntegerType::class, defaultValue: 1)]
	private ?int $projectId = null;

	#[AirtableField(fieldIdOrName: 'Students', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $students = null;

	#[AirtableField(fieldIdOrName: 'Likes', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $likes = null;

	#[AirtableField(fieldIdOrName: 'Link', fieldType: SingleLineType::class, defaultValue: null)]
	private ?string $link = null;

	#[AirtableField(fieldIdOrName: 'Promotion', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $promotion = null;

	#[AirtableField(fieldIdOrName: 'Categories', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $categories = null;

	#[AirtableField(fieldIdOrName: 'Categories [Name]', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $categoryNames = null;

	#[AirtableField(fieldIdOrName: 'Promotion [Name]', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $promotionNames = null;

	#[AirtableField(fieldIdOrName: 'Students [Full Name]', fieldType: SingleLineType::class, multiple: true, defaultValue: null)]
	private ?array $studentFullNames = null;

	#[AirtableField(fieldIdOrName: 'Likes->Count', fieldType: IntegerType::class, defaultValue: null)]
	private ?int $likesCount = null;

	#[AirtableField(fieldIdOrName: 'TEST', fieldType: SingleLineType::class, defaultValue: null)]
	private ?string $test = null;

	public function getId(): ?string
	{
		return $this->id;
	}

	public function setId(?string $id): void
	{
		$this->id = $id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(?string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getCreatedAt(): ?DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(?DateTime $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getLastModifiedAt(): ?DateTime
	{
		return $this->lastModifiedAt;
	}

	public function setLastModifiedAt(?DateTime $lastModifiedAt): void
	{
		$this->lastModifiedAt = $lastModifiedAt;
	}

	public function getProjectId(): ?int
	{
		return $this->projectId;
	}

	public function setProjectId(?int $projectId): void
	{
		$this->projectId = $projectId;
	}

	public function getStudents(): ?array
	{
		return $this->students;
	}

	public function setStudents(?array $students): void
	{
		$this->students = $students;
	}

	public function getLikes(): ?array
	{
		return $this->likes;
	}

	public function setLikes(?array $likes): void
	{
		$this->likes = $likes;
	}

	public function getLink(): ?string
	{
		return $this->link;
	}

	public function setLink(?string $link): void
	{
		$this->link = $link;
	}

	public function getPromotion(): ?array
	{
		return $this->promotion;
	}

	public function setPromotion(?array $promotion): void
	{
		$this->promotion = $promotion;
	}

	public function getCategories(): ?array
	{
		return $this->categories;
	}

	public function setCategories(?array $categories): void
	{
		$this->categories = $categories;
	}

	public function getCategoryNames(): ?array
	{
		return $this->categoryNames;
	}

	public function setCategoryNames(?array $categoryNames): void
	{
		$this->categoryNames = $categoryNames;
	}

	public function getPromotionNames(): ?array
	{
		return $this->promotionNames;
	}

	public function setPromotionNames(?array $promotionNames): void
	{
		$this->promotionNames = $promotionNames;
	}

	public function getStudentFullNames(): ?array
	{
		return $this->studentFullNames;
	}

	public function setStudentFullNames(?array $studentFullNames): void
	{
		$this->studentFullNames = $studentFullNames;
	}

	public function getLikesCount(): ?int
	{
		return $this->likesCount;
	}

	public function setLikesCount(?int $likesCount): void
	{
		$this->likesCount = $likesCount;
	}

	public function getTest(): ?string
	{
		return $this->test;
	}

	public function setTest(?string $test): void
	{
		$this->test = $test;
	}
}