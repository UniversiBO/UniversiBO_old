<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);
require_once ('mobytSms'.PHP_EXTENSION);


/**
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ScriptTestSms extends UniversiboCommand 
{
	
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $fc->getTemplateEngine();
		$m = $fc->getSmsMoby();
		$m->setQualityHigh();
		$m->setAuthPlain();
		var_dump($m); 	

		$op = 'GETCREDIT';
		$fields = array(
			'operation' => $op,
			'id'            => $m->login,
			'password'      => $m->pwd,
//			'ticket'        => ''
		);

		echo '**** PLAIN AUTH ****'. "\n";	
		$ch = curl_init('http://smsweb.mobyt.it/sms-gw/sendsmart');
               curl_setopt($ch, CURLOPT_VERBOSE, 1); 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'phpMobytSms/'.MOBYT_PHPSMS_VERSION.' (curl)');
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		var_dump($ch);
                 $page = curl_exec($ch);
		var_dump($page);        
        
		$op = 'GETCREDIT';
		$fields = array(
			'operation' => $op,
			'id'            => $m->login,
//			'password'      => $m->pwd,
			'ticket'        => md5($m->login.$op.$m->pwd) 
		);

		echo '**** MD5 AUTH ****'. "\n";	
		$ch = curl_init('http://smsweb.mobyt.it/sms-gw/sendsmart');
               curl_setopt($ch, CURLOPT_VERBOSE, 1); 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'phpMobytSms/'.MOBYT_PHPSMS_VERSION.' (curl)');
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		var_dump($ch);
                 $page = curl_exec($ch);
		var_dump($page);
         
		echo '**** START REQUEST ****'. "\n";	
		echo 'credito ';  
		var_dump($m->getCredit());
                echo "\n".'sms residuii ';
		var_dump($m->getAvailableSms());
		echo '**** STOP REQUEST ****'. "\n";	
	//	echo $m->sendSms('+393334708204','prova');
	}	
}

?>
