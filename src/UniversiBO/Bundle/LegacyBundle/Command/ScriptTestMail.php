<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\App\Notifica\NotificaMail;

/**
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptTestMail extends UniversiboCommand
{
	
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $fc->getTemplateEngine();
		
		$mail = new NotificaMail(1,'prova','prova prova',time(),false, false,'mail://evaimitico@gmail.com');

		var_dump($mail->send($fc));
		echo $mail->getError();	
	}	
}
