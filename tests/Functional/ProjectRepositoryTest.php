<?php

namespace Airtable\ORM\Tests\Functional;

use Airtable\ORM\Client\AirtableClientInterface;
use Airtable\ORM\Entity\AirtableEntityManager;
use Airtable\ORM\Tests\Common\Entity\ProjectEntity;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProjectRepositoryTest extends TestCase
{
	private AirtableEntityManager $entityManager;
	private MockObject $client;

	protected function setUp(): void
	{
		$this->client = $this->createMock(AirtableClientInterface::class);
		$this->entityManager = new AirtableEntityManager($this->client);
	}

	public function testFindAll(): void
	{
		$mockResponse = json_decode(file_get_contents(__DIR__ . '/Mocks/Response/projects.json'), true);
		$records = $mockResponse['records'];

		$this->client->method('getRecords')->willReturn($records);

		$repository = $this->entityManager->getRepository(ProjectEntity::class);
		$entities = $repository->findAll();

		$this->assertCount(2, $entities);
		$this->assertInstanceOf(ProjectEntity::class, $entities[0]);
		$this->assertInstanceOf(ProjectEntity::class, $entities[1]);

		// First project
		$this->assertEquals('recVXwCfLXpuim6US', $entities[0]->getId());
		$this->assertEquals('Microservices', $entities[0]->getName());
		$this->assertEquals("Pas de description.\n", $entities[0]->getDescription());
		$this->assertEquals('2025-03-14 22:51:54', $entities[0]->getCreatedAt()->format('Y-m-d H:i:s'));
		$this->assertEquals('2025-03-19 22:38:27', $entities[0]->getLastModifiedAt()->format('Y-m-d H:i:s'));
		$this->assertEquals(2, $entities[0]->getProjectId());
		$this->assertEquals(['rec7zsE50vIyv0QwL', 'recT2CKoQlPraEb8O'], $entities[0]->getStudents());
		$this->assertEquals(['rec94InrjBlyRLoZ6', 'recQGv5dll9VkiuUD'], $entities[0]->getLikes());
		$this->assertEquals('https://microservices.io/', $entities[0]->getLink());
		$this->assertEquals(['recW4K69I6rvCyFO6'], $entities[0]->getPromotion());
		$this->assertEquals(['rec06mcr7MUanojrq', 'rec89rrGl8eBRX87J'], $entities[0]->getCategories());
		$this->assertEquals(['Software', 'Web'], $entities[0]->getCategoryNames());
		$this->assertEquals(['5A'], $entities[0]->getPromotionNames());
		$this->assertEquals(['Melvin PIERRE', 'Anonymous ANONYMOUS'], $entities[0]->getStudentFullNames());
		$this->assertEquals(2, $entities[0]->getLikesCount());
		$this->assertEquals('test', $entities[0]->getTest());

		// Second project (partial check)
		$this->assertEquals('reczRWnXnpYEtO7JL', $entities[1]->getId());
		$this->assertEquals('Airtable S3', $entities[1]->getName());
	}

	public function testFind(): void
	{
		$mockResponse = json_decode(file_get_contents(__DIR__ . '/Mocks/Response/projects.json'), true);
		$records = [$mockResponse['records'][0]]; // First record only

		$this->client->method('getRecords')->willReturn($records);

		$repository = $this->entityManager->getRepository(ProjectEntity::class);
		$entity = $repository->find('recVXwCfLXpuim6US');

		$this->assertInstanceOf(ProjectEntity::class, $entity);
		$this->assertEquals('recVXwCfLXpuim6US', $entity->getId());
		$this->assertEquals('Microservices', $entity->getName());
		$this->assertEquals("Pas de description.\n", $entity->getDescription());
		$this->assertEquals('2025-03-14 22:51:54', $entity->getCreatedAt()->format('Y-m-d H:i:s'));
		$this->assertEquals(['Software', 'Web'], $entity->getCategoryNames());
		$this->assertEquals(2, $entity->getLikesCount());
	}

	public function testFindNotFound(): void
	{
		$this->client->method('getRecords')->willReturn([]);

		$repository = $this->entityManager->getRepository(ProjectEntity::class);
		$entity = $repository->find('rec999');

		$this->assertNull($entity);
	}

	public function testSaveNew(): void
	{
		$entity = new ProjectEntity();
		$entity->setName('New Project');
		$entity->setDescription('New project description');
		$entity->setCreatedAt(new DateTime('2025-03-20T12:00:00Z'));
		$entity->setLastModifiedAt(new DateTime('2025-03-20T12:00:00Z'));
		$entity->setProjectId(3);
		$entity->setStudents(['recNewStudent1', 'recNewStudent2']);
		$entity->setLikes(['recNewLike1']);
		$entity->setLink('https://example.com');
		$entity->setPromotion(['recNewPromo']);
		$entity->setCategories(['recNewCat1']);
		$entity->setCategoryNames(['New Category']);
		$entity->setPromotionNames(['New Promo']);
		$entity->setStudentFullNames(['New Student']);
		$entity->setLikesCount(1);
		$entity->setTest('new test');

		$this->client->expects($this->once())
			->method('createRecord')
			->with('tblTest', [
				'Name' => 'New Project',
				'Description' => 'New project description',
				'Created at' => '2025-03-20T12:00:00+00:00',
				'Last modified at' => '2025-03-20T12:00:00+00:00',
				'Id' => 3,
				'Students' => ['recNewStudent1', 'recNewStudent2'],
				'Likes' => ['recNewLike1'],
				'Link' => 'https://example.com',
				'Promotion' => ['recNewPromo'],
				'Categories' => ['recNewCat1'],
				'Categories [Name]' => ['New Category'],
				'Promotion [Name]' => ['New Promo'],
				'Students [Full Name]' => ['New Student'],
				'Likes->Count' => 1,
				'TEST' => 'new test'
			])
			->willReturn(['id' => 'recNew']);

		$repository = $this->entityManager->getRepository(ProjectEntity::class);
		$repository->save($entity);

		$this->assertEquals('recNew', $entity->getId());
	}
}