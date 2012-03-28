<?php

/**
 * Examples for LogHandler Class
 *
 * @package universibo_tests
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL {@link http://www.opensource.org/licenses/gpl-license.php}
 */

		$log_error_definition = array(0 => 'time',
									  1 => 'remote_ip',
									  2 => 'request',
									  3 => 'referer_page',
									  4 => 'file',
									  5 => 'line',
									  6 => 'description' );
		
		$errorLog = new UniversiBO\Legacy\Framework\LogHandler('error',$this->paths['logs'],$log_error_definition); 
		
		$a = time();
		$b = $_SERVER['REMOTE_ADDR'];
		$c = (array_key_exists('HTTP_REFERER',$_SERVER)) ? $_SERVER['HTTP_REFERER'] : '';
		$protocol = (array_key_exists('HTTPS',$_SERVER) && $_SERVER['HTTPS']=='on')? 'https':'http';
		$d = $protocol.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$e = __FILE__;
		$f = __LINE__;
		$g = 'description';
		$log_array = array('time'         => $a,
						   'remote_ip'    => $b,
						   'request'      => $c,
						   'referer_page' => $d,
						   'file'         => $e,
						   'line'         => $f,
						   'description'  => $g);

		$errorLog->addLogEntry($log_array);

		var_dump($errorLog);

?>