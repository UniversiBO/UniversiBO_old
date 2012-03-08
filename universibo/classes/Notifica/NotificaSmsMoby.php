<?php 

require_once ('Notifica/NotificaItem'.PHP_EXTENSION);
require_once('mobytSms'.PHP_EXTENSION);

/**
 *
 * NotificaSmsMoby class
 *
 * Rappresenta una singola Notifica di tipo Sms.
 *
 * @package Notifica
 * @version 2.0.0
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */


class NotificaSmsMoby extends NotificaItem 
{
	
	function NotificaSmsMoby ($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario) 
	{
		//$id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario
		parent::NotificaItem($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario);
	}
	
	
	
	/**
	* Overwrite the send (abstract) function of the base class
	* 
	* @return boolean true "success" or false "failed"
	*/
	function send($fc) {
		// impediamo l'invio di sms più vecchi di 7 gg
		$time = time();
		$setteGiorni = 7 * 24 * 3600;
		if(($time -$setteGiorni) > $this->getTimestamp()) return true;
		
		//per usare l'SMTPkeepAlive usa il singleton
		$sms = $fc->getSmsMoby();
		
		$result = $sms->sendSms($this->getIndirizzo(), $this->getMessaggio());
		
		if (substr($result, 0, 2) != 'OK')
			return false;
	}
	
	
	function factoryNotifica($id_notifica)
	{
		$not = NotificaItem::selectNotifica($id_notifica);
		$ret =  new NotificaSmsMoby($not->getIdNotifica(), $not->getTitolo(), $not->getMessaggio(), $not->getTimestamp(), $not->isUrgente(), $not->isEliminata(), $not->getDestinatario());
		return $ret;
		//$notif=NotificaMail::selectNotifica($id_notifica);
		//$notifMail=new NotificaMail($notif,$fc);
		//return $notifMail;
	}
}
