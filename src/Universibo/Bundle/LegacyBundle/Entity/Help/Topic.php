<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Help;

/**
 * Represents an help topic
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class Topic
{
    /**
     * @var string
     */
    private $reference;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var int
     */
    private $index;
    
    /**
     * @param string $reference
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Topic
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }
    
    /**
     * @param string $title
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Topic
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
     * @param int $index
     * @return \Universibo\Bundle\LegacyBundle\Entity\Help\Topic
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
