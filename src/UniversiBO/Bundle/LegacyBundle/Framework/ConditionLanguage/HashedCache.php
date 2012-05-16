<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class HashedCache
{
    /**
     * @var HashedCache
     */
    private static $instance;

    private $lista = array();

    public function fetch($name)
    {
        return (array_key_exists(md5($name),$this->lista)) ? $this->lista[md5($name)] : null;
    }

    public function store($name, $value)
    {
        $this->lista[md5($name)] = $value;
    }

    public function clear()
    {
        $this->lista = array();
    }

    /**
     * @deprecated use only in legacy code
     * @return HashedCache
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }


        return self::$instance;
    }
}
