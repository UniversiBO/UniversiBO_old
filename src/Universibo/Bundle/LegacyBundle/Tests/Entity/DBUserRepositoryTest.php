<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;
use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

use Universibo\Bundle\WebsiteBundle\Entity\UserRepository;

class UserRepositoryTest extends DBRepositoryTest
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new UserRepository($this->db);
    }

    public function testSelectByUsername()
    {
        $username = 'brain';
        $user = $this->repository->findByUsername($username);

        self::assertEquals(1, $user->getId());
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
        self::assertEquals('brain', $this->repository->getUsernameFromId(1));
    }
}
