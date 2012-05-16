<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

use \Java;
use \JavaClass;

class CLInterpreter
{
    private $parser;
    private $opsTable;
    private $varTable;
    private $executorFactory;
    private $debugLevel;

    public function __construct(ExecutorFactory $executorFactory, JavaBridge $javaBridge, $basePath, $debugLevel = -1)
    {
        $this->debugLevel = $debugLevel;
        $this->executorFactory = $executorFactory;
        $javaBridge->javaRequire("$basePath;$basePath/parser;$basePath/util;$basePath/visitor;$basePath/syntaxtree");

        $this->opsTable = null;
        $this->varTable = null;
        $this->execMe(file_get_contents($basePath.'/core_operation.txt'));
    }

    /**
     * metodo che esegue il parsing e l'interpretazione del codice
     * @param datatype paramname
     */
    function execMe($codice)
    {
        try {
            $this->initParser($codice);

            // parsing
            $root = $this->parser->start();

            // executing
            $visitor = new CLVisitor($this->executorFactory, $this->opsTable, $this->varTable, false,false, $this->debugLevel);

            $root->accept(java_get_closure($visitor,null,new JavaClass("visitor.Visitor")));

            $this->opsTable = $visitor->getOpsTable();
            $this->varTable = $visitor->getVarTable();


            return $visitor->getEsito();
        }
        catch(JavaException $ex)
        {
            $exStr = java_cast($ex, "string");
            echo "Exception occured; mixed trace: $exStr\n";

            return false;
        }
    }

    private function initParser($codice)
    {
        if(!isset($this->parser)) {
            $this->parser = new Java('parser.CLParser', new Java('java.io.StringReader',$codice));
        }
        else {
            $this->parser->ReInit(new Java('java.io.StringReader',$codice));
        }
    }
}
