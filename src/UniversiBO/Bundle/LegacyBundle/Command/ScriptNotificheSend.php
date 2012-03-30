<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\App\Notifica\NotificaItem;
use UniversiBO\Bundle\LegacyBundle\Framework\LogHandler;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ScriptNotificheSendis an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptNotificheSend extends UniversiboCommand
{
	function execute()
	{
		$fc = $this->getFrontController();
		$db = $fc->getDbConnection('main');
		$user = $this->getSessionUser();
		$filePath = $fc->getAppSetting('filesPath');
		
		$log_notifica_definition = array(0 => 'time', 1 => 'id_notifica', 2 => 'titolo', 3 => 'destinatario', 4 => 'risultato' );
		
		$notifLog = new LogHandler('notifica',$fc->getAppSetting('logs'),$log_notifica_definition); 
		

		//acquisire lock
		$full_file_name = $fc->getAppSetting('notificheLock');
		$fp = fopen ($full_file_name, "a");
		if ($fp === false) return false; 
		
		flock($fp,LOCK_EX);
		
		$notifiche = NotificaItem::selectNotificheSend();
		
		$num_notifiche = count($notifiche);
		//var_dump($notifiche);
		
		for ($i = 0; $i < $num_notifiche; $i++)
		{
			if ($notifiche[$i]->send($fc) === false)
			{
				$log_array = array( 'time'         => time(),
									'id_notifica'  => $notifiche[$i]->getIdNotifica(),
									'titolo'       => $notifiche[$i]->getTitolo(),
									'destinatario' => $notifiche[$i]->getDestinatario(),
						   			'risultato'    => 'failed: '.$notifiche[$i]->getError());
				$notifLog->addLogEntry($log_array);
				
				//$notifiche[$i]->setFallita(true);
			}
			else
			{
				$log_array = array( 'time'         => time(),
									'id_notifica'  => $notifiche[$i]->getIdNotifica(),
									'titolo'       => $notifiche[$i]->getTitolo(),
									'destinatario' => $notifiche[$i]->getDestinatario(),
						   			'risultato'    => 'success');
				$notifLog->addLogEntry($log_array);
				
				//$notifiche[$i]->setInviata(true);
			}
			
			$notifiche[$i]->setEliminata(true);
			$notifiche[$i]->updateNotificaItem();
			
		}
		
		
        
        //$db->autoCommit(true);
        //ignore_user_abort(0);
		// terminare transazione 

		flock($fp,LOCK_UN);
		fclose($fp);
		// rialsciare lock
		
	}
}
