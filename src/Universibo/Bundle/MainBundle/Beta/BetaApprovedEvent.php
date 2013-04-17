<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davide
 * Date: 17/04/13
 * Time: 3.39
 * To change this template use File | Settings | File Templates.
 */

namespace Universibo\Bundle\MainBundle\Beta;

use Symfony\Component\EventDispatcher\Event;
use Universibo\Bundle\MainBundle\Entity\User;

class BetaApprovedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
