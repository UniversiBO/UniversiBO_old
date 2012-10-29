<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\ForumBundle\Routing;

use Universibo\Bundle\ForumBundle\Security\ForumSession\ForumSessionInterface;

/**
 * Forum Router Class
 */
class ForumRouter
{
    /**
     * Forum session
     *
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

    /**
     * Gets a forum uri
     *
     * @param  integer $forumId
     * @return string
     */
    public function getForumUri($forumId)
    {
        return $this->addSid('/forum/viewforum.php?f='.$forumId);
    }
    
    /**
     * Gets a forum uri
     *
     * @param  integer $forumId
     * @return string
     */
    public function getIndexUri()
    {
        return $this->addSid('/forum/index.php'.$forumId);
    }

    /**
     * Gets a post uri
     *
     * @param  integer $postId
     * @return string
     */
    public function getPostUri($postId)
    {
        return $this->addSid('/forum/viewtopic.php?p='.$postId);
    }

    /**
     * Appends the session ID
     *
     * @param  string $uri
     * @return string
     */
    private function addSid($uri)
    {
        $sid = $this->forumSession->getSessionId();

        if (strlen($sid) > 0) {
            $uri .= '&sid='.$sid;
        }

        return $uri;
    }
}
