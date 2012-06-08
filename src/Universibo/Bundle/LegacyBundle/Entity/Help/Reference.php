<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Help;

/**
 * References a group of news with a simbolic name (id)
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Reference
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $helpId;

    /**
     * @param  string                                                $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Reference
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int                                                   $helpId
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Reference
     */
    public function setHelpId($helpId)
    {
        $this->helpId = $helpId;

        return $this;
    }

    /**
     * @return number
     */
    public function getHelpId()
    {
        return $this->helpId;
    }
}
