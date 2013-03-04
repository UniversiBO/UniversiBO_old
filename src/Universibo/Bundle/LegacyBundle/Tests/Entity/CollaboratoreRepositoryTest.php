<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;

class CollaboratoreRepositoryTest extends DBRepositoryTest
{
    /**
     * @var DBCollaboratoreRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $container = static::$kernel->getContainer();
        $this->repository = $container->get('universibo_legacy.repository.collaboratore');
    }

    public function testFind()
    {
        $collaboratore = $this->repository->find(1);

        $this->assertInstanceOf('Universibo\\Bundle\\LegacyBundle\\Entity\\Collaboratore', $collaboratore);

        $this->assertEquals(2, $collaboratore->getIdUtente());
        $this->assertEquals('lorem ipsum', $collaboratore->getRecapito());
        $this->assertEquals('no_foto.png', $collaboratore->getFotoFilename());
        $this->assertEquals('lorem ipsum', $collaboratore->getRuolo());
    }
}
