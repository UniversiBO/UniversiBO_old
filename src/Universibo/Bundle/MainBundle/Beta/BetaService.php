<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davide
 * Date: 17/04/13
 * Time: 2.23
 * To change this template use File | Settings | File Templates.
 */

namespace Universibo\Bundle\MainBundle\Beta;

use Doctrine\Common\Persistence\ObjectManager;
use Universibo\Bundle\MainBundle\Entity\BetaRequest;
use Universibo\Bundle\MainBundle\Entity\BetaRequestRepository;
use Universibo\Bundle\MainBundle\Entity\User;

class BetaService
{
    /**
     * @var BetaRequestRepository
     */
    private $betaRequestRepository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManager         $objectManager
     * @param BetaRequestRepository $betaRequestRepository
     */
    public function __construct(ObjectManager $objectManager, BetaRequestRepository $betaRequestRepository)
    {
        $this->objectManager = $objectManager;
        $this->betaRequestRepository = $betaRequestRepository;
    }

    /**
     * @param User $user
     */
    public function request(User $user)
    {
        $request = new BetaRequest();

        $request->setRequestedBy($user);
        $request->setRequestedAt(new \DateTime());

        $this->objectManager->persist($request);
        $this->objectManager->flush();
    }

    /**
     * @param  User            $user
     * @param  User            $approvedBy
     * @throws \LogicException
     */
    public function approve(User $user, User $approvedBy)
    {
        $request = $this->find($user);

        if (null === $request) {
            throw new \LogicException('Request does not exists!');
        }

        $request->setApprovedAt(new \DateTime());
        $request->setApprovedBy($approvedBy);

        $user->addRole('ROLE_BETA');

        $om = $this->objectManager;
        $om->merge($user);
        $om->merge($request);
        $om->flush();
    }

    /**
     * @param  User        $user
     * @return BetaREquest
     */
    public function find(User $user)
    {
        return $this->betaRequestRepository->findOneByRequestedBy($user);
    }
}
