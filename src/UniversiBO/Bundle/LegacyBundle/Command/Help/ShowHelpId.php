<?php
namespace UniversiBO\Bundle\LegacyBundle\Command\Help;

use \Error;
use \DB;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowHelpId ? un'implementazione di PluginCommand.
 *
 * Mostra la spiegazione dell'argomento n? $id_help
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato l'id_help da visualizzare.
 * E' associato al template help_id.tpl
 *
 * @package universibo
 * @subpackage Help
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowHelpId extends PluginCommand {
	
	
	/**
	 * Esegue il plugin
	 *
	 * @param array $param deve contenere: 
	 *  - 'id_help' l'id dell'argomento o argomenti da visualizzare
	 *	  es: array("5","6") 
	 *	se viene passato 0  come parametro mostra tutti gli argomenti	
	 *  NB 0 non pu? essere l'id di una notizia
	 */
	function execute($param)
	{
		
		
		$allFlag	= false;
		$listid		= '';
		
		foreach ($param as $key => $id_help){
			if ($id_help === 0) {$allFlag = true; break;}
			if ($key === 0)	$listid = $id_help;
			else 	$listid = $listid.', '.$id_help;
		}

		$bc			     = $this->getBaseCommand();
		$frontcontroller = $bc->getFrontController();
		$template		 = $frontcontroller->getTemplateEngine();
		
		$db = FrontController::getDbConnection('main');
		
		if ($allFlag === true)
			$query = 'SELECT id_help, titolo, contenuto FROM help ORDER BY indice';
		else 
			$query = 'SELECT id_help, titolo, contenuto FROM help WHERE id_help IN ('.$listid.') ORDER BY indice';
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		$argomenti	= array();
		//if( $rows > 0) restituisco comunque l'array vuoto
	
		while($res->fetchInto($row))
		{		
			$argomenti[] = array('id' => 'id'.$row[0], 'titolo' => $row[1], 'contenuto' => $row[2]);
		}
		$res->free();
		
		$template->assign('showHelpId_langArgomento', $argomenti);
		
		return $argomenti;
		
	}
	
	
}
