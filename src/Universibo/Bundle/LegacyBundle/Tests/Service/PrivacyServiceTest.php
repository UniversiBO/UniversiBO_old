<?php

namespace Universibo\Bundle\LegacyBundle\Tests\Service;
use Universibo\Bundle\LegacyBundle\Service\PrivacyService;

use Universibo\Bundle\LegacyBundle\Entity\User;

use Universibo\Bundle\LegacyBundle\Tests\Entity\DoctrineRepositoryTest;

class PrivacyServiceTest extends DoctrineRepositoryTest
{

    /**
     * @var PrivacyService
     */
    private $service;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = static::$kernel->getContainer()->get('universibo_legacy.service.privacy');
    }

    public function testSimple()
    {
        $user = new User(100, 0);
        $this->assertFalse($this->service->hasAcceptedPrivacy($user));
        $this->service->markAccepted($user);
        $this->assertTrue($this->service->hasAcceptedPrivacy($user));
    }
}
