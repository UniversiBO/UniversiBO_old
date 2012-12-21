<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;

class DBCollaboratoreRepositoryTest extends DBRepositoryTest
{
    /**
     * @var DBCollaboratoreRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $container = static::$kernel->getContainer();

        $userRepo = $container->get('universibo_core.repository.user');
        $this->repository = $container->get('universibo_legacy.repository.collaboratore');
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
