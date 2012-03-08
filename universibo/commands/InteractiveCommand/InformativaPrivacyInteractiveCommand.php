<?php

require_once ('InteractiveCommand/BaseInteractiveCommand'.PHP_EXTENSION);

/**
 * InformativaPrivacyInteractiveCommand is an extension of BaseInteractiveCommand class.
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class InformativaPrivacyInteractiveCommand extends BaseInteractiveCommand
{
	function InformativaPrivacyInteractiveCommand (&$baseCommand) {
		parent::BaseInteractiveCommand($baseCommand);
		
		// Da qui si può personalizzare il contenuto che comparirà. Meglio qui o direttamente nel tpl? ah, se avessimo risolto il problema dei testi ..
		$this->priority = HIGH_INTERACTION;
		$this->title = 'Informativa sulla privacy';
		$this->navigationLang['next'] = 'accetta';
	}
	
	function call_informativa ( & $item) {
		// normal view
		$formValues = $this->getCurrentValues($item);
		if (isset($_POST['action']))
		{
			// postback
			
			//NB i valori che rimangono settati in item vengono loggati, quindi ripulire da quelli che non servono
			$item->setValues(array('id_informativa' => $formValues['informativa']['id_info']));
			$item->completeStep();
		}
		
		$this->systemValues['template']->assign('call_informativa_values', $formValues);		
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return array actual form values
	 */
	function getCurrentValues(& $item) 
	{
		$values = $item->getValues();
		$valoriForm = (count($values) > 0) ? $values : 
					array(
						'informativa' => $this->getAttualeInformativaPrivacy(), 
						);
		$item->setValues($valoriForm);
		return $valoriForm;
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return array 'id_info' => id, 'testo' => text dell'informativa corrente
	 */
	function getAttualeInformativaPrivacy () 
	{
		$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT id_informativa, testo FROM  informativa 
					WHERE data_pubblicazione <= '.$db->quote( time() ).
					' AND  (data_fine IS NULL OR data_fine > '.$db->quote( time() ).')' .
							'ORDER BY id_informativa DESC';  // VERIFY così possiamo già pianificare quando una certa informativa scadrà
										
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		
		if( $rows = 0) return array();
				
		$list = array();	
		$res->fetchInto($row);
		$list['id_info'] = $row[0];
		$list['testo']= $row[1];
		$res->free();
				
		return $list;
	}
}
?>
