<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class PhpExecutor
{
    /**
     * @var HashedCache
     */
    private $cache;
    
    public function __construct(HashedCache $cache = null)
    {
        if(is_null($cache)) {
            $cache = HashedCache::getInstance();
        }
        
        $this->cache = $cache;
    }
    
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
		$f = $this->cache->fetch('php_'.$paramString.$code);
		
		if ($f == null)
		{
			// TODO check se esiste una istruzione di return ed eventualmente aggiugnerla
			$f = create_function($paramString,$code);
//			var_dump($code);die;
			$this->cache->store('php_'.$paramString.$code, $f);
		}
		
		return array(call_user_func_array($f,array_values($in)));
	}	
}
