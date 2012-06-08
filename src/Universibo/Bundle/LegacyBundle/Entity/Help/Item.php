<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Help;

/**
 * Entity class for Help Item
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Item
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var int
     */
    private $lastEdit;
    
    /**
     * @var int
     */
    private $index;
    
    /**
     * @param int $id
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Item
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param string $title
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Item
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @param int $lastEdit
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Item
     */
    public function setLastEdit($lastEdit)
    {
        $this->lastEdit = $lastEdit;
        
        return $this;
    }
    
    /**
     * @return number
     */
    public function getLastEdit()
    {
        return $this->lastEdit;
    }
    
    /**
     * @param int $index
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Item
     */
    public function setIndex($index)
    {
        $this->index = $index;
        
        return $this;
    }
    
    /**
     * @return number
     */
    public function getIndex()
    {
        return $this->index;
    }
}
