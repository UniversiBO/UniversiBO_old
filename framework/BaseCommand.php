<?php
/**
 * BaseCommand is the abstract super class of all command classes.
 *
 * @package framework
 * @version 1.1.0
 * @author  Ilias Bartolini <brain79@virgilio.it>
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */
abstract class BaseCommand 
{
	
	/**
	 * @private
	 */
	var $frontController;

	/**
	 * Initializes the base command link to front controller
	 * 
	 * This method must be called from son classes
	 * parent::initCommand();
	 *
	 * @param FrontController $frontController
	 */ 
	function initCommand( &$frontController )
	{
		$this->frontController =& $frontController;
	}
	
	
	/**
	 * Abstract method must be overridden from sons-classes
	 *
	 * @return string template identifier if command uses template engine
	 */ 
	public abstract function execute();
	
	/**
	 * Shutdown the command
	 * 
	 * This method must be overridden from Commands that need shutdown
	 */ 
	function shutdownCommand()
	{

	}



	/**
	 * Return front controller
	 *
	 * @return FrontController
	 */ 
	function &getFrontController()
	{
		return $this->frontController;
	}


	/**
	 * Executes plugin
	 *
	 * @param string $name identifier name for this plugin
	 * @param mixed $param a parameter handled by PluginCommand 
	 * @return mixed return value of plugin
	 */ 
	function executePlugin($name, $param)
	{
		$fc =& $this->getFrontController();
		return $fc->executePlugin($name,$this, $param);
	}


}
