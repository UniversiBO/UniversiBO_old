<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\Constants;
use UniversiBO\Bundle\LegacyBundle\App\ForumApi;
use UniversiBO\Bundle\LegacyBundle\Entity\User;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * @author Davide Bellettini
 * @license GPL v2 or later, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptBatchRename extends UniversiboCommand
{
	function execute()
	{
		$fc = $this->getFrontController();
		$db = $fc->getDbConnection('main');
		
		
		$repo = new DBUserRepository($db);
	}
}
