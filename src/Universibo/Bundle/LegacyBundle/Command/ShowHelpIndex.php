<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowHelpIndex is an extension of UniversiboCommand class.
 *
 * It shows help page order by index
 *
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowHelpIndex extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template -> assign('showHelpIndex_langAltTitle', 'Help');

        $this->executePlugin('ShowHelpId', array( 'id_help' => 0 ) );

        return 'default';
    }
}
