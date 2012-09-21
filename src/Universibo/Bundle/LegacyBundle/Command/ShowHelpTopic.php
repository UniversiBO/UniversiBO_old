<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

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
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $template->assign('showHelpTopic_langAltTitle', 'Help');

        /**
         * @todo queste info andrebbero in una classetta statica per l'help
         */
        $ref_pattern = '/^[[:alnum:]]{1,32}$/';

        $references = array();

        $topics = array();

        if (!array_key_exists('ref', $_GET) || !preg_match($ref_pattern, $_GET['ref'])) {
            $topicRepo = $this->getContainer()->get('universibo_legacy.repository.help.topic');

            foreach ($topicRepo->findAll() as $topic) {
                $references[] = $topic->getReference();
                $topics[] = $this->executePlugin('ShowTopic', array('reference' => $topic->getReference()));
            }

            $template->assign('showHelpTopic_index', 'true');
        } else {
            $topics[] = $this->executePlugin('ShowTopic', array('reference' => $_GET['ref']));

            $template->assign('showHelpTopic_index', 'false');
        }

        //$template->assign('showHelpTopic_langReferences', $references);
        $template->assign('showHelpTopic_topics', $topics);

        return 'default';
    }
}
