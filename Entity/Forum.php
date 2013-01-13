<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 */

namespace Universibo\Bundle\ForumBundle\Entity;

use Universibo\Bundle\ForumBundle\Entity\Forum;

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
}
