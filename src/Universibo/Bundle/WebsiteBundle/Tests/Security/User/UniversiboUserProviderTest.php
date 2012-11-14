<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Security\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManagerInterface;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\UniboGroup;
use Universibo\Bundle\CoreBundle\Entity\UniboGroupRepository;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\WebsiteBundle\Security\User\UniversiboUserProvider;

class UniversiboUserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object manager
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Provider
     *
     * @var UniversiboUserProvider
     */
    private $provider;

    /**
     * Person repository
     *
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * User repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * User manager
     *
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * Unibo Group repository
     *
     * @var UniboGroupRepository
     */
    private $uniboGroupRepository;

    protected function setUp()
    {
        $this->objectManager = $this->getMock('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->personRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\PersonRepository', array('findOneByUniboId'), array(), '', false);
        $this->userRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UserRepository', array('findOneByEmail', 'findOneAllowedToLogin'), array(), '', false);
        $this->userManager = $this->getMock('FOS\\UserBundle\\Model\\UserManagerInterface');
        $this->uniboGroupRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UniboGroupRepository', array('findOrCreate'), array(), '', false);

        $this->provider = new UniversiboUserProvider($this->objectManager,
                $this->personRepository, $this->userRepository, $this->userManager,
                $this->uniboGroupRepository);

        $this
            ->userManager
            ->expects($this->any())
            ->method('createUser')
            ->will($this->returnValue(new User()))
        ;
    }

    public function testExistingPersonExistingUserSimple()
    {
        $person = new Person();
        $person->setUniboId(42);
        $person->setGivenName('Nome');
        $person->setSurname('cognome');

        $mockedUser = new User();
        $mockedUser->setPerson($person);
        $mockedUser->setEmail('nome.cognome@unibo.it');
        $mockedUser->setEnabled(true);

        $claims = array (
            'eppn' => $mockedUser->getEmail(),
            'idAnagraficaUnica' => $person->getUniboId(),
            'isMemberOf' => 'Docente',
            'givenName' => $person->getGivenName(),
            'sn' => $person->getSurname()
        );

        $group = new UniboGroup();
        $group->setName($claims['isMemberOf']);
        $mockedUser->getUniboGroups()->add($group);

        $this->personRepository
             ->expects($this->atLeastOnce())
             ->method('findOneByUniboId')
             ->with($this->equalTo($person->getUniboId()))
             ->will($this->returnValue($person));

        $this->userRepository
             ->expects($this->atLeastOnce())
             ->method('findOneAllowedToLogin')
             ->with($this->equalTo($person))
             ->will($this->returnValue($mockedUser));

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($mockedUser, $user);

        $this->assertGroup($user, $claims['isMemberOf']);
        $this->userAssertions($user);
        $this->personAssertions($user->getPerson(), $claims);
    }

    /**
     * @dataProvider provider
     * @param string  $memberOf
     * @param integer $legacyGroups
     * @param boolean $usernameLocked
     */
    public function testNoPersonNoUser($memberOf, $legacyGroups, $usernameLocked)
    {
        $claims = array (
            'eppn' => 'nome.cognome@unibo.it',
            'idAnagraficaUnica' => 42,
            'isMemberOf' => $memberOf,
            'givenName' => 'Nome',
            'sn' => 'Cognome'
        );

        $this
            ->personRepository
            ->expects($this->atLeastOnce())
            ->method('findOneByUniboId')
            ->with($this->equalTo($claims['idAnagraficaUnica']))
            ->will($this->returnValue(null))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('findUserByEmail')
            ->with($this->equalTo($claims['eppn']))
            ->will($this->returnValue(null))
        ;

        $this
            ->userRepository
            ->expects($this->atLeastOnce())
            ->method('findOneAllowedToLogin')
            ->will($this->throwException(new NoResultException()))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('findUserByUsername')
            ->will($this->returnValue(null))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('updateUser')
        ;

        $this->expectsFindOrCreateUniboGroup($memberOf);

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($claims['eppn'], $user->getEmail());
        $this->assertEquals($legacyGroups, $user->getLegacyGroups());
        $this->assertEquals($usernameLocked, $user->isUsernameLocked());

        $this->assertGroup($user, $claims['isMemberOf']);
        $person = $user->getPerson();
        $this->userAssertions($user);
        $this->personAssertions($person, $claims);
    }

    public function testNoPersonExistingEmailSimple()
    {
        $claims = array (
            'eppn' => 'nome.cognome@unibo.it',
            'idAnagraficaUnica' => 42,
            'isMemberOf' => 'Docente',
            'givenName' => 'Nome',
            'sn' => 'Cognome'
        );

        $mockedUser = new User();
        $mockedUser->setEmail($claims['eppn']);
        $mockedUser->setUsername('username');
        $mockedUser->setEnabled(true);

        $this
            ->personRepository
            ->expects($this->atLeastOnce())
            ->method('findOneByUniboId')
            ->with($this->equalTo($claims['idAnagraficaUnica']))
            ->will($this->returnValue(null))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('findUserByEmail')
            ->with($this->equalTo($claims['eppn']))
            ->will($this->returnValue($mockedUser))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('updateUser')
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->expectsFindOrCreateUniboGroup($claims['isMemberOf']);

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertSame($mockedUser, $user);

        $person = $user->getPerson();
        $this->assertGroup($user, $claims['isMemberOf']);
        $this->userAssertions($user);
        $this->personAssertions($person, $claims);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testNoPersonExistingEmailLocked()
    {
        $claims = array (
            'eppn' => 'nome.cognome@unibo.it',
            'idAnagraficaUnica' => 42,
            'isMemberOf' => 'Docente',
            'givenName' => 'Nome',
            'sn' => 'Cognome'
        );

        $mockedUser = new User();
        $mockedUser->setEmail($claims['eppn']);
        $mockedUser->setUsername('username');
        $mockedUser->setLocked(true);
        $mockedUser->setEnabled(true);

        $this
            ->personRepository
            ->expects($this->atLeastOnce())
            ->method('findOneByUniboId')
            ->with($this->equalTo($claims['idAnagraficaUnica']))
            ->will($this->returnValue(null))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('findUserByEmail')
            ->with($this->equalTo($claims['eppn']))
            ->will($this->returnValue($mockedUser))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('updateUser')
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->expectsFindOrCreateUniboGroup($claims['isMemberOf']);

        $this->provider->loadUserByClaims($claims);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testNoPersonExistingEmailDisabled()
    {
        $claims = array (
            'eppn' => 'nome.cognome@unibo.it',
            'idAnagraficaUnica' => 42,
            'isMemberOf' => 'Docente',
            'givenName' => 'Nome',
            'sn' => 'Cognome'
        );

        $mockedUser = new User();
        $mockedUser->setEmail($claims['eppn']);
        $mockedUser->setUsername('username');
        $mockedUser->setEnabled(false);

        $this
            ->personRepository
            ->expects($this->atLeastOnce())
            ->method('findOneByUniboId')
            ->with($this->equalTo($claims['idAnagraficaUnica']))
            ->will($this->returnValue(null))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('findUserByEmail')
            ->with($this->equalTo($claims['eppn']))
            ->will($this->returnValue($mockedUser))
        ;

        $this
            ->userManager
            ->expects($this->atLeastOnce())
            ->method('updateUser')
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->expectsFindOrCreateUniboGroup($claims['isMemberOf']);

        $this->provider->loadUserByClaims($claims);
    }

    public function testExistingPersonDifferentEmailSimple()
    {
        $person = new Person();
        $person->setUniboId(42);
        $person->setGivenName('Nome');
        $person->setSurname('cognome');

        $mockedUser = new User();
        $mockedUser->setPerson($person);
        $mockedUser->setEmail('nome.cognome@unibo.it');
        $mockedUser->setEnabled(true);

        $claims = array (
            'eppn' => 'x'.$mockedUser->getEmail(),
            'idAnagraficaUnica' => $person->getUniboId(),
            'isMemberOf' => 'Docente',
            'givenName' => $person->getGivenName(),
            'sn' => $person->getSurname()
        );

        $this->personRepository
             ->expects($this->atLeastOnce())
             ->method('findOneByUniboId')
             ->with($this->equalTo($person->getUniboId()))
             ->will($this->returnValue($person));

        $this->userRepository
             ->expects($this->atLeastOnce())
             ->method('findOneAllowedToLogin')
             ->with($this->equalTo($person))
             ->will($this->returnValue($mockedUser));

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->expectsFindOrCreateUniboGroup($claims['isMemberOf']);

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($mockedUser, $user);
        $this->assertEquals($claims['eppn'], $user->getEmail());

        $this->assertGroup($user, $claims['isMemberOf']);
        $this->userAssertions($user);
        $this->personAssertions($user->getPerson(), $claims);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testNonUniqueResultException()
    {
        $person = new Person();
        $person->setUniboId(42);
        $person->setGivenName('Nome');
        $person->setSurname('cognome');

        $mockedUser = new User();
        $mockedUser->setPerson($person);
        $mockedUser->setEmail('nome.cognome@unibo.it');

        $claims = array (
            'eppn' => $mockedUser->getEmail(),
            'idAnagraficaUnica' => $person->getUniboId(),
            'isMemberOf' => 'Docente',
            'givenName' => $person->getGivenName(),
            'sn' => $person->getSurname()
        );

        $this->personRepository
             ->expects($this->atLeastOnce())
             ->method('findOneByUniboId')
             ->with($this->equalTo($person->getUniboId()))
             ->will($this->returnValue($person));

        $this->userRepository
             ->expects($this->atLeastOnce())
             ->method('findOneAllowedToLogin')
             ->with($this->equalTo($person))
             ->will($this->throwException(new NonUniqueResultException()))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->provider->loadUserByClaims($claims);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testExistingPersonDifferentEmailConflict()
    {
        $person = new Person();
        $person->setUniboId(42);
        $person->setGivenName('Nome');
        $person->setSurname('cognome');

        $mockedUser = new User();
        $mockedUser->setPerson($person);
        $mockedUser->setEmail('nome.cognome@unibo.it');

        $claims = array (
            'eppn' => 'x'.$mockedUser->getEmail(),
            'idAnagraficaUnica' => $person->getUniboId(),
            'isMemberOf' => 'Docente',
            'givenName' => $person->getGivenName(),
            'sn' => $person->getSurname()
        );

        $mockedUser2 = new User();
        $mockedUser2->setEmail($claims['eppn']);

        $this->personRepository
             ->expects($this->atLeastOnce())
             ->method('findOneByUniboId')
             ->with($this->equalTo($person->getUniboId()))
             ->will($this->returnValue($person));

        $this->userRepository
             ->expects($this->atLeastOnce())
             ->method('findOneAllowedToLogin')
             ->with($this->equalTo($person))
             ->will($this->returnValue($mockedUser))
        ;

        $this->userManager
             ->expects($this->atLeastOnce())
             ->method('findUserByEmail')
             ->with($this->equalTo($claims['eppn']))
             ->will($this->returnValue($mockedUser2))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $this->provider->loadUserByClaims($claims);
    }

    public function provider()
    {
        return array (
            array('Docente', LegacyRoles::DOCENTE, true),
            array('Studente', LegacyRoles::STUDENTE, false),
            array('PersonaleTA', LegacyRoles::PERSONALE, true),
            array('Esterno', LegacyRoles::PERSONALE, true),
            array('Accreditato', LegacyRoles::PERSONALE, true),
        );
    }

    private function assertGroup(User $user, $groupName)
    {
        $this->assertEquals(1, count($user->getUniboGroups()), '1 unibo group should be present');

        $group = $user->getUniboGroups()->first();
        $this->assertEquals($groupName, $group->getName());
    }

    private function expectsFindOrCreateUniboGroup($name)
    {
        $group = new UniboGroup();
        $group->setName($name);

        $this
            ->uniboGroupRepository
            ->expects($this->once())
            ->method('findOrCreate')
            ->with($this->equalTo($name))
            ->will($this->returnValue($group))
        ;
    }

    private function userAssertions(User $user)
    {
        $this->assertInstanceOf('DateTime', $user->getLastLogin());
    }

    private function personAssertions(Person $person, array $claims)
    {
        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\Person', $person);
        $this->assertEquals($claims['idAnagraficaUnica'], $person->getUniboId());
        $this->assertEquals($claims['givenName'], $person->getGivenName());
        $this->assertEquals($claims['sn'], $person->getSurname());
    }
}
