<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Security\User;

use Doctrine\Common\Persistence\ObjectManager;
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

    protected function setUp()
    {
        $this->objectManager = $this->getMock('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->personRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\PersonRepository', array('findOneByUniboId'), array(), '', false);
        $this->userRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UserRepository', array('findOneByEmail', 'findOneNotLocked'), array(), '', false);
        $this->provider = new UniversiboUserProvider($this->objectManager,
                $this->personRepository, $this->userRepository);
    }

    public function testExistingPersonNoUserSimple()
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

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($mockedUser, $user);
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
            ->userRepository
            ->expects($this->atLeastOnce())
            ->method('findOneByEmail')
            ->with($this->equalTo($claims['eppn']))
            ->will($this->returnValue(null))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->will($this->returnArgument(0))
        ;

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);
        $this->assertEquals($claims['eppn'], $user->getEmail());
        $this->assertEquals($legacyGroups, $user->getLegacyGroups());
        $this->assertEquals($usernameLocked, $user->isUsernameLocked());

        $person = $user->getPerson();

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\Person', $person);
        $this->assertEquals($claims['idAnagraficaUnica'], $person->getUniboId());
        $this->assertEquals($claims['givenName'], $person->getGivenName());
        $this->assertEquals($claims['sn'], $person->getSurname());

        $this->markTestIncomplete();
    }

    public function testNoPersonExistingEmailSimple()
    {
        $this->markTestIncomplete();
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
            array('Docente', LegacyRoles::DOCENTE, false),
            array('Studente', LegacyRoles::STUDENTE, true),
            array('PersonaleTA', LegacyRoles::PERSONALE, false),
            array('Esterno', LegacyRoles::PERSONALE, false),
            array('Accreditato', LegacyRoles::PERSONALE, false),
        );
    }
}
