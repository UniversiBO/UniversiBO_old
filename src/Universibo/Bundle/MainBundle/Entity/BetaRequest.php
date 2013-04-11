<?php

namespace Universibo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BetaRequest
 *
 * @ORM\Table(name="beta_requests")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\MainBundle\Entity\BetaRequestRepository")
 */
class BetaRequest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="requested_at", type="datetime")
     */
    private $requestedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * Requested by
     *
     * @ORM\ManyToOne(targetEntity="Universibo\Bundle\MainBundle\Entity\User")
     * @ORM\JoinColumn("request_user_id", nullable=false)
     * @var User
     */
    protected $requestedBy;

    /**
     * Approved by
     *
     * @ORM\ManyToOne(targetEntity="Universibo\Bundle\MainBundle\Entity\User")
     * @ORM\JoinColumn("approval_user_id")
     * @var User
     */
    protected $approvedBy;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set requestDate
     *
     * @param  \DateTime   $requestedAt
     * @return BetaRequest
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    /**
     * Get requestDate
     *
     * @return \DateTime
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }

    /**
     * Set approvedAt
     *
     * @param  \DateTime   $approvedAt
     * @return BetaRequest
     */
    public function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * Get approvedAt
     *
     * @return \DateTime
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * @param  \Universibo\Bundle\MainBundle\Entity\User $approvedBy
     * @return BetaRequest
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    /**
     * @return \Universibo\Bundle\MainBundle\Entity\User
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * @param  \Universibo\Bundle\MainBundle\Entity\User $requestedBy
     * @return BetaRequest
     */
    public function setRequestedBy($requestedBy)
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    /**
     * @return \Universibo\Bundle\MainBundle\Entity\User
     */
    public function getRequestedBy()
    {
        return $this->requestedBy;
    }
}
