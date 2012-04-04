<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Entity;

use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;

class DBUserRepositoryTest extends DBRepositoryTest
{
    /**
     * @var DBUserRepository
     */
    private $repository;
    
    public function setUp()
    {
        parent::setUp();
        $this->repository = new DBUserRepository($this->db);
    }
    
    public function testSelectByUsername()
    {
        $user = $this->repository->findByUsername('SbiellONE');

        self::assertEquals(4431, $user->getIdUser());
        self::assertEquals('SbiellONE', $user->getUsername());
        self::assertTrue($user->matchesPassword('padrino'), 'Should match password "padrino"');
    }
    
    public function testDelete()
    {
        $user = $this->repository->findByUsername('SbiellONE');
        $this->repository->delete($user);
        self::assertTrue($user->isEliminato(), 'isEliminato directly');
        $user = $this->repository->findByUsername('SbiellONE');
        self::assertTrue($user->isEliminato(), 'isEliminato after reload');
    }
}