<?php

namespace Universibo\Bundle\MainBundle\Tests\Beta;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Universibo\Bundle\MainBundle\Beta\BetaService;
use Universibo\Bundle\MainBundle\Entity\BetaRequestRepository;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\MainBundle\Entity\UserRepository;

class BetaServiceTest extends WebTestCase
{
    /**
     * @var BetaService
     */
    private $betaService;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var BetaRequestRepository
     */
    private $betaRequestRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    protected function setUp()
    {
        static::createClient();

        $container = static::$kernel->getContainer();
        $this->betaService = $container->get('universibo_main.beta.service');
        $this->betaRequestRepo = $container->get('universibo_main.repository.beta_request');
        $this->userRepo = $container->get('universibo_main.repository.user');
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->eventDispatcher = $container->get('event_dispatcher');
    }

    public function testNoRequest()
    {
        $user = $this->getCleanUser();
    }

    /**
     * @return mixed
     */
    public function getCleanUser()
    {
        $user = $this->userRepo->find(1);
        $this->ensureNoRequest($user);
        $user->removeRole('ROLE_BETA');

        return $user;
    }

    /**
     * Tests a BetaService request
     */
    public function testRequest()
    {
        $user = $this->getCleanUser();

        $this->betaService->request($user);
        $request = $this->betaService->find($user);

        $this->assertInstanceOf('Universibo\\Bundle\\MainBundle\\Entity\\BetaRequest', $request);
        $this->assertEquals($user, $request->getRequestedBy(), 'User should match');

        $this->assertInstanceOf('DateTime', $request->getRequestedAt());
        $this->assertNull($request->getApprovedBy(), 'approvedBy should be null');
        $this->assertNull($request->getApprovedAt(), 'approvedAt should be null');
    }

    public function testApproveDispatchsEvent()
    {
        $dispatched = false;
        $this->eventDispatcher->addListener('universibo_main.beta.approved', $listener = function(Event $event) use (&$dispatched) {
            $dispatched = true;
        });

        $user = $this->getCleanUser();
        $this->betaService->request($user);
        $this->betaService->approve($user, $user);

        $this->assertTrue($dispatched, 'Event should be dispatched');
    }

    /**
     * @expectedException LogicException
     */
    public function testUnexistentRequestShouldThrowLogicException()
    {
        $user = $this->getCleanUser();

        $this->betaService->approve($user, $user);
    }

    /**
     *
     */
    public function testApprove()
    {
        $user = $this->getCleanUser();
        $this->betaService->request($user);
        $this->betaService->approve($user, $user);

        $request = $this->betaService->find($user);

        $this->assertInstanceOf('DateTime', $request->getApprovedAt());
        $this->assertEquals($user, $request->getApprovedBy());

        $this->assertTrue($user->hasRole('ROLE_BETA'), 'ROLE_BETA should be present');
    }

    /**
     * @param User $user
     */
    private function ensureNoRequest(User $user)
    {
        $requests = $this->betaRequestRepo->findByRequestedBy($user);

        foreach ($requests as $request) {
            $this->em->remove($request);
        }

        $this->em->flush();

        $this->assertNull($this->betaService->find($user), 'Request should not be found');
    }
}
