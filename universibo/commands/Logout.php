<?php

use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * Manages Users Logout actions
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class Logout extends UniversiboCommand {
	function execute()
	{
		$fc = $this->getFrontController();
		
		if ( array_key_exists('f2_submit',$_POST) || (array_key_exists('symfony',$_GET) && $_GET['symfony']))
		{
			$this->setSessionIdUtente(0);
			
			$fc->setStyle($fc->getAppSetting('defaultStyle'));
			
			$forum = new ForumApi();
			$forum->logout();
		}
		
		$fc->redirectCommand();
				
		return;
	}
}
