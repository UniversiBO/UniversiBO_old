<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class HashedCache
{
	static $lista = array();
		
	static function fetch($name)
	{
		return (array_key_exists(md5($name),self::$lista)) ? self::$lista[md5($name)] : null;
	}
	
	static function store($name, $value)
	{
		self::$lista[md5($name)] = $value;
	}
	
	static function clear()
	{
		self::$lista = array();
	}
}
