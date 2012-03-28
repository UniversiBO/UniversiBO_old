<?php
use UniversiBO\Legacy\Framework\FrontController;
use UniversiBO\Legacy\Framework\PluginCommand;

require_once('InteractiveCommand/StepList'.PHP_EXTENSION);

define('CALLBACK', 'call_');

// livelli di priorità
define('HIGH_INTERACTION', 		1); //è obbligatorio un esito positivo, altrimenti non si completa il login
define('NORMAL_INTERACTION',	2); //in caso di cancel, viene richiesto ad ogni login
define('LOW_INTERACTION', 		3); //in caso di cancel, non viene più richiesto. [interazione una-tantum]

//action
define('NEXT_ACTION',	'next');
define('BACK_ACTION',	'back');
define('CANC_ACTION',	'canc');

define('VALUES_SEPARATOR', '|');
/**
 * Classe di base per l'interazione a step.
 * 
 * uno step è rappresentato da una funzione che comincia con il prefisso definito nella costante CALLBACK.
 * In caso di esito positivo, la funzione che rappresenta lo step deve eseguire completeStep() sullo step corrente.
 * NB i metodi callback devono cominciare con il prefisso definito nella costante CALLBACK [ alla junit ^_^ ] 
 *
 * @package universibo
 * @subpackage InteractiveCommand
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */

 
class BaseInteractiveCommand extends PluginCommand
{
	/**
	 * è uno StepList
	 * @access private
	 */
	var $listaStep;
	
	/**
	 * per comodità, metto a disposizione dei callback i riferimenti ai principali elementi del sistema
	 * @author Pinto
	 * @access private
	 */
	 var $systemValues = array();
	 
	/**
	 * @access private
	 */
	var $id_utente;  //VERIFY servirà veramente?
	 
	 
	/**
	 * @access private
	 */
	var $priority=NORMAL_INTERACTION;
	
	/**
	 * @access private
	 */
	var $title = 'Compila in tutte le parti';
	
	/**
	 * @access private
	 */
	var $msgOnCancelByUser = 'Senza l\'accettazione di tutte le condizioni ed il completamento di tutti i dati è impossibile poter continuare ad usufruire del servizio';
	
	/**
	 * flag. true se cancelled by user
	 * @access private
	 */
	var $cancelled;
	
	
	/**
	 * @access private
	 */
	var $navigationLang = array(
							'back' => 'indietro',
							'next' => 'avanti',
							'canc' => 'annulla'
							);
	
	var $navigationLastNextLang = 'accetta';							
	
	/**
	 * Costruttore
	 * 
	 * @return void
	 */
	public function __construct($baseCommand)
	{
		// VERIFY andrà bene questo costruttore?
		parent::__construct($baseCommand);
		
		$this->systemValues['bc'] 	= $baseCommand;
		$this->systemValues['fc'] 	= $baseCommand->getFrontController();
//		$this->systemValues['user'] = $baseCommand->getSessionUser();  // con la modifica delle 19.00 del 14-05-06 a login.php, l'identità dell'utente non è qui
		$this->systemValues['template'] = $this->systemValues['fc']->getTemplateEngine();
		$this->systemValues['krono']	= $this->systemValues['fc']->getKrono();
		
		if (isset($_SESSION['user'])) $this->systemValues['user'] = unserialize($_SESSION['user']);
		else  Error::throwError(_ERROR_CRITICAL,array('id_utente' => 0,'msg'=>'Si è verificato un errore imprevisto, la preghiamo di avvisare gli amministratori di sistema','file'=>__FILE__,'line'=>__LINE__, 'template_engine' => & $this->systemValues['template']) );
//		var_dump($_SESSION['user']);
		array_key_exists('lista', $_SESSION) ? $this->listaStep = unserialize($_SESSION['lista']) : $this->listaStep = $this->getAllCallback();

		if(array_key_exists('idUtenteStep', $_SESSION)) 
			$this->id_utente = $_SESSION['idUtenteStep']; 
		else
		{
			$this->id_utente = $this->systemValues['user']->getIdUser();
			$_SESSION['idUtenteStep'] = $this->getIdUtente();
		}
		
		$this->cancelled = array_key_exists('canc', $_SESSION);
	}
	

	/**
	 * @author fab
	 * @access private
	 * @return StepList collezione degli step dell'interazione
	 */
	function getAllCallback() 
	{
		    $nome_classe = get_class($this);
		    $metodi_classe = get_class_methods($nome_classe);
		    $callback = array();
		    foreach ($metodi_classe as $item)
		    	if (strncasecmp($item, CALLBACK, strlen(CALLBACK)) == 0  && !in_array($item, $callback))
		    		$callback[] = $item;
		    return new StepList($callback);
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function getPriority () {
		return $this->priority;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function getIdUtente () {
		return $this->id_utente;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 * @return string callback name of current step
	 */
	function getCurrentCallbackName () {
		$item = $this->listaStep->getCurrentStep();
		return $item->getCallback();
	}
	
	/**
	 * @author fab
	 * @access private
	 */
	function doCallback( & $item) 
	{
		$item->visitedStep();
		$action = $item->getCallback();
//		var_dump($action);
		// check if method exists
		if (!in_array($action, get_class_methods(get_class($this))))
		{
//			var_dump(debug_backtrace()); die;
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->systemValues['user']->getIdUser(),'msg'=>'Si è verificato un errore nell\'interazione, la preghiamo di avvisare gli amministratori di sistema','file'=>__FILE__,'line'=>__LINE__, 'template_engine' => & $this->systemValues['template']) );
		}
		// se NEXT_ACTION stiamo effettuando il postback, altrimenti stiamo visualizzando semplicemente lo step richiesto 
		$this->$action($item);
//		var_dump(debug_backtrace());
		$_SESSION['lista'] = serialize($this->listaStep);
	}
	
	
	/**
	 * @author fab
	 * @access private
	 * @param string msg messaggio di errore
	 */
	function errore($msg) 
	{
		Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $this->systemValues['user']->getIdUser(), 'msg' => $msg, 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $this->systemValues['template']));
	}
	
	/**
	 * @author fab
	 * @access private
	 * @param string $name
	 * @param boolean $complete
	 * @return array	 array with useful values for InteractiveCommandHandler
	 */
	function returnState($complete = false, $canc = false ) 
	{
		$i = & $this->listaStep->getCurrentStep();
		$nav = $this->navigationLang;
		if ($this->listaStep->isFirstStep($i))
			unset($nav['back']);
		if ($this->listaStep->isLastStep($i))
			$nav['next'] = $this->navigationLastNextLang;
			
		return array('stepName' => $this->getCurrentCallbackName(), 
					 'complete' => $complete, 
					 'priority' => $this->priority, 
					 'title' => $this->title,
					 'navigation' =>  $nav,
					 'cancelled' => $canc
					 );					 
	}
	
	/**
	 * @author fab
	 * @access private
	 * @param string $name
	 * @param boolean $complete
	 * @return array	 array with useful values for InteractiveCommandHandler
	 */
	function returnErrorState($error) 
	{
		return array('error' => $error);							 
	}
	
	
	/**
	 * Esegue la callback associata al prossimo step 
	 *
	 * @access public
	 * @param array $param 
	 * @return mixed true if all InteractiveCommand callbacks are ok, false if cancelled by user, null otherwise
	 */
	function execute($param)
	{		
//		var_dump($this); die;
		// check if current session user is the one who started the InteractiveCommand
		if ($this->id_utente != $this->systemValues['user']->getIdUser())
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->systemValues['user']->getIdUser(),'msg'=>'L\'utente della sessione corrente non è autorizzato a completare l\'interazione','file'=>__FILE__,'line'=>__LINE__, 'template_engine' => & $this->systemValues['template']) );
		
		// check if cancelled by user
		if ($param == CANC_ACTION)
		{			
//			var_dump($this);
			$this->listaStep->invalidateAllResult();
				
			// se è la prima volta che l'utente annulla, mostro il primo step con mess di spiegazione, altrimenti esco dall'interazione a step
			if (!$this->cancelled && $this->getPriority()  == HIGH_INTERACTION)
			{
				$this->errore($this->msgOnCancelByUser);
				$_SESSION['canc'] = true;
			}
			else
			{
				unset($_SESSION['idUtenteStep']);
				unset($_SESSION['lista']);
				if ($this->getPriority()  == LOW_INTERACTION) 
					$this->storeInteractiveCommandLog(false);
				return $this->returnState(false, true);
			}			
		}		
		
		if ($this->listaStep->getLength() == 0)
			return $this->returnErrorState(get_class($this) . ' è uno InteractiveCommand attivo senza callback (o step) implementati; provvedere quanto prima');

		
		$item = $this->listaStep->getCurrentStep();
//		var_dump($item);
		if ($param == BACK_ACTION)$item = $this->listaStep->getPreviousStep();
//		var_dump($item);
		$this->doCallback($item);
		
		if($this->listaStep->isComplete())
		{
			$this->storeInteractiveCommandLog(true);
			unset($_SESSION['idUtenteStep']);
			unset($_SESSION['lista']);
			return $this->returnState(true);
		} 		
		
		if ($param == NEXT_ACTION)
		{
			$this->cancelled = false; // se l'utente dopo un cancel si ravvede e da un next, allora gli diamo la possibilità di premere cance un'altra volta per sbaglio :)
			$item = $this->listaStep->getNextStep();
			$this->doCallback($item);
		} 
//		echo time() . "\n";
//		debug_print_backtrace();
		
		return $this->returnState(null);
	}
	
	/**
	 * registra nel db l'esito dell'interazione
	 * @author Pinto
	 * @param boolean esito esito dell'interazione
	 * @access private
	 */
	function storeInteractiveCommandLog ($complete = false) 
	{
//		echo 'inizio il log'; die;
		$db = FrontController::getDbConnection('main');
		
        ignore_user_abort(1);
        $db->autoCommit(false);
        $next_id = $db->nextID('step_id_step');
        if (DB::isError($next_id)){
			$db->rollback();
			Error::throwError(_ERROR_CRITICAL,array('msg'=>$next_id->getUserInfo(),'file'=>__FILE__,'line'=>__LINE__));
		}
		$esito = ($complete) ? 'S' : 'N';
		$query = 'INSERT INTO step_log (id_step, id_utente, data_ultima_interazione, nome_classe, esito_positivo) VALUES '.
					'( '.$next_id.' , '.
					$db->quote($this->getIdUtente()).' , '.
					$db->quote(time()).' , '.
					$db->quote(get_class($this)).' , '.
					$db->quote($esito).' )'; 
		$res = $db->query($query);
		if (DB::isError($res)){
			$db->rollback();
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
				
		if ($complete)
			foreach ($this->listaStep->logMe() as $callback => $params)
				foreach ($params as $key => $val)
				{
					// VERIFY ha senso come tratto gli eventuali array? o è meglio fare più inserimenti?
					$value = (is_array($val)) ? implode(VALUES_SEPARATOR, $val): $val ;
					$query = 'INSERT INTO step_parametri (id_step, callback_name, param_name, param_value) VALUES '.
							'( '.$next_id.' , '.
							$db->quote($callback).' , '.
							$db->quote($key).' , '.
							$db->quote($val).' )'; 
					$res = $db->query($query);
					//var_dump($query);
					if (DB::isError($res)){
						$db->rollback();
						Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
					}
				}
		
		$db->commit();
		$db->autoCommit(true);
		ignore_user_abort(0);
	}
	
	/*
	 * query di verifica per le tabelle degli step_command. tutto ok se il risultato è 0 righe.
	 * 'select * from step_parametri where id_step not in (select id_step from step_log )'
	 */ 
	 
}
