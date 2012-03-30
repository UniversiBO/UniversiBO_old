<?php

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowTopic ? un'implementazione di PluginCommand.
 *
 * Dato un riferimento mostra gli argomenti di help inerenti
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato l'id_help da visualizzare.
 *
 * @package universibo
 * @subpackage Help
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowTopic extends PluginCommand {
	
	
	/**
	 * Esegue il plugin
	 *
	 * @param array $param deve contenere: 
	 *  - 'reference' il riferimento degli argomenti da visualizzare
	 *	  es: array('reference'=>'pippo') 
	 */
	function execute($param)
	{
		$reference  =  $param['reference'];

		$bc        = $this->getBaseCommand();
		$frontcontroller = $bc->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		
		$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT titolo FROM help_topic ht WHERE ht.riferimento=\''.$reference.'\'';
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
		$rows = $res->numRows();
		if( $rows == 0) 
			Error::throwError(_ERROR_DEFAULT,array('msg'=>'E\'stato richiesto un argomento dell\'help non presente','file'=>__FILE__,'line'=>__LINE__)); 
		$res->fetchInto($row);
		$topic_title = $row[0];
		$res->free();
		
		
		$query = 'SELECT he.id_help FROM help_riferimento he, help h WHERE h.id_help=he.id_help AND he.riferimento=\''.$reference.'\' ORDER BY h.indice';  //un join solo per ordinare secondo l'indice..
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		$topic = array();
		
		if( $rows > 0)
		{
			$argomenti	= array();
					
			while($res->fetchInto($row))
			{		
				$argomenti[] = $row[0];
			}
			$res->free();
			
			$lang_argomenti = $this->executePlugin('ShowHelpId', $argomenti);
	
			$topic = array('titolo'=>$topic_title ,'reference'=>$reference, 'argomenti'=>$lang_argomenti);
		}
		
		$template->assign('showTopic_topic', $topic);
		
		return $topic;
	}
}
