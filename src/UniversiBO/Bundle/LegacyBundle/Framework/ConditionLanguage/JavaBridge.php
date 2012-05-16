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
    private static $instance = null;

    /**
     * Loads
     */
    public function load()
    {
        if(!class_exists('Java')) {
            // TODO it's an ugly hack
            @include_once self::URI;

            if(!class_exists('Java')) {
                throw new \Exception('Java extension not loaded');
            }
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
