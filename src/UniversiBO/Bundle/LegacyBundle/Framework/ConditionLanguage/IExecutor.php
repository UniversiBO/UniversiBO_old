<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

interface IExecutor
{
    /**
     * @param mixed args lista parametri necessari per eseguire l'executor o direttamente il singolo parametro
     * @return array
     */
    public function run($args);
}
