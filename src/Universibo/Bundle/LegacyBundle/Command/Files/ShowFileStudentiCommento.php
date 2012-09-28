<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowFileStudentiCommenti è un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 *
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowFileStudentiCommento extends PluginCommand
{
    //todo: rivedere la questione diritti per uno studente...

    /**
     * Esegue il plugin
     *
     * @param l'id del file di cui si voglio i commenti
     */
    public function execute($param = array())
    {
        $bc = $this->getBaseCommand();
        $user = $bc->get('security.context')->getToken()->getUser();

        $fc = $bc->getFrontController();
        $template = $fc->getTemplateEngine();
        $krono = $fc->getKrono();

        $commento = CommentoItem::selectCommentoItem($param['id_commento']);
        $commento_tpl = array();

        $id_utente = $commento->getIdUtente();
        $commento_tpl['commento'] = $commento->getCommento();
        $commento_tpl['voto'] = $commento->getVoto();
        $commento_tpl['userLink'] = $this->get('router')->generate('universibo_legacy_user', array('id_utente' => $id_utente));
        $commento_tpl['userNick'] = $commento->getUsername();

        $template->assign('showFileStudentiCommenti_commento', $commento_tpl);
    }

}
