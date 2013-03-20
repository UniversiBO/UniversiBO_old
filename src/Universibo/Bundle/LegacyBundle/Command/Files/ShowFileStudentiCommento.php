<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;

use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowFileStudentiCommenti è un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 *
 *
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

        $fc = $bc->getFrontController();
        $template = $fc->getTemplateEngine();

        $commentRepo = $this->get('universibo_legacy.repository.commenti.commento_item');
        $userRepo = $this->get('universibo_core.repository.user');
        $comment = $commentRepo->find($param['id_commento']);

        $comment_tpl = array();

        $id_utente = $comment->getIdUtente();
        $comment_tpl['commento'] = $comment->getCommento();
        $comment_tpl['voto'] = $comment->getVoto();
        $comment_tpl['userLink'] = $this->get('router')->generate('universibo_legacy_user', array('id_utente' => $id_utente));
        $comment_tpl['userNick'] = $userRepo->getUsernameFromId($id_utente);

        $template->assign('showFileStudentiCommenti_commento', $comment_tpl);
    }

}
