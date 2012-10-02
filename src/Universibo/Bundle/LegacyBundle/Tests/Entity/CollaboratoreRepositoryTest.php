<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

class CollaboratoreRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @var DBCollaboratoreRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = static::$kernel->getContainer()->get('universibo_website.repository.collaboratore');
    }

    public function testFind()
    {
        $collaboratore = $this->repository->find(1);

        $this->assertInstanceOf('Universibo\\Bundle\\LegacyBundle\\Entity\\Collaboratore', $collaboratore);

        $this->assertEquals(1, $collaboratore->getIdUtente());
        $this->assertEquals('9999999999', $collaboratore->getRecapito());
        $this->assertEquals('1_brain.jpg', $collaboratore->getFotoFilename());
        $this->assertEquals('fondatore - progettista software', $collaboratore->getRuolo());
    }
}
