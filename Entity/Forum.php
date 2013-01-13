<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 */

namespace Universibo\Bundle\ForumBundle\Entity;

use InvalidArgumentException;

/**
 * Forum Entity
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Forum
{
    /**
     * Plain forum type
     */
    const TYPE_FORUM = 0;

    /**
     * Category (forum without posts)
     */
    const TYPE_CATEGORY = 1;

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
     * Forum description
     *
     * @var string
     */
    private $description;

    /**
     * Forum type
     *
     * @var integer
     */
    private $type;

    /**
     * Parent ID
     *
     * @var integer
     */
    private $parentId;

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

    /**
     * Description getter
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Description setter
     *
     * @param  string $description
     * @return Forum
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Forum type either TYPE_FORUM or TYPE_CATEGORY
     *
     * @param  integer $type
     * @return Forum
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_CATEGORY, self::TYPE_FORUM))) {
            throw new InvalidArgumentException('Forum type invalid');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Parent Forum ID getter
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Parent Forum ID setter
     *
     * @param  integer $parentId
     * @return Forum
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }
}
