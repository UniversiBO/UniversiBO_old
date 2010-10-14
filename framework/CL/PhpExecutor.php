<?php
require_once('HashedCache.php');
class PhpExecutor
{
	public function run($args)
	{
//		if (array_key_exists('codice',$args)) throw new Exception('manca il parametro codice');
		$in = $args['input'];
		$code = $args['codice'];

		//$t =microtime();
		$paramString = (count($in)>0)?'$'.implode(',$',array_keys($in)) : '';
		//$paramString = array_reduce(array_keys($in),create_function('$v,$w','if($v!=null) $v.=\',\'; $v.=\'$\'.$w; return $v;'));
		//echo (microtime() - $t )."\n"; 
		//var_dump($paramString);die;
//		foreach ($in as $t)
//			echo $t . ' '.((is_object($t))?'true' : 'false'); die;
		$f = HashedCache::fetch('php_'.$paramString.$code);
		
		if ($f == null)
		{
			// TODO check se esiste una istruzione di return ed eventualmente aggiugnerla
			$f = create_function($paramString,$code);
//			var_dump($code);die;
			HashedCache::store('php_'.$paramString.$code, $f);
		}
		
		return array(call_user_func_array($f,array_values($in)));
	}	
}
?>
