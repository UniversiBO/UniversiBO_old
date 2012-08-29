<?php
namespace Universibo\Bundle\LegacyBundle\App\InteractiveCommand;

class Step
{
    const COMPLETATO = 3;
    const RIFIUTATO = 2;
    const VISITATO = 1;
    const NONVISITATO = 0;

    /**
     * @access private
     */
    public $callback;

    /**
     * @access private
     */
    public $state=self::NONVISITATO;

    /**
     * is a cache for form values
     * @access private
     */
    public $values=array();

    /**
     * @author Pinto
     * @access public
     * @param string callback name of function associated with the step
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @access public
     * @return array all step values
     */
    public function logMe()
    {
        return $this->values;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function getCallback ()
    {
        return $this->callback;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @author Pinto
     * @access public
     * @return boolean true se l'update è stato effettuato
     */
    public function setValues($array)
    {
        if (!is_array($array)) return false;
        $this->values = $array;

        return true;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function resetStep()
    {
        $this->state	= self::NONVISITATO;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function completeStep()
    {
        $this->state	= self::COMPLETATO;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function visitedStep()
    {
        $this->state	= self::VISITATO;
    }

    /**
     * @author Pinto
     * @access public
     */
    public function refusedStep()
    {
        $this->state	= self::RIFIUTATO;
    }

}

define('STEP_COMPLETATO', Step::COMPLETATO);
define('STEP_RIFIUTATO', Step::RIFIUTATO);  // TODO VERIFY mi sa che è inutile questo valore
define('STEP_VISITATO', Step::VISITATO);
define('STEP_NONVISITATO', Step::NONVISITATO);
