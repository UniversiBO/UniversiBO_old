<?php


define('STEP_COMPLETATO', 3);
define('STEP_RIFIUTATO', 2);  // VERIFY mi sa che è inutile questo valore
define('STEP_VISITATO', 1);
define('STEP_NONVISITATO', 0);

class Step
{
	/**
	 * @access private
	 */
	var $callback;

	/**
	 * @access private
	 */
	var $state=STEP_NONVISITATO;
	
	
	/**
	 * is a cache for form values
	 * @access private
	 */
	var $values=array();
	
	/**
	 * @author Pinto
	 * @access public
	 * @param string callback name of function associated with the step
	 */
	function Step ($callback) 
	{
		$this->callback = $callback;
	}
	
	/**
	 * @access public
	 * @return array all step values
	 */	
	function logMe()
	{
		return $this->values;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function getCallback () 
	{
		return $this->callback;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function getState() 
	{
		return $this->state;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function getValues() 
	{
		return $this->values;
	}
	
	
	/**
	 * @author Pinto
	 * @access public
	 * @return boolean true se l'update è stato effettuato
	 */
	function setValues($array) 
	{
		if (!is_array($array)) return false;
		$this->values = $array;
		return true;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function resetStep()
	{
		$this->state	= STEP_NONVISITATO;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function completeStep()
	{
		$this->state	= STEP_COMPLETATO;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function visitedStep()
	{
		$this->state	= STEP_VISITATO;
	}
	
	/**
	 * @author Pinto
	 * @access public
	 */
	function refusedStep()
	{
		$this->state	= STEP_RIFIUTATO;
	}

}

define('EMPTY_VALUE', -1);

/**
 * Rappresenta una lista di step. Contiene le informazioni relative necessarie 
 * 
 * @package universibo
 * @subpackage InteractiveCommand
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class StepList 
{
	/**
	 * Lista_step
	 * @access private
	 */
	var $Lista_step;
	
	/**
	 * @access private
	 */
	var $currentStep = 0;
	
	/**
	 * se vale -1 vuol dire che non c'è alcuno step completo
	 * @access private
	 */
	var $lastGoodStep = EMPTY_VALUE;
	
	/**
	 * @access private
	 */
	var $length = null;
	 
	/**
	 * 
	 * @access public
	 * @param array steps array di stringhe con i nomi delle callback
	 */ 
  	function StepList($steps = null) 
  	{
		$this->Lista_step = array();
				
		if ($steps != null) 
			foreach ($steps as $step)	
				// VERIFY lo metto o no il number nell'array?
				$this->Lista_step[] =  new Step($step);
//		echo 'costruzione ';
//		var_dump($this);
	}
	
	/**
	 * @access public
	 * @return array array bidimensionale, stepname => array di valori
	 */	
	function logMe()
	{
		$value = array();
		foreach ($this->Lista_step as $item)
			$value[$item->getCallback()] = $item->logMe();
		return $value;
	}
	
	/**
	 * @access private
	 */
	function isNextAllowed()
	{
		for ($i = ($this->lastGoodStep != EMPTY_VALUE) ? $this->lastGoodStep : 0; $i <= $this->currentStep; $i++)
		{
//			var_dump($i); echo "\n";
			$step =& $this->getStep($i);
			// VERIFY è ammissibile che uno stato sia refused?
//			var_dump($step); die;
			if ($step->getState() != STEP_COMPLETATO)
			{
				$this->lastGoodStep = max(EMPTY_VALUE, $i - 1);
				return false;
			}
		}
		$this->lastGoodStep = $this->currentStep;
		return true;
	}
	
	/**
	 * @access public
	 * @return Step object of next step to go through if all is ok, last step otherwise
	 */
	function & getNextStep () 
	{
		$item=(($this->isNextAllowed()) ? $this->currentStep : $this->lastGoodStep) + 1;
		$this->currentStep = min($this->getLength()-1, $item);
//		echo 'next '; var_dump($this);
////		return ($this->isValidIndex($item)) ? ($this->Lista_step[$item]): $this->getLastStep();
//		$ret = ($this->isValidIndex($item))? $this->getCurrentStep(): $this->getLastStep();   		 
//		return $ret;
		return $this->getCurrentStep();
	}
	
	/**
	 * @access public
	 * @return Step object of precedent step , first step otherwise
	 */
	function & getPreviousStep()
	{
		$this->currentStep -= 1;
		$this->lastGoodStep = max(EMPTY_VALUE, $this->currentStep - 1);
		if ($this->isValidIndex($this->currentStep))
			return $this->Lista_step[$this->currentStep];
		else
			return $this->getFirstStep(); 
	}
	
	/**
	 * @access public
	 * @return object  current step
	 */
	function & getCurrentStep()
	{
		return $this->Lista_step[$this->currentStep];			
	}
	
	/**
	 * @access public
	 * @return mixed array of i-th step, null if invalid index
	 */
	function & getFirstStep() 
	{
		$this->currentStep = 0;
		$this->lastGoodStep = EMPTY_VALUE;  // VERIFY ha senso modificare anche lastGoodStep?
		return $this->Lista_step[0]; 
	}
	
	/**
	 * @access public
	 * @return mixed array of i-th step, null if invalid index
	 */
	function & getLastStep() 
	{
		$this->currentStep = $this->getLength();
		return $this->Lista_step[($this->getLength()) - 1]; 
	}
	
	/**
	 * @access public
	 * @return boolean	true if the param is the last step
	 */
	function isLastStep(&$step) 
	{
		return $step == $this->Lista_step[($this->getLength()) - 1]; 
	}
	
	/**
	 * @access public
	 * @return boolean	true if the param is the first step
	 */
	function isFirstStep(&$step) 
	{
		return $step == $this->Lista_step[0]; 
	}
	
	
	
	/**
	 * @access public
	 * @return boolean	true if all step are completed
	 */
	function isComplete() 
	{
		return $this->isNextAllowed() && $this->currentStep == ($this->getLength() - 1);
	}
	
	
	/**
	 * @access private
	 * @return mixed array of i-th step, null if invalid index
	 */
	function & getStep($i) 
	{
//		var_dump($i);
//		var_dump($this->Lista_step); die;
		if(!$this->isValidIndex($i)) return null;
		return $this->Lista_step[$i]; 
	}

	/**
	 * @access public
	 */
	function getLength()
	{
		if ($this->length == null)
			$this->length = count($this->Lista_step);
		
		return $this->length;
	}
	
	/**
	 * @access private
	 */
	function isValidIndex($i)
	{
		return $i >= 0 && $i < $this->getLength();
	}
	
	/**
	 * @access public
	 */
	function invalidateAllResult()
	{
		for ($i = 0; $i < $this->getLength(); $i++)
			$this->Lista_step[$i]->resetStep();
		$this->resetInternalPointer();
	}
	
	/**
	 * @access public
	 */
	function resetInternalPointer()
	{	
		$this->lastGoodStep = EMPTY_VALUE;
		$this->currentStep = 0;
	}
		
	
//	/**
//	 * @access private
//	 * @return mixed null if invalid index, bool otherwise
//	 */
//	function getEsitoStep($i)
//	{
//		$item = getStep($i);
//		return $item['esito'];
//	}
//	
//	/**
//	 * @access private
//	 * @return mixed null if invalid index, bool otherwise
//	 */
//	function getVisitedStep($i)
//	{
//		$item = getStep($i);
//		return $item['visited'];
//	}
	
		
//	/**
//	 * @access private
//	 * @param int idStep numero dello step
//	 * @param bool esito esito della callback 
//	 * @return bool false se esito non è booleano		
//	 */
//	function setEsitoStep($idStep, $esito = true)
//	{
//		if ( ($esito !== true && $esito !== false) || !$this->isValidIndex($idStep) ) return false;
//		$this->Lista_step[$idStep]['esito'] = $esito;
//		$this->setVisitedStep($idStep);
//		return true;
//	}
//	
//	
//	/**
//	 * @param int idStep numero dello step
//	 * @access private
//	 */
//	function setVisitedStep($idStep)
//	{
//		$this->Lista_step[$idStep]['visited'] = true;
//	}
	

}

?>
