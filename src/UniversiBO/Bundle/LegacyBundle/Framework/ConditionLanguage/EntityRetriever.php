<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

class EntityRetriever implements IExecutor
{
    public $entityTable = array();

    public function __constructor($froncontroller,$user)
    {
        $this->entityTable['fc'] =  $froncontroller;
        $this->entityTable['user'] = $user;
        if ($user != null)
            $this->entityTable['ruoli'] = $user->getRuoli();
        else
            $this->entityTable['ruoli'] = null;
        $this->entityTable['db'] = $froncontroller->getDbConnection('main');
        $this->entityTable['krono'] = $froncontroller->getKrono();
    }

    public function run($args)
    {
        //var_dump($name); die;
        if (!array_key_exists($args['codice'],$this->entityTable)) return false;

        return array($this->entityTable[$args['codice']]);
    }
}
