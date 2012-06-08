<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * Manages Users Logout actions
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class Logout extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();

        $sf = false;

        if (array_key_exists('f2_submit', $_POST)
                || (array_key_exists('symfony', $_GET)
                        && ($sf = $_GET['symfony']))) {
            $this->setSessionIdUtente(0);

            $fc->setStyle($fc->getAppSetting('defaultStyle'));

            $forum = $this->getContainer()->get('universibo_legacy.forum.api');
            $forum->logout();
        }

        session_destroy();
        session_start();

        if ($sf) {
            $fc->redirectUri($sf);
        } else {
            $fc->redirectCommand();
        }
    }
}
