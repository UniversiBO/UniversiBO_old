<?php
namespace Universibo\Bundle\LegacyBundle\Command\News;

use Universibo\Bundle\CoreBundle\Entity\User;

use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowNews è un'implementazione di PluginCommand.
 *
 * Mostra la notizia $id_notizia.
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel paramentro di ingresso del deve essere specificato il numero di notizie da visualizzare.
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowNews extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  un array di id notizie da visualizzare
     *	  es: array('id_notizia'=>5)
     */
    public function execute($param=array())
    {
        $elenco_id_news		=  $param['id_notizie'];
        $flag_chkDiritti	=  $param['chk_diritti'];
        $router = $this->get('router');
//		var_dump($param['id_notizie']);
//		die();

        $bc        = $this->getBaseCommand();
        $canale    = $bc->getRequestCanale();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        if ($flag_chkDiritti) {
            $id_canale = $canale->getIdCanale();
            $titolo_canale =  $canale->getTitolo();
            $ultima_modifica_canale =  $canale->getUltimaModifica();
            $canale    = $bc->getRequestCanale();
        }

        $personalizza_not_admin = false;

        $template->assign('showNews_addNewsFlag', 'false');
        if ($flag_chkDiritti && (array_key_exists($id_canale, $user_ruoli) || $this->get('security.context')->isGranted('ROLE_ADMIN'))) {
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
            $ultimo_accesso = $user instanceof User ? $user->getLastLogin()->getTimestamp() : 0;
        }

        $canale_news = count($elenco_id_news);

        if ($canale_news == 0) {
            $template->assign('showNews_langNewsAvailable', 'Non ci sono notizie da visualizzare');
            $template->assign('showNews_langNewsAvailableFlag', 'false');
        } else {
            $template->assign('showNews_langNewsAvailable', 'Ci sono '.$canale_news.' notizie');
            $template->assign('showNews_langNewsAvailableFlag', 'true');
        }

        $elenco_news = NewsItem::selectNewsItems($elenco_id_news);

        $elenco_news_tpl = array();

        if ($elenco_news ==! false) {

            $ret_news = count($elenco_news);

            for ($i = 0; $i < $ret_news; $i++) {
                $news = $elenco_news[$i];
                //var_dump($news);
                $this_moderatore = ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($moderatore && $news->getIdUtente()==$user->getId()));

                $elenco_news_tpl[$i]['titolo']       = $news->getTitolo();
                $elenco_news_tpl[$i]['notizia']      = $news->getNotizia();
                $elenco_news_tpl[$i]['data']         = $krono->k_date('%j/%m/%Y', $news->getDataIns());
                //echo $personalizza,"-" ,$ultimo_accesso,"-", $news->getUltimaModifica()," -- ";
                $elenco_news_tpl[$i]['nuova']        = ($flag_chkDiritti && $personalizza_not_admin && $ultimo_accesso < $news->getUltimaModifica()) ? 'true' : 'false';
                $elenco_news_tpl[$i]['autore']       = $news->getUsername();
                $elenco_news_tpl[$i]['autore_link']  = 'ShowUser&id_utente='.$news->getIdUtente();
                $elenco_news_tpl[$i]['id_autore']    = $news->getIdUtente();
                $elenco_news_tpl[$i]['id_notizia']   = $news->getIdNotizia();

                $elenco_news_tpl[$i]['scadenza']     = '';
                if ( ($news->getDataScadenza()!=NULL) && ( $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore ) && $flag_chkDiritti) {
                    $elenco_news_tpl[$i]['scadenza'] = 'Scade il '.$krono->k_date('%j/%m/%Y - %H:%i', $news->getDataScadenza() );
                }

                $elenco_news_tpl[$i]['permalink']     = $router->generate('universibo_legacy_permalink', array('id_notizia' => $news->getIdNotizia()));
                $elenco_news_tpl[$i]['modifica']     = '';
                $elenco_news_tpl[$i]['modifica_link']= '';
                $elenco_news_tpl[$i]['elimina']      = '';
                $elenco_news_tpl[$i]['elimina_link'] = '';
                if ( ($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore)  && $flag_chkDiritti) {
                    $elenco_news_tpl[$i]['modifica']     = 'Modifica';
                    $elenco_news_tpl[$i]['modifica_link']= $router->generate('universibo_legacy_news_edit', array('id_news' => $news->getIdNotizia()));
                    $elenco_news_tpl[$i]['elimina']      = 'Elimina';
                    $elenco_news_tpl[$i]['elimina_link'] = $router->generate('universibo_legacy_news_delete', array('id_news' => $news->getIdNotizia(), 'id_canale' => $id_canale));
                }

            }

        }

        $template->assign('showNews_newsList', $elenco_news_tpl);
    }
}
