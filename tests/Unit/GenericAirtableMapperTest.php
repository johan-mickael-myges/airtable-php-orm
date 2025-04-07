<?php

namespace Airtable\ORM\Tests\Unit;

use Airtable\ORM\Mapper\GenericAirtableMapper;
use Airtable\ORM\Tests\Common\Entity\ProjectEntity;
use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;

class GenericAirtableMapperTest extends TestCase
{
	private GenericAirtableMapper $mapper;

	protected function setUp(): void
	{
		$this->mapper = new GenericAirtableMapper();
	}

	public function testItShouldMapRecordToEntityCorrectly(): void
	{
		$record = [
			'id' => 'rec123',
			'createdTime' => '2023-01-01T12:00:00Z',
			'fields' => [
				'Name' => 'Test Project',
				'Description' => 'Test Description',
				'Created at' => '2023-01-01T12:00:00Z',
				'Last modified at' => '2023-01-02T12:00:00Z',
				'Id' => 123,
				'Students' => ['recStudent1', 'recStudent2'],
				'Likes' => ['recLike1', 'recLike2'],
				'Link' => 'https://example.com',
				'Promotion' => ['recPromo1'],
				'Categories' => ['recCat1', 'recCat2'],
				'Categories [Name]' => ['Cat1', 'Cat2'],
				'Promotion [Name]' => ['Promo1'],
				'Students [Full Name]' => ['Student One', 'Student Two'],
				'Likes->Count' => 2,
				'TEST' => 'test value'
			],
		];

		/** @var ProjectEntity $entity */
		$entity = $this->mapper->mapToEntity($record, ProjectEntity::class);

		$this->assertInstanceOf(ProjectEntity::class, $entity);
		$this->assertEquals('rec123', $entity->getId());
		$this->assertEquals('Test Project', $entity->getName());
		$this->assertEquals('Test Description', $entity->getDescription());
		$this->assertEquals('2023-01-01 12:00:00', $entity->getCreatedAt()->format('Y-m-d H:i:s'));
		$this->assertEquals('2023-01-02 12:00:00', $entity->getLastModifiedAt()->format('Y-m-d H:i:s'));
		$this->assertEquals(123, $entity->getProjectId());
		$this->assertEquals(['recStudent1', 'recStudent2'], $entity->getStudents());
		$this->assertEquals(['recLike1', 'recLike2'], $entity->getLikes());
		$this->assertEquals('https://example.com', $entity->getLink());
		$this->assertEquals(['recPromo1'], $entity->getPromotion());
		$this->assertEquals(['recCat1', 'recCat2'], $entity->getCategories());
		$this->assertEquals(['Cat1', 'Cat2'], $entity->getCategoryNames());
		$this->assertEquals(['Promo1'], $entity->getPromotionNames());
		$this->assertEquals(['Student One', 'Student Two'], $entity->getStudentFullNames());
		$this->assertEquals(2, $entity->getLikesCount());
		$this->assertEquals('test value', $entity->getTest());
	}

	public function testItShouldMapMissingFieldsToNull(): void
	{
		$record = [
			'id' => 'rec456',
			'createdTime' => '2023-01-01T12:00:00Z',
			'fields' => [
				'Name' => 'Partial Project',
				'Id' => 456
			],
		];

		/** @var ProjectEntity $entity */
		$entity = $this->mapper->mapToEntity($record, ProjectEntity::class);

		$this->assertInstanceOf(ProjectEntity::class, $entity);
		$this->assertEquals('rec456', $entity->getId());
		$this->assertEquals('Partial Project', $entity->getName());
		$this->assertNull($entity->getDescription());
		$this->assertNull($entity->getCreatedAt());
		$this->assertNull($entity->getLastModifiedAt());
		$this->assertEquals(456, $entity->getProjectId());
		$this->assertNull($entity->getStudents());
		$this->assertNull($entity->getLikes());
		$this->assertNull($entity->getLink());
		$this->assertNull($entity->getPromotion());
		$this->assertNull($entity->getCategories());
		$this->assertNull($entity->getCategoryNames());
		$this->assertNull($entity->getPromotionNames());
		$this->assertNull($entity->getStudentFullNames());
		$this->assertNull($entity->getLikesCount());
		$this->assertNull($entity->getTest());
	}

	public function testItShouldMapToAirtableObjectCorrectly(): void
	{
		$entity = new ProjectEntity();
		$entity->setId('rec789');
		$entity->setName('New Project');
		$entity->setDescription('New Description');
		$entity->setCreatedAt(new DateTime('2025-03-20T12:00:00Z'));
		$entity->setLastModifiedAt(new DateTime('2025-03-21T12:00:00Z'));
		$entity->setProjectId(789);
		$entity->setStudents(['recStudent3', 'recStudent4']);
		$entity->setLikes(['recLike3']);
		$entity->setLink('https://newexample.com');
		$entity->setPromotion(['recPromo2']);
		$entity->setCategories(['recCat3']);
		$entity->setCategoryNames(['NewCat']);
		$entity->setPromotionNames(['NewPromo']);
		$entity->setStudentFullNames(['New Student']);
		$entity->setLikesCount(3);
		$entity->setTest('new test');

		$airtableObject = $this->mapper->mapToAirtableObject($entity);

		$this->assertInstanceOf(stdClass::class, $airtableObject);
		$this->assertEquals('rec789', $airtableObject->id);
		$this->assertEquals([
			'Name' => 'New Project',
			'Description' => 'New Description',
			'Created at' => '2025-03-20T12:00:00+00:00',
			'Last modified at' => '2025-03-21T12:00:00+00:00',
			'Id' => 789,
			'Students' => ['recStudent3', 'recStudent4'],
			'Likes' => ['recLike3'],
			'Link' => 'https://newexample.com',
			'Promotion' => ['recPromo2'],
			'Categories' => ['recCat3'],
			'Categories [Name]' => ['NewCat'],
			'Promotion [Name]' => ['NewPromo'],
			'Students [Full Name]' => ['New Student'],
			'Likes->Count' => 3,
			'TEST' => 'new test'
		], $airtableObject->fields);
	}

	/**
	 * @return void
	 */
	public function testItShouldMapToAirtableObjectWithNullValues(): void
	{
		$this->markTestSkipped('This test has been skipped because the implementation is missing.');
		$entity = new ProjectEntity();
		$entity->setId('rec999');
		$entity->setName('Minimal Project');
		// Leave other fields unset

		$airtableObject = $this->mapper->mapToAirtableObject($entity);

		$this->assertInstanceOf(stdClass::class, $airtableObject);
		$this->assertEquals('rec999', $airtableObject->id);
		$this->assertEquals([
			'Name' => 'Minimal Project',
			'Description' => null,
			'Created at' => null,
			'Last modified at' => null,
			'Id' => null,
			'Students' => null,
			'Likes' => null,
			'Link' => null,
			'Promotion' => null,
			'Categories' => null,
			'Categories [Name]' => null,
			'Promotion [Name]' => null,
			'Students [Full Name]' => null,
			'Likes->Count' => null,
			'TEST' => null
		], $airtableObject->fields);
	}
}