<?php
namespace UniversiBO\Bundle\LegacyBundle\Command\InteractiveCommand;

use UniversiBO\Bundle\LegacyBundle\Entity\DBInformativaRepository;

use UniversiBO\Bundle\LegacyBundle\App\InteractiveCommand\BaseInteractiveCommand;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

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
	public function __construct ($baseCommand) {
		parent::__construct($baseCommand);
		
		// Da qui si pu� personalizzare il contenuto che comparir�. Meglio qui o direttamente nel tpl? ah, se avessimo risolto il problema dei testi ..
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
	 * @return array 'id_info' => id, 'testo' => text dell'informativa corrente
	 */
	public static function getAttualeInformativaPrivacy () 
	{
		$repository = new DBInformativaRepository(FrontController::getDbConnection('main'));
		$informativa = $repository->findByTime(time());
		
		return array(
		        'id_info' => $informativa->getId(),
		        'testo'=> $informativa->getTesto()
		);
	}
}
