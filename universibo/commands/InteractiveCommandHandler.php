<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);
require_once('InteractiveCommand/BaseInteractiveCommand'.PHP_EXTENSION);

/**
 * InteractiveCommandHandler is an extension of UniversiboCommand class.
 *
 * Manages Step interactions after login request by user
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class InteractiveCommandHandler extends UniversiboCommand {
	private $userLogin = null;
	
	function execute()
	{
		$fc =& $this->getFrontController();
		$template =& $this->frontController->getTemplateEngine();
//		$user =& $this->getSessionUser();
		
		//se esiste user in $_SESSION o siamo giunti dal login, o siamo nel bel mezzo di una interazione a step. 
		// VERIFY decidere se lanciare un errore o meno
		if(!isset($_SESSION['user'])) FrontController::redirectCommand();
		$this->userLogin = unserialize($_SESSION['user']);

		$referer = (array_key_exists('referer',$_SESSION)) ? 
			$_SESSION['referer'] :	((array_key_exists('HTTP_REFERER',$_SERVER))? 
						$_SERVER['HTTP_REFERER'] : '');
		$_SESSION['referer'] = ($referer != '') ? 
			$referer : $fc->getReceiverUrl($fc->getReceiverId()); // VERIFY meglio in homepage o in myuniversibo se loggato?

		$activeSteps = (array_key_exists('activeSteps', $_SESSION)) ? $_SESSION['activeSteps'] : $this->getActiveInteractiveCommand();
//		var_dump($activeSteps); die;		
		if (count($activeSteps) == 0) 
		{	
			// completo il login dell'utente
			$_SESSION = array();
			session_destroy();
			session_start();
			$this->userLogin->updateUltimoLogin(time());
			$this->setSessionIdUtente($this->userLogin->getIdUser());
			$fc->setStyle($this->userLogin->getDefaultStyle());
			
			require_once ('ForumApi'.PHP_EXTENSION);
			$forum = new ForumApi();
			$forum->login($this->userLogin);
			
			if ( !strstr($referer, 'forum') && ( !strstr($referer, 'do') || strstr($referer, 'do=ShowHome')  || strstr($referer, 'do=ShowError') || strstr($referer, 'do=Login') || strstr($referer, 'do=RegStudente')))
				FrontController::redirectCommand('ShowMyUniversiBO');
			else if (strstr($referer, 'forum'))
				FrontController::redirectUri($forum->getMainUri());
			else
				FrontController::redirectUri($referer);
		}
		
		
		$action = null;
		$action = (array_key_exists('action',$_GET) && in_array($_GET['action'], array(CANC_ACTION, BACK_ACTION))) ? $_GET['action'] : $action;
		if (isset($_POST['action'])) $action = NEXT_ACTION; 
		
		$currentStep = current($activeSteps);		
		if (! $this->isAllowedInteractionForActualUser($this->userLogin, $currentStep))
			$this->updateActiveSteps($activeSteps);	
			
		$esito = $this->executePlugin($currentStep['className'], $action);
		
		//TODO verificare se esito � array?
		if (isset($esito['error'])) 
		{			
			/** 
			 * @todo mail agli sviluppatori per correggere subito l'errore, altrimenti la gente non si logga pi�!!
			 * per il futuro, pensare a come disabilitare in automatico gli InteractiveCommand con errore
			 */			
			require_once('Notifica/NotificaItem'.PHP_EXTENSION);
			$notifica_titolo_long = 'WARNING: l\'InteractiveCommand '.$currentStep['className'].' e\' errato';
			$notifica_titolo = substr($notifica_titolo_long, 0 , 199);
			$notifica_dataIns = time();
			$notifica_urgente = false; // TODO settare come urgente
			$notifica_eliminata = false;
			$notifica_messaggio = 
'~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
'.$notifica_titolo_long.'

Probabilmente l\'InteractiveCommand '.$currentStep['className'].' non ha metodi implementati.
Risolvere subito il problema o disabilitarlo quanto prima,
perch� impedisce il login agli utenti		
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~';
					
			$notifica_destinatario = 'mail://'.$frontcontroller->getAppSetting('develEmail');
			
			$notifica = new NotificaItem(0, $notifica_titolo, $notifica_messaggio, $notifica_dataIns, $notifica_urgente, $notifica_eliminata, $notifica_destinatario );
			$notifica->insertNotificaItem();
			
			$this->updateActiveSteps($activeSteps);
		}
//		var_dump($esito);
		if ($action == CANC_ACTION && $esito['priority'] == HIGH_INTERACTION && $esito['cancelled'])
		{			
			$_SESSION = array();
			session_destroy();
			session_start();
			// TODO messaggio di errore per spiegare che � obbligatorio accettare?
			FrontController::redirectUri($referer);
		}	
		
		//  Elimino dalla lista gli step cancellati dall'utente e quelli completati con successo
		if ($esito['complete'] || ($action == CANC_ACTION && $esito['priority'] != HIGH_INTERACTION))
			$this->updateActiveSteps($activeSteps);	
			
		$callbackName = $esito['stepName'];
		
		$template->assign('InteractiveCommandHandler_stepPath', 'InteractiveCommand/' . $currentStep['className'] .'/'. $callbackName .'.tpl' );  //stepPath. estensione  e path hardcoded
		$template->assign('InteractiveCommandHandler_title_lang', $esito['title'] );  // TODO dare un title ad ogni InteractiveCommand?
		if(array_key_exists('back',$esito['navigation']))
		{
			$template->assign('InteractiveCommandHandler_back_uri', 'index.php?do='.$fc->getCommandRequest().'&action='.BACK_ACTION );
			$template->assign('InteractiveCommandHandler_back_lang', $esito['navigation']['back']);
		}
		$template->assign('InteractiveCommandHandler_canc_uri', 'index.php?do='.$fc->getCommandRequest().'&action='.CANC_ACTION);
		$template->assign('InteractiveCommandHandler_canc_lang', $esito['navigation']['canc']);
		$template->assign('InteractiveCommandHandler_next_lang', $esito['navigation']['next'] );
	}
	
	/**
	 * @author Pinto
	 * @access private
	 */
	function updateActiveSteps(&$activeSteps)
	{
		unset($activeSteps[key($activeSteps)]);
		$_SESSION['activeSteps'] = $activeSteps;
		FrontController::redirectCommand('InteractiveCommandHandler');
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return boolean 
	 */
	function isAllowedInteractionForActualUser ( & $user, & $activeStep) 
	{
//		var_dump($activeStep); die;
		if (empty($activeStep['restrictedTo'])) return true;  // nessun gruppo particolare specificato
		
		$allowedGroups = array();
		foreach ($activeStep['restrictedTo'] as $i)
			if (defined($i))
				$allowedGroups[] = constant($i);
		// verifico che il gruppo dell'utente sia tra quelli specificati 
//		var_dump($allowedGroups); die;
		return (in_array($user->getGroups(), $allowedGroups));
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return array list of available InteractiveCommand
	 */
	function getAllInteractiveCommand () 
	{
		$list = $this->frontController->getAvailablePlugins();
//		var_dump($list);
		$steps = array();
		foreach ($list as $item)
		{
			include_once('InteractiveCommand/' . $item['className'] . PHP_EXTENSION);
			if (in_array('BaseInteractiveCommand', $this->get_all_ancerstors_of_class($item['className'])) ||
				in_array('baseinteractivecommand', $this->get_all_ancerstors_of_class($item['className']))) 
					if(empty($item['condition']) || $this->evaluateCondition($item['condition']))
						$steps[] = $item;
//			var_dump($item);
//			var_dump(get_parent_class($item)); die;
		}
//		var_dump($steps); die;
		return $steps;
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return array list of ancestor (almeno quelli che riesce a trovare)
	 */
	function get_all_ancerstors_of_class ($class) {
		$list = array();
//		$ancestor = get_parent_class($class);
//		while ($ancestor != null && $ancestor != 'stdClass' )
//		{
//			$list[] 	= $ancestor;
//			$ancestor 	= get_parent_class($ancestor);
//		}
		// versione alternativa migliore. PS servira' il controllo != da stdClass?
		$parentClass = $class;
		while(is_string($parentClass = get_parent_class($parentClass)) && strcasecmp($parentClass, 'stdClass') != 0) {
            $list[] = $parentClass;
        }
//        var_dump($list);
		// TODO se il while si interrompe per il null, vuol dire che la lista � parziale. Gestirlo in modo diverso?
		return $list;
		
				
		
	}
	
	
	/**
	 * @author Pinto
	 * @access private
	 * @return array list of active InteractiveCommand
	 */
	function getActiveInteractiveCommand () 
	{
		// TODO: migliorare il confronto
		$allSteps 	= $this->getAllInteractiveCommand();
		$stepsDone 	= $this->getCompletedInteractiveCommandByUser();
//		var_dump($allSteps);
//		var_dump($stepsDone); die;
		$ret = array();
		foreach ($allSteps as $i)
			if (!in_array($i['className'], $stepsDone))
				$ret[] = $i;
				
		return $ret;
	}
	
	/**
	 * @author Pinto
	 * @access private
	 * @return mixed array with the list of InteractiveCommand already completed by current user, false if empty
	 */
	function getCompletedInteractiveCommandByUser() 
	{
		$db =& FrontController::getDbConnection('main');
		$user =  unserialize($_SESSION['user']);
		
		$query = 'SELECT id_step, nome_classe FROM  	step_log 
					WHERE id_utente = '.$db->quote( $user->getIdUser() ).
					' AND  esito_positivo IS NOT NULL '.		// NB suppongo che quelli con esito 'n' siano quelli una-tantum (bassa priorit�) rifiutati 
					'';					
		$res =& $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		
		if( $rows = 0) return array();
				
		$list = array();	
		while ( $res->fetchInto($row) )
		{
			$list[$row[0]]= $row[1];
		}		
		$res->free();
				
		return $list;
	}	

	/**
	 * valuta se la condizione espressa in linguaggio ConditionLanguage � verificata
	 *
	 * @param string $CL_code
	 * @return boolean
	 */
	function evaluateCondition($CL_code)
	{
		require_once('CL/CLInterpreter'.PHP_EXTENSION);
		CLInterpreter::init($this->getFrontController(), $this->userLogin);
		return CLInterpreter::execMe($CL_code);
	}
	
}
