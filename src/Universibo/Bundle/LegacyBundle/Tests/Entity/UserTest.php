<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;


use Universibo\Bundle\LegacyBundle\Entity\User;

class UserTest extends UniversiBOEntityTest
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
        $this->_user->setUsername('myusername');
        $other = clone $this->_user;
        $this->assertTrue($other->equals($this->_user), 'Equals of cloned object should return true');

        $other = new User(42, User::COLLABORATORE);
        $this->assertFalse($other->equals($this->_user), 'Equals of different object should return false');
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
                array('email', 'nome.cognome@studio.unibo.it'),
                array('groups', 42),
                array('idUser', 42),
                array('principalName', 'nome.cognome@studio.unibo.it'),
                array('salt', 'pizza'),
                array('ultimoLogin', 2342423423),
                array('username', 'world'),
        );
    }
}
