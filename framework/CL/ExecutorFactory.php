<?php
class ExecutorFactory
{
	static $exs = array();
	
	function init($listaExecutor)
	{
		self::$exs = $listaExecutor;  //TODO conf in un file? al momento il visitor chiama erroneamente dispatch in maniera statica
	}
	
	function dispatch($executor, $arg)
	{
		if(!array_key_exists(strtolower($executor),self::$exs)) throw new Exception(); //TODO voglio veramente usare le exception???
		
		if(is_array($arg) && !array_key_exists('input',$arg)) $arg['input'] = array();
		
		$ret = call_user_func(array(self::$exs[strtolower($executor)],'run'),$arg);

		if (is_array($arg) && array_key_exists('outMask',$arg))
		{
			if(($tot = count($arg['outMask'])) > 1) 
			{	
				foreach($ret as $r)
				{
					for ($i=0; $i<$tot; $i++)
						$l[$arg['outMask'][$i]] = $r[$i];
					//var_dump($l);
					$s[]=$l;
				}
				return $s;
			}
		}
		//var_dump($ret);
		return $ret;
	}
}
