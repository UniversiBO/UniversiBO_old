<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $_user;

    protected function setUp()
    {
        $this->_user = new User(0, User::ADMIN);
    }

    public function testIdGetter()
    {
        self::assertEquals(0, $this->_user->getIdUser());
    }

    public function testGroupsGetter()
    {
        self::assertEquals(User::ADMIN, $this->_user->getGroups());
    }

    public function getRoles()
    {
        self::assertEquals(array('ROLE_ADMIN'), $this->_user->getGroups());
    }

    public function testEquals()
    {
        $this->markTestIncomplete('Should test equals');
    }

    public function testSerialize()
    {
        $this->markTestIncomplete('Should test serialization');
    }
}
