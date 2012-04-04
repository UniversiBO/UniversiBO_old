<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

use \Java;
use \JavaClass;

class CLInterpreter
{
	static $parser;
	static $opsTable;
	static $varTable;

	function init($fc,$user = null)
	{
	    $base = UNIVERSIBO_ROOT . '/framework/CL';
	    JavaBridge::getInstance()->load()->javaRequire("$base;$base/parser;$base/util;$base/visitor;$base/syntaxtree");
	    
		if (!isset(self::$parser))
		{
			$list['php'] = new PhpExecutor();
			$list['sql'] = new SqlExecutor($fc->getDbConnection('main')); // TODO sar� il modo giusto per ottenere il rif al db??
			$list['entity'] = new EntityRetriever($fc,$user); // TODO quando l'utente diventa autenticato? problemi?
			ExecutorFactory::init($list);
			self::$opsTable = null;
			self::$varTable = null;
			self::execMe(file_get_contents($base.'/core_operation.txt'));
		}
	}

	/**
	 * metodo che esegue il parsing e l'interpretazione del codice
	 * @param datatype paramname
	 */
	function execMe($codice)
	{
		try {
			//			echo $codice ."\n";
			if(!isset(self::$parser))
			{
				self::$parser = new Java('parser.CLParser', new Java('java.io.StringReader',$codice));
			}
			else
			{
				self::$parser->ReInit(new Java('java.io.StringReader',$codice));
			}
			// parsing
			//			$tt = microtime(true);
			$root = self::$parser->start();
			//			echo "\nPARSING: ".(microtime(true) -$tt)."\n";
			// executing
			//			$root->accept(new Java('visitor.SchemeTreeBuilder'));
			$debug_level = (array_key_exists('dl',$_GET)) ? $_GET['dl'] : -1;
			//			$visitor = new CLVisitor(self::$opsTable, true,true, $debug_level);
			$visitor = new CLVisitor(self::$opsTable, self::$varTable, false,false, $debug_level);
			//$visitor = new CLVisitor(self::$opsTable, self::$varTable, true,true, $debug_level);
			//			$t = microtime(true);
			$root->accept(java_get_closure($visitor,null,new JavaClass("visitor.Visitor")));
			//			echo "\nVISITING: ".(microtime(true) -$t)."\n";
			self::$opsTable = $visitor->getOpsTable();
			self::$varTable = $visitor->getVarTable();
			//			foreach(self::$opsTable as $op)
				//				echo "\n$op";
			return $visitor->getEsito();
		}
		catch(JavaException $ex)
		{
			$exStr = java_cast($ex, "string");
			echo "Exception occured; mixed trace: $exStr\n";
			return false;
		}
	}
}


// ***************** MAIN ********************

//_cliArgs2HttpArgs();

//CLInterpreter::init(null);
//
//CLInterpreter::exec('EVENT nome DEF SQL[aa];
//EVENT altro_nome DEF PHP[echo "pippo";];
//STARTS WHEN $nome AND $altro_nome;');

//
//CLInterpreter::execMe('EVENT nome DEF SQL[aa];
//STARTS WHEN $nome;');


// ***************** END MAIN ********************

// suppress the warning message from the use of reset.
//@java_reset();
