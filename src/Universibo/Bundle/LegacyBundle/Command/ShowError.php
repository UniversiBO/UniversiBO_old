<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowError: mostra una pagina con la descrizione dell'errore per gli ErrorDefault
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @todo implementare il log degli errori tramite il logger.
 */
class ShowError extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        //if (!array_key_exists('error_param', $_SESSION))
        //	Error::throwError(_ERROR_CRITICAL,array('msg'=>'Chiamata illegale del comando di errore','log'=>true,'file'=>__FILE__,'line'=>__LINE__));

        (session_id() !== '' && array_key_exists('error_param', $_SESSION)) ? $param = $_SESSION['error_param']
                : $param = 'Errore di sistema';

        $template->assign('error_default', $param['msg']);

        return 'default';
    }
}
