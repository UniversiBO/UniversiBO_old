<?php

namespace Universibo\Bundle\ForumBundle\Routing;

use Universibo\Bundle\ForumBundle\Security\ForumSession\ForumSessionInterface;

class ForumRouter
{
    /**
     * @var ForumSessionInterface
     */
    private $forumSession;

    /**
     * @param ForumSessionInterface $forumSession
     */
    public function __construct(ForumSessionInterface $forumSession)
    {
        $this->forumSession = $forumSession;
    }

    public function getForumUri($forumId)
    {
        $sid = $this->forumSession->getSessionId();

        $uri = '/forum/viewforum.php?f='.$forumId;

        if (strlen($sid) > 0) {
            $uri .= '&sid='.$sid;
        }

        return $uri;
    }
}
