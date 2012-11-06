<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Security\User;

use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\WebsiteBundle\Security\User\UniversiboUserProvider;

class UniversiboUserProviderTest extends \PHPUnit_Framework_TestCase
{
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
        $this->personRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\PersonRepository', array('findOneByUniboId'), array(), '', false);
        $this->userRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UserRepository', array(), array(), '', false);
        $this->provider = new UniversiboUserProvider($this->personRepository, $this->userRepository);
    }

    public function testExistingPersonSimple()
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
}
