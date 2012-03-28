<?php

require_once ('InteractiveCommand/BaseInteractiveCommand'.PHP_EXTENSION);

/**
 * ProvaInteractiveCommand is an extension of BaseInteractiveCommand class.
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ProvaInteractiveCommand extends BaseInteractiveCommand
{
	public function __construct ($baseCommand) {
		parent::__construct($baseCommand);
//		$this->priority = HIGH_INTERACTION;
		$this->title = 'ecco il titolo personalizzato';
		// modificate la seguente variabile se non va bene il mess di default
		//$this->msgOnCancelByUser = 'ecco il messaggio personalizzato in caso di cancel, quando la priorità è alta. Fate buon uso di questo strumento';
	}
	
	function call_example ( & $item) {
		// TODO normal view
		$values = $item->getValues();
		$valoriForm = (count($values) > 0) ? $values : array('a' => 0, 'b' => 0);
		
		
		if (isset($_POST['action']))
		{
			// TODO postback
			
			//per lanciare un error_notice si può fare direttamente:
			// $this->errore($messaggio_di_errore);
			
			// supponiamo che l'utente abbia modificato i valori
			$item->setValues(array('a' => 1, 'b' => 2));
			$item->completeStep();
		}
		
		// per assegnare i valori al tpl:
		// $this->systemValues['template']->assign('name', $valoriForm);
		
	}
	
	function call_example2 ( & $item) {
		// TODO normal view
		
		if (isset($_POST['action']))
		{
			// TODO postback
//			echo 'postback';
			$item->completeStep();
		}
	}
}
