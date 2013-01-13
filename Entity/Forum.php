<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 */

namespace Universibo\Bundle\ForumBundle\Entity;

/**
 * Forum Entity
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Forum
{
    /**
     * Forum ID
     *
     * @var integer
     */
    private $id;

    /**
     * Forum name
     *
     * @var string
     */
    private $name;

    /**
     * ID getter
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * ID setter
     *
     * @param  integer $id
     * @return Forum
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name setter
     *
     * @param  string $name
     * @return Forum
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
