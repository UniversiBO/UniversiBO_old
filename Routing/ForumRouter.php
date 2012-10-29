<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\ForumBundle\Routing;

use Symfony\Component\HttpFoundation\Request;
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
     * @var Request
     */
    private $request;

    /**
     * @param ForumSessionInterface $forumSession
     */
    public function __construct(ForumSessionInterface $forumSession, 
            Request $request)
    {
        $this->forumSession = $forumSession;
        $this->request = $request;
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
        return $this->addSid('/forum/index.php', '?');
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
    private function addSid($uri, $character = '&')
    {
        $sid = $this->forumSession->getSessionId($this->request);

        if (strlen($sid) > 0) {
            $uri .= $character.'sid='.$sid;
        }

        return $uri;
    }
}
