<?php
use Universibo\Bundle\LegacyBundle\Framework\BaseReceiver;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

list($usec, $sec) = explode(" ", microtime());
$page_time_start = ((float)$usec + (float)$sec);


/**
 * The receiver. 
 * Code to activate the framework system.
 * One application can be built by multiple receivers.
 * 
 * @package framework
 * @version 1.0.0
 * @author Deepak Dutta, http://www.eocene.net, 
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Receiver extends BaseReceiver {
	
	/**
 	* Return the receiver name identifier
 	*
 	* @return string
	*/
	function getIdentifier()
	{
		return $this->receiverIdentifier;
	}

	/**
 	* Transforms the command line args to $_GET superglobal array
	*/
	function _cliArgs2HttpArgs()
	{
		global $argc, $argv;
		
		if ($argc != 2)
			die('Passare come unico parametro la stringa che identifica la richiesta GET desiderata');
			
		$couples = explode('&', $argv[1]);
		
		foreach ($couples as $value)
		{
			$couple = explode('=', $value);
			
			if(!array_key_exists(0, $couple) || !array_key_exists(1, $couple) || count($couple)>2)
				die('Parametro della richiesta non valida');
			
			$_GET[$couple[0]] = $couple[1];
		}
		
	}
	
	
	/**
 	* Main code for framework activation, includes Error definitions
 	* and instantiates FrontController
	*/
	public function main()
	{
		$this->_cliArgs2HttpArgs();
		parent::main();
	}

}


$receiver = new Receiver('main', '../config_cli.xml', '../framework', '../universibo', new AppKernel('dev', true));
$receiver->main();


list($usec, $sec) = explode(" ", microtime());
$page_time_end = ((float)$usec + (float)$sec);

printf("%01.5f", $page_time_end - $page_time_start);
