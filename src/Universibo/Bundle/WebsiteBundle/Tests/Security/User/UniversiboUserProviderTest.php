<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Security\User;

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
        $this->personRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\PersonRepository', array(), array(), '', false);
        $this->userRepository = $this->getMock('Universibo\\Bundle\\CoreBundle\\Entity\\UserRepository', array(), array(), '', false);
        $this->provider = new UniversiboUserProvider($this->personRepository, $this->userRepository);
    }

    public function testExistingPersonSimple()
    {
        $claims = array (
            'eppn' => 'nome.cognome@unibo.it',
            'idAnagraficaUnica' => 42,
            'isMemberOf' => 'Docente',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
        );

        $user = $this->provider->loadUserByClaims($claims);

        $this->assertInstanceOf('Universibo\\Bundle\\CoreBundle\\Entity\\User', $user);

        $this->markTestIncomplete();
    }
}
