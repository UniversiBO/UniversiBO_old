<?php

namespace Universibo\Bundle\ContentBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use FOS\CommentBundle\Model\ThreadInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_comments")
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
     * @ORM\ManyToOne(targetEntity="Universibo\Bundle\ContentBundle\Entity\Thread")
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
