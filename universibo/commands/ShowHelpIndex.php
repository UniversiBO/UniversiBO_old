<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);

/**
 * ShowHelpIndex is an extension of UniversiboCommand class.
 *
 * It shows help page order by index
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowHelpIndex extends UniversiboCommand {
	function execute(){
		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		
		$template -> assign('showHelpIndex_langAltTitle', 'Help');
		
		$this->executePlugin('ShowHelpId', array( 'id_help' => 0 ) );
		
		return 'default';
	}
}  
 
 
 
?> 