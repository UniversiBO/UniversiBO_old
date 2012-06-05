<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaMail;

/**
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptTestMail extends UniversiboCommand
{

    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();

        $mail = new NotificaMail(1,'prova','prova prova',time(),false, false,'mail://evaimitico@gmail.com');

        var_dump($mail->send($fc));
        echo $mail->getError();
    }
}
