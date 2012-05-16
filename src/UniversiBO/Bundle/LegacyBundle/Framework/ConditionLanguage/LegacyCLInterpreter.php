<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;


class LegacyCLInterpreter
{
    /**
     * @var CLInterpreter
     */
    private static $interpreter;

    public static function init($fc,$user = null)
    {
        $executorFactory = new ExecutorFactory();

        $executorFactory->register('php', new PhpExecutor());
        $executorFactory->register('sql', new SqlExecutor($fc->getDbConnection('main')));
        $executorFactory->register('entity', new EntityRetriever($fc,$user));

        self::$interpreter = new CLInterpreter($executorFactory, JavaBridge::getInstance(), UNIVERSIBO_ROOT . '/framework/CL', array_key_exists('dl', $_GET) ? $_GET['dl'] : -1);
    }

    /**
     * metodo che esegue il parsing e l'interpretazione del codice
     * @param datatype paramname
     */
    public static function execMe($codice)
    {
        return self::$interpreter->execMe($codice);
    }
}
