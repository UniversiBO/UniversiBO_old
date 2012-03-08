<?php
/**
 *
 * @package universibo
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2005
 */

class AntiVirusFactory
{

	function getAntiVirus($fc)
	{
		
		if ( $fc->getAppSetting('antiVirusEnable') == 'true' )
		{
			if ($fc->getAppSetting('antiVirusType') == 'clamav' )
			{
				require_once('AntiVirus/Clamav'.PHP_EXTENSION);
				
				$cmd = $fc->getAppSetting('antiVirusClamavCmd');
				$opts = $fc->getAppSetting('antiVirusClamavOpts');
				
				return new Clamav($cmd, $opts);
				
			}
			else 
				return false;
			
		}
		else 	
			return false;
	}	
}
