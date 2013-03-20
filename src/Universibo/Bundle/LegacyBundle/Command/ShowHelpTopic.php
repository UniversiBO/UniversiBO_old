<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowHelpTopic is an extension of UniversiboCommand class.
 *
 * It shows Contribute page
 *
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

        $references = array();
        $topics = array();

        $ref = $this->getRequest()->get('ref', '');
        if (!preg_match('/^[[:alnum:]]{1,32}$/', $ref)) {
            $topicRepo = $this->getContainer()->get('universibo_legacy.repository.help.topic');

            foreach ($topicRepo->findAll() as $topic) {
                $references[] = $topic->getReference();
                $topics[] = $this->executePlugin('ShowTopic', array('reference' => $topic->getReference()));
            }

            $template->assign('showHelpTopic_index', 'true');
        } else {
            $topics[] = $this->executePlugin('ShowTopic', array('reference' => $ref));

            $template->assign('showHelpTopic_index', 'false');
        }

        //$template->assign('showHelpTopic_langReferences', $references);
        $template->assign('showHelpTopic_topics', $topics);

        return 'default';
    }
}
