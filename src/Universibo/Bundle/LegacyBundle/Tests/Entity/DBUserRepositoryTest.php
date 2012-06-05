<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;
use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

use Universibo\Bundle\LegacyBundle\Entity\DBUserRepository;

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
        $username = 'brain';
        $user = $this->repository->findByUsername($username);

        self::assertEquals(81, $user->getIdUser());
        self::assertEquals($username, $user->getUsername());
        self::assertTrue($user->matchesPassword('padrino'),
                'Should match password "padrino"');
    }

    public function testDelete()
    {
        $username = 'brain';
        $user = $this->repository->findByUsername($username);
        $this->repository->delete($user);
        self::assertTrue($user->isEliminato(), 'isEliminato directly');
        $user = $this->repository->findByUsername($username);
        self::assertTrue($user->isEliminato(), 'isEliminato after reload');
    }

    public function testUsernameExists()
    {
        self::assertTrue($this->repository->usernameExists(TestConstants::ADMIN_USERNAME));
    }

    public function testGetUsernameFromId()
    {
        self::assertEquals('brain', $this->repository->getUsernameFromId(81));
    }
}
