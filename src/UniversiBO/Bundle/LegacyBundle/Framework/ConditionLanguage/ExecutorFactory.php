<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class ExecutorFactory
{
    private $executors = array();

    public function register($executor, $impl)
    {
        $this->executors[$executor] = $impl;
    }

    public function dispatch($executor, $arg)
    {
        if(!array_key_exists(strtolower($executor),$this->executors)) throw new Exception(); //TODO voglio veramente usare le exception???

        if(is_array($arg) && !array_key_exists('input',$arg)) $arg['input'] = array();

        $ret = call_user_func(array($this->executors[strtolower($executor)],'run'),$arg);

        if (is_array($arg) && array_key_exists('outMask',$arg)) {
            if (($tot = count($arg['outMask'])) > 1) {
                foreach ($ret as $r) {
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
