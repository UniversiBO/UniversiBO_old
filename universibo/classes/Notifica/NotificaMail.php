<?php 

require_once ('Notifica/NotificaItem'.PHP_EXTENSION);

/**
 *
 * NotificaMail class
 *
 * Rappresenta una singola Notifica di tipo Mail.
 *
 * @package universibo
 * @subpackage Notifica
 * @version 2.0.0
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class NotificaMail extends NotificaItem 
{

	
	function NotificaMail ($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario) 
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
		
		//per usare l'SMTPkeepAlive usa il singleton
		$mail =& $fc->getMail(MAIL_KEEPALIVE_ALIVE);
		
		$mail->clearAddresses();
		$mail->AddAddress($this->getIndirizzo());

        $mail->Subject = str_replace( "\n"," - ",  '[UniversiBO] '.$this->getTitolo());
		$mail->Body = $this->getMessaggio();
		
		/**
		 * @todo fare la mail urgente se $this->isUrgente()
		 */
		if (!$mail->send())
		{
			$this->error = $mail->ErrorInfo;
			return false;
		}
	}
	
	
	function &factoryNotifica($id_notifica)
	{
		$not = NotificaItem::selectNotifica($id_notifica);
		$ret = new NotificaMail($not->getIdNotifica(), $not->getTitolo(), $not->getMessaggio(), $not->getTimestamp(), $not->isUrgente(), $not->isEliminata(), $not->getDestinatario());
	 	return $ret;
		//$notif=NotificaMail::selectNotifica($id_notifica);
		//$notifMail=new NotificaMail($notif,$fc);
		//return $notifMail;
	}
}
?>