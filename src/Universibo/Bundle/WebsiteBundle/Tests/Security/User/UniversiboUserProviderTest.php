<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Security\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManagerInterface;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
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

    protected function setUp()
    {
        $this->objectManager = $this->getMock('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->personRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\PersonRepository', array('findOneByUniboId'), array(), '', false);
        $this->userRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UserRepository', array('findOneByEmail', 'findOneNotLocked'), array(), '', false);
        $this->userManager = $this->getMock('FOS\\UserBundle\\Model\\UserManagerInterface');

        $this->provider = new UniversiboUserProvider($this->objectManager,
                $this->personRepository, $this->userRepository, $this->userManager);
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
             ->method('findOneNotLocked')
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
            ->method('findOneNotLocked')
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

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($claims['eppn'], $user->getEmail());
        $this->assertEquals($legacyGroups, $user->getLegacyGroups());
        $this->assertEquals($usernameLocked, $user->isUsernameLocked());

        $person = $user->getPerson();
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

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertSame($mockedUser, $user);

        $person = $user->getPerson();

        $this->personAssertions($person, $claims);
    }

    public function testExistingPersonDifferentEmailSimple()
    {
        $this->markTestIncomplete();
    }

    public function testExistingPersonDifferentEmailConflict()
    {
        $this->markTestIncomplete();
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

    private function personAssertions(Person $person, array $claims)
    {
        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\Person', $person);
        $this->assertEquals($claims['idAnagraficaUnica'], $person->getUniboId());
        $this->assertEquals($claims['givenName'], $person->getGivenName());
        $this->assertEquals($claims['sn'], $person->getSurname());
    }
}
