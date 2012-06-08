<?php
namespace Universibo\Bundle\LegacyBundle\Command\Help;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowHelpId ? un'implementazione di PluginCommand.
 *
 * Mostra la spiegazione dell'argomento n? $id_help
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato l'id_help da visualizzare.
 * E' associato al template help_id.tpl
 *
 * @package universibo
 * @subpackage Help
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowHelpId extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  - 'id_help' l'id dell'argomento o argomenti da visualizzare
     *	  es: array("5","6")
     *	se viene passato 0  come parametro mostra tutti gli argomenti
     *  NB 0 non pu? essere l'id di una notizia
     */
    public function execute($param = array())
    {
        $bc			     = $this->getBaseCommand();
        $frontcontroller = $bc->getFrontController();
        $template		 = $frontcontroller->getTemplateEngine();

        $repo = $this->getContainer()->get('universibo_legacy.repository.help.item');
        $items = in_array(0, $param) ? $repo->findAll() : $repo->findMany($param);

        foreach ($items as $item) {
            $argomenti[] = array('id' => 'id'.$item->getId(), 'titolo' => $item->getTitle(), 'contenuto' => $item->getContent());
        }

        $template->assign('showHelpId_langArgomento', $argomenti);

        return $argomenti;
    }
}
