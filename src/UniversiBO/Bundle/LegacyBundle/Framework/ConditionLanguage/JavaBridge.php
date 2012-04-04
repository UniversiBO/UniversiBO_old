<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

/**
 * Convenience loader
 * 
 * @author davide
 */
class JavaBridge
{
    const URI = 'http://localhost:8080/JavaBridge/java/Java.inc';
    
    /**
     * @var JavaBridge
     */
    private static $instance;
    
    /**
     * @var boolean
     */
    private $loaded = false;
    
    /**
     * Loads
     */
    public function load()
    {
        if(!$this->loaded) {
            require_once self::URI;
            $this->loaded = true;
        }
        
        return $this;
    }
    
    public function javaRequire($path)
    {
        $this->load();
        
        return java_require($path);
    }

    /**
     * @deprecated use only in legacy code
     * @return \UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage\JavaBridge
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
}