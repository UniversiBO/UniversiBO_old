<?php
namespace Universibo\Bundle\LegacyBundle\Command\Help;

use Universibo\Bundle\LegacyBundle\Framework\Error;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowTopic ? un'implementazione di PluginCommand.
 *
 * Dato un riferimento mostra gli argomenti di help inerenti
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato l'id_help da visualizzare.
 *
 * @package universibo
 * @subpackage Help
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowTopic extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  - 'reference' il riferimento degli argomenti da visualizzare
     *	  es: array('reference'=>'pippo')
     */
    public function execute($param = array())
    {
        $reference  =  $param['reference'];

        $bc        = $this->getBaseCommand();
        $frontcontroller = $bc->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $topicRepo = $this->getContainer()->get('universibo_legacy.repository.help.topic');

        $topic = $topicRepo->find($reference);

        if (is_null($topic)) {
            Error::throwError(_ERROR_DEFAULT,array('msg'=>'E\'stato richiesto un argomento dell\'help non presente','file'=>__FILE__,'line'=>__LINE__));
        }

        $itemRepo = $this->getContainer()->get('universibo_legacy.repository.help.item');

        $argomenti = array();
        foreach ($itemRepo->findByReference($reference) as $item) {
            $argomenti[] = $item->getId();
        }

        if (count($argomenti) > 0) {
            $lang_argomenti = $this->executePlugin('ShowHelpId', $argomenti);
            $topic = array('titolo'=>$topic->getTitle() ,'reference'=>$reference, 'argomenti'=>$lang_argomenti);
        }

        $template->assign('showTopic_topic', $topic);

        return $topic;
    }
}
