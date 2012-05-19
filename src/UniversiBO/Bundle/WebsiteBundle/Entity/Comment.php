<?php

namespace UniversiBO\Bundle\WebsiteBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\ThreadInterface;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="UniversiBO\Bundle\WebsiteBundle\Entity\Thread")
     */
    protected $thread;

    public function getThread()
    {
        return $this->thread;
    }

    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }
}
