<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowHelpTopic is an extension of UniversiboCommand class.
 *
 * It shows Contribute page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowHelpTopic extends UniversiboCommand
{
    function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        //		$bc = $this->getBaseCommand();
        //		$sessionUser = $bc->getSessionUser();
        $sessionUser = $this->getSessionUser();

        $template->assign('showHelpTopic_langAltTitle', 'Help');

        /**
         * @todo queste info andrebbero in una classetta statica per l'help
         */
        $ref_pattern = '^([:alnum:]{1,32})$';

        $references = array();

        $topics = array();

        if (!array_key_exists('ref', $_GET)
                || ereg($ref_pattern, $_GET['ref'])) {
            /**
             * @todo l'acesso al DB a questo livello non mi piace... ci sarebbe da inserire un po' di roba in una classetta statica
             */
            $db = $frontcontroller->getDbConnection('main');
            $query = 'SELECT riferimento FROM help_topic';
            $res = $db->query($query);
            if (DB::isError($res))
                Error::throwError(_ERROR_CRITICAL,
                        array('id_utente' => $this->sessionUser->getIdUser(),
                                'msg' => DB::errorMessage($res),
                                'file' => __FILE__, 'line' => __LINE__));

            while ($res->fetchInto($row)) {
                $references[] = $row[0];
                $topics[] = $this
                        ->executePlugin('ShowTopic',
                                array('reference' => $row[0]));
            }
            $res->free();

            $template->assign('showHelpTopic_index', 'true');
        } else {
            $topics[] = $this
                    ->executePlugin('ShowTopic',
                            array('reference' => $_GET['ref']));

            $template->assign('showHelpTopic_index', 'false');
        }

        //$template->assign('showHelpTopic_langReferences', $references);
        $template->assign('showHelpTopic_topics', $topics);

        return 'default';
    }
}
