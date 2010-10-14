<?php

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

class Receiver{

	var $frameworkPath = '../framework';
	var $applicationPath = '../universibo';

	var $configFile = '../config_cli.xml';
	var $receiverIdentifier = 'main';
	var $pathDelimiter = ':';
	
	/**
	 * Costruttore del Receiver
	 *
	 * @param string $identifier indentifier of receiver
	 * @param string $config_file configuration file for this receiver (applicatio)
	 * @param string $framework_path percorso in cui si trovano i file del framework
	 * @param string $application_path percorso in cui si trovano i file dell'applicazione
	 */
	function Receiver($identifier, $config_file, $framework_path, $application_path)
	{
		$this->frameworkPath = $framework_path;
		$this->applicationPath = $application_path;

		$this->configFile = $config_file;
		$this->receiverIdentifier = $identifier;
	}
	
	
	
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
 	* Set PHP language settings (path, gpc, error_reporting)
	*/
	function _setPhpEnvirorment()
	{
		
		//error reporting activation (enabled on testing system)
		error_reporting(E_ALL); 

		//output buffering
		//ob_start('ob_gzhandler');

		//session initialization
		session_start();
		if (!array_key_exists('SID',$_SESSION) )
		{
			$_SESSION['SID'] = SID;
		}
				
		if (defined('PATH_SEPARATOR')) 
		{
		    $pathDelimiter = PATH_SEPARATOR;
		}
		else 
		{
			$pathDelimiter = ( substr(php_uname(), 0, 7) == "Windows") ? ';' : ':' ;
		}
		
		ini_set('include_path', $this->frameworkPath.$pathDelimiter.$this->applicationPath.'/classes'.$pathDelimiter.ini_get('include_path'));
		
		if (get_magic_quotes_gpc()) {
		   function stripslashes_deep($value)
		   {
		       return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		   }
		
		   $_POST = array_map('stripslashes_deep', $_POST);
		   $_GET = array_map('stripslashes_deep', $_GET);
		   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
		}

		if ( get_magic_quotes_runtime() == 1 )
		{
			 set_magic_quotes_runtime(0);
		} 
		
		//php files extension, can ben modified to externally hide php files
		define ('PHP_EXTENSION', '.php');
		
		
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
	function main()
	{
		$this->_cliArgs2HttpArgs();
		$this->_setPhpEnvirorment();
				
		include_once('FrontController'.PHP_EXTENSION);
		$fc= new FrontController($this);
		
		$fc->setConfig( $this->configFile );
		
		$fc->executeCommand();
		
	}

}


$receiver = new Receiver('main', '../config_cli.xml', '../framework', '../universibo');
$receiver->main();


list($usec, $sec) = explode(" ", microtime());
$page_time_end = ((float)$usec + (float)$sec);

printf("%01.5f", $page_time_end - $page_time_start);

?>


