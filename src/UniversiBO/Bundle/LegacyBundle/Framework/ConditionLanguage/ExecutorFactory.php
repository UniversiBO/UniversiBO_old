<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class ExecutorFactory
{
    private static $factory;
	
	public static function init($listaExecutor)
	{
	    self::$factory = new ExecutorFactorySf2();
	    
	    foreach($listaExecutor as $executor => $impl) {
	        self::$factory->register($executor, $impl);
	    }
	}
	
	public static function dispatch($executor, $arg)
	{
	    return self::$factory->dispatch($executor, $arg);
	}
}
