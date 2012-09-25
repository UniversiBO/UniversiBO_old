<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\WebsiteBundle\Entity\User;

class UserTest extends UniversiBOEntityTest
{
    /**
     * @var User
     */
    private $_user;

    protected function setUp()
    {
        $this->_user = new User(0, 'ROLE_ADMIN');
    }

    public function testIdGetter()
    {
        self::assertEquals(0, $this->_user->getId());
    }

    public function testGroupsGetter()
    {
        self::assertEquals('ROLE_ADMIN', $this->_user->getLegacyGroups());
    }

    public function getRoles()
    {
        self::assertEquals(array('ROLE_ADMIN'), $this->_user->getLegacyGroups());
    }

    public function testEquals()
    {
        $this->_user->setUsername('myusername');
        $other = clone $this->_user;
        $this->assertTrue($other->isEqualTo($this->_user), 'Equals of cloned object should return true');

        $other = new User(42, 'ROLE_COLLABORATOR');
        $this->assertFalse($other->isEqualTo($this->_user), 'Equals of different object should return false');
    }

    public function testSerialize()
    {
        $serialized = serialize($this->_user);
        $unserialized = unserialize($serialized);

        $this->assertEquals($this->_user, $unserialized);
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->_user, $name, $value);
    }

    public function accessorDataProvider()
    {
        return array(
                array('adUsername', 'nome.cognome@studio.unibo.it'),
                array('eliminato', true),
                array('eliminato', false),
                array('email', 'nome.cognome@studio.unibo.it'),
                array('groups', rand()),
                array('idUser', rand()),
                array('principalName', 'nome.cognome@studio.unibo.it'),
                array('salt', 'pizza'),
                array('ultimoLogin', rand()),
                array('username', 'world'),
                array('phone', '+399999999999'),
                array('banned', true),
                array('banned', false),
        );
    }
}
