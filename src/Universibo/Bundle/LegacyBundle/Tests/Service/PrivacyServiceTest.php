<?php

namespace Universibo\Bundle\LegacyBundle\Tests\Service;
use Universibo\Bundle\LegacyBundle\Service\PrivacyService;

use Universibo\Bundle\CoreBundle\Entity\User;

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
        $user = new User();

        $reflection = new \ReflectionProperty($user, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($user, 100);

        $this->assertFalse($this->service->hasAcceptedPrivacy($user));
        $this->service->markAccepted($user);
        $this->assertTrue($this->service->hasAcceptedPrivacy($user));
    }
}
