<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

use \DB_common;

class SqlExecutor implements IExecutor
{
    /**
     * @var DB_common
     */
    private $db;

    public function __construct(DB_common $conn, HashedCache $cache = null)
    {
        $this->db = $conn;

        if($cache === null) {
            $cache = HashedCache::getInstance();
        }

        $this->cache = $cache;
    }

    public function run($args)
    {
//		if (array_key_exists('query',$args)) throw new Exception('query missing');
//		$res = $this->db->query($args);
//		while ($res->fetchInto($row))
//		{
//			$l[]=$row;
//		}
//		$res->free();
//		return $l;

        $in = $args['input'];
        $query = $args['codice'];

        foreach($in as $key => $val)
            $in[$key] = $this->db->quote($val);
//
//		extract($in);
//		$sql_query = "$query";

        $paramString = (count($in)>0)?'$'.implode(',$',array_keys($in)) : '';

        $in['db'] = $this->db;

        $f = $this->cache->fetch('sql_'.$paramString.$query);

        // TODO replace with closures?
        if ($f == null)
        {
//			echo "\n".'definisco sql' ."\n";
            $code='$s = "'.addcslashes($query,'\'').'";
            $res = $db->query($s);
            //var_dump($s);
            if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array(\'msg\'=>DB::errorMessage($res),\'file\'=>__FILE__,\'line\'=>__LINE__));
            while ($res->fetchInto($row))
            {
                $l[]=$row;
                //var_dump($row);
            }
            $res->free();

            return $l;';
            if ($paramString != '') $paramString .= ',';
            $f = create_function($paramString.'&$db',$code);
            //var_dump($query); die;
            $this->cache->store('sql_'.$paramString.$query, $f);
        }
//		echo "\ninvoco\n";
        return call_user_func_array($f,array_values($in));
    }
}
