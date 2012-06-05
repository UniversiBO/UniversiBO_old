<?php
namespace Universibo\Bundle\LegacyBundle\App\InteractiveCommand;

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
    const EMPTY_VALUE = -1;
    /**
     * Lista_step
     * @access private
     */
    public $Lista_step;

    /**
     * @access private
     */
    public $currentStep = 0;

    /**
     * se vale -1 vuol dire che non c'è alcuno step completo
     * @access private
     */
    public $lastGoodStep = self::EMPTY_VALUE;

    /**
     * @access private
     */
    public $length = null;

    /**
     *
     * @access public
     * @param array steps array di stringhe con i nomi delle callback
     */
      public function __construct($steps = null)
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
    public function logMe()
    {
        $value = array();
        foreach ($this->Lista_step as $item)
            $value[$item->getCallback()] = $item->logMe();

        return $value;
    }

    /**
     * @access private
     */
    public function isNextAllowed()
    {
        for ($i = ($this->lastGoodStep != self::EMPTY_VALUE) ? $this->lastGoodStep : 0; $i <= $this->currentStep; $i++) {
//			var_dump($i); echo "\n";
            $step = $this->getStep($i);
            // VERIFY � ammissibile che uno stato sia refused?
//			var_dump($step); die;
            if ($step->getState() != Step::COMPLETATO) {
                $this->lastGoodStep = max(self::EMPTY_VALUE, $i - 1);

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
    public function  getNextStep ()
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
    public function  getPreviousStep()
    {
        $this->currentStep -= 1;
        $this->lastGoodStep = max(self::EMPTY_VALUE, $this->currentStep - 1);
        if ($this->isValidIndex($this->currentStep))

            return $this->Lista_step[$this->currentStep];
        else

            return $this->getFirstStep();
    }

    /**
     * @access public
     * @return object current step
     */
    public function  getCurrentStep()
    {
        return $this->Lista_step[$this->currentStep];
    }

    /**
     * @access public
     * @return mixed array of i-th step, null if invalid index
     */
    public function  getFirstStep()
    {
        $this->currentStep = 0;
        $this->lastGoodStep = self::EMPTY_VALUE;  // VERIFY ha senso modificare anche lastGoodStep?

        return $this->Lista_step[0];
    }

    /**
     * @access public
     * @return mixed array of i-th step, null if invalid index
     */
    public function  getLastStep()
    {
        $this->currentStep = $this->getLength();

        return $this->Lista_step[($this->getLength()) - 1];
    }

    /**
     * @access public
     * @return boolean true if the param is the last step
     */
    public function isLastStep(&$step)
    {
        return $step == $this->Lista_step[($this->getLength()) - 1];
    }

    /**
     * @access public
     * @return boolean true if the param is the first step
     */
    public function isFirstStep(&$step)
    {
        return $step == $this->Lista_step[0];
    }

    /**
     * @access public
     * @return boolean true if all step are completed
     */
    public function isComplete()
    {
        return $this->isNextAllowed() && $this->currentStep == ($this->getLength() - 1);
    }

    /**
     * @access private
     * @return mixed array of i-th step, null if invalid index
     */
    public function  getStep($i)
    {
//		var_dump($i);
//		var_dump($this->Lista_step); die;
        if(!$this->isValidIndex($i)) return null;

        return $this->Lista_step[$i];
    }

    /**
     * @access public
     */
    public function getLength()
    {
        if ($this->length == null)
            $this->length = count($this->Lista_step);

        return $this->length;
    }

    /**
     * @access private
     */
    public function isValidIndex($i)
    {
        return $i >= 0 && $i < $this->getLength();
    }

    /**
     * @access public
     */
    public function invalidateAllResult()
    {
        for ($i = 0; $i < $this->getLength(); $i++)
            $this->Lista_step[$i]->resetStep();
        $this->resetInternalPointer();
    }

    /**
     * @access public
     */
    public function resetInternalPointer()
    {
        $this->lastGoodStep = self::EMPTY_VALUE;
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
//	 * @return bool false se esito non � booleano
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
define('EMPTY_VALUE', StepList::EMPTY_VALUE);
