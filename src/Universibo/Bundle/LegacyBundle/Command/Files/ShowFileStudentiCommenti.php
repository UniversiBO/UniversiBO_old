<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;

use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
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

class ShowFileStudentiCommenti extends PluginCommand
{
    //todo: rivedere la questione diritti per uno studente...

    /**
     * Esegue il plugin
     *
     * @param l'id del file di cui si voglio i commenti
     */
    public function execute($param = array())
    {
        //$flag_chkDiritti	=  $param['chk_diritti'];
//		var_dump($param['id_notizie']);
//		die();

        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();

        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();

        $file = FileItemStudenti::selectFileItem($param['id_file']);
        $id_canali = $file->getIdCanali();
        $id_canale = $id_canali[0];
        $user_ruoli = $user->getRuoli();

        $personalizza_not_admin = false;

        if (array_key_exists($id_canale, $user_ruoli) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $personalizza = true;

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $personalizza_not_admin = true;
                $referente      = $ruolo->isReferente();
                $moderatore     = $ruolo->isModeratore();
                $ultimo_accesso = $ruolo->getUltimoAccesso();
            }

        } else {
            $personalizza   = false;
            $referente      = false;
            $moderatore     = false;
            $ultimo_accesso = $user->getUltimoLogin();
        }
/*
        $canale_news = $this->getNumNewsCanale($id_canale);

        $template->assign('showNews_desc', 'Mostra le ultime '.$num_news.' notizie del canale '.$id_canale.' - '.$titolo_canale);
*/

        $elenco_commenti = CommentoItem::selectCommentiItem($param['id_file']);
        $num_commenti = CommentoItem::quantiCommenti($param['id_file']);
        $elenco_commenti_tpl = array();
//		var_dump($elenco_commenti);
//	    die();


        if ($elenco_commenti ==! false) {
            for ($i = 0; $i < $num_commenti; $i++) {

                $id_utente = $elenco_commenti[$i]->getIdUtente();
                $commenti['commento'] = $elenco_commenti[$i]->getCommento();
                $commenti['voto'] = $elenco_commenti[$i]->getVoto();
                $commenti['userLink'] = ('/?do=ShowUser&id_utente='.$id_utente);
                $commenti['userNick'] = $elenco_commenti[$i]->getUsername();


//				var_dump($elenco_commenti_tpl);
//				die();

                $this_diritti = ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($moderatore) || ($referente) || ($id_utente==$user->getIdUser()));

                if ($this_diritti) {
                        $id_commento = $elenco_commenti[$i]->getIdCommento();
                        $commenti['dirittiCommento'] = 'true';
                        $commenti['editCommentoLink'] = '/?do=FileStudentiCommentEdit&id_commento='.$id_commento.'&id_canale='.$id_canale;
                        $commenti['deleteCommentoLink'] = '/?do=FileStudentiCommentDelete&id_commento='.$id_commento.'&id_canale='.$id_canale;
                    } else {$commenti['dirittiCommento']='false';}
                $elenco_commenti_tpl[$i] = $commenti;
            }


            $template->assign('showFileStudentiCommenti_langCommentiAvailableFlag', 'true');
            $template->assign('showFileStudentiCommenti_commentiList', $elenco_commenti_tpl);
        } else {$template->assign('showFileStudentiCommenti_langCommentiAvailableFlag', 'false');
         }
    }
}
