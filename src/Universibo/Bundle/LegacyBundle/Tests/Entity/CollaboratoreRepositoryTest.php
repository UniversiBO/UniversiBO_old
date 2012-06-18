<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;

class CollaboratoreRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @var DBCollaboratoreRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = static::$kernel->getContainer()->get('universibo_legacy.repository.collaboratore');
    }

    public function testFind()
    {
        $collaboratore = $this->repository->find(1);

        $this->assertEquals(1, $collaboratore->getIdUtente());
        $this->assertEquals('9999999999', $collaboratore->getRecapito());
        $this->assertEquals('1_brain.jpg', $collaboratore->getFotoFilename());
        $this->assertEquals('fondatore - progettista software', $collaboratore->getRuolo());
    }
}
