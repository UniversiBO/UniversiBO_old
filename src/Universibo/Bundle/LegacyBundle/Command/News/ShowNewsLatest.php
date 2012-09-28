<?php
namespace Universibo\Bundle\LegacyBundle\Command\News;

use Universibo\Bundle\WebsiteBundle\Entity\User;

use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowNewsLatest è un'implementazione di PluginCommand.
 *
 * Mostra le ultime $num notizie del canale.
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del deve essere specificato il numero di notizie da visualizzare.
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowNewsLatest extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  - 'num' il numero di notizie da visualizzare
     *	  es: array('num'=>5)
     */
    public function execute($param=array())
    {
        $num_news  =  $param['num'];

        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $canale    = $bc->getRequestCanale();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();
        $router    = $this->get('router');

        $id_canale = $canale->getIdCanale();
        $titolo_canale =  $canale->getTitolo();
        $ultima_modifica_canale =  $canale->getUltimaModifica();

        if ($user instanceof User) {
            $roleRepo = $this->get('universibo_legacy.repository.ruolo');
            $user_ruoli = $roleRepo->findByIdUtente($user->getId());
        } else {
            $user_ruoli = array();
        }

        $personalizza_not_admin = false;

        $template->assign('showNewsLatest_addNewsFlag', 'false');
        if (array_key_exists($id_canale, $user_ruoli) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $personalizza = true;

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $personalizza_not_admin = true;
                $referente      = $ruolo->isReferente();
                $moderatore     = $ruolo->isModeratore();
                $ultimo_accesso = $ruolo->getUltimoAccesso();
            }

            if ( $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore ) {
                $template->assign('showNewsLatest_addNewsFlag', 'true');
                $template->assign('showNewsLatest_addNews', 'Scrivi nuova notizia');
                $template->assign('showNewsLatest_addNewsUri', $router->generate('universibo_legacy_news_add', array('id_canale' => $id_canale)));
            }
        } else {
            $personalizza   = false;
            $referente      = false;
            $moderatore     = false;
            $ultimo_accesso = $user instanceof User ? $user->getLastLogin() : null;
        }
        //var_dump($moderatore);
        $canale_news = $this->getNumNewsCanale($id_canale);

        $template->assign('showNewsLatest_desc', 'Mostra le ultime '.$num_news.' notizie del canale '.$id_canale.' - '.$titolo_canale);

        if ($canale_news == 0) {
            $template->assign('showNewsLatest_langNewsAvailable', 'Non ci sono notizie in questo canale');
            $template->assign('showNewsLatest_langNewsAvailableFlag', 'false');
            $template->assign('showNewsLatest_langNewsShowOthers', '');
        } else {
            $template->assign('showNewsLatest_langNewsAvailable', 'Ci sono '.$canale_news.' notizie in questo canale');
            $template->assign('showNewsLatest_langNewsAvailableFlag', 'true');
            if ($canale_news > $num_news) {
                $template->assign('showNewsLatest_langNewsShowOthers', 'Mostra tutte le news');
                $template->assign('showNewsLatest_langNewsShowOthersUri', $router->generate('universibo_legacy_news_show_canale', array('id_canale' => $id_canale, 'inizio' => 0, 'qta' => 10)));
            } else {
                $template->assign('showNewsLatest_langNewsShowOthers', '');
            }
        }

        $elenco_news = $this->getLatestNewsCanale($num_news, $id_canale);

        $elenco_news_tpl = array();

        if ($elenco_news ==! false) {

            $ret_news = count($elenco_news);

            for ($i = 0; $i < $ret_news; $i++) {
                $news = $elenco_news[$i];
                $this_moderatore = ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($moderatore && $news->getIdUtente()==$user->getId()));

                                $elenco_news_tpl[$i]['id_notizia']   = $news->getIdNotizia();
                $elenco_news_tpl[$i]['titolo']       = $news->getTitolo();
                $elenco_news_tpl[$i]['notizia']      = $news->getNotizia();
                $elenco_news_tpl[$i]['data']         = $krono->k_date('%j/%m/%Y - %H:%i', $news->getDataIns());
                //echo $personalizza,"-" ,$ultimo_accesso,"-", $news->getUltimaModifica()," -- ";
                $elenco_news_tpl[$i]['nuova']        = ($personalizza_not_admin==true && $ultimo_accesso < $news->getUltimaModifica()) ? 'true' : 'false';
                $elenco_news_tpl[$i]['autore']       = $news->getUsername();
                $elenco_news_tpl[$i]['autore_link']  = $router->generate('universibo_legacy_user', array('id_utente' => $news->getIdUtente()));
                $elenco_news_tpl[$i]['id_autore']    = $news->getIdUtente();

                $elenco_news_tpl[$i]['scadenza']     = '';
                if ( ($news->getDataScadenza()!=NULL) && ( $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore ) ) {
                    $elenco_news_tpl[$i]['scadenza'] = 'Scade il '.$krono->k_date('%j/%m/%Y', $news->getDataScadenza() );
                }

                $elenco_news_tpl[$i]['permalink']     = $router->generate('universibo_legacy_permalink', array('id_notizia' => $news->getIdNotizia()));
                $elenco_news_tpl[$i]['modifica']     = '';
                $elenco_news_tpl[$i]['modifica_link']= '';
                $elenco_news_tpl[$i]['elimina']      = '';
                $elenco_news_tpl[$i]['elimina_link'] = '';
                if ( $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore ) {
                    $elenco_news_tpl[$i]['modifica']     = 'Modifica';
                    $elenco_news_tpl[$i]['modifica_link']= $router->generate('universibo_legacy_news_delete', array('id_news' => $news->getIdNotizia(), 'id_canale' => $id_canale));
                    $elenco_news_tpl[$i]['elimina']      = 'Elimina';
                    $elenco_news_tpl[$i]['elimina_link'] = $router->generate('universibo_legacy_news_delete', array('id_news' => $news->getIdNotizia(), 'id_canale' => $id_canale));
                }

            }

        }

        $template->assign('showNewsLatest_newsList', $elenco_news_tpl);

    }

    /**
     * Preleva da database le ultime $num notizie non scadute del canale $id_canale
     *
     * @static
     * @param  int   $num       numero notize da prelevare
     * @param  int   $id_canale identificativo su database del canale
     * @return array elenco NewsItem , false se non ci sono notizie
     */
    public function getLatestNewsCanale($num, $id_canale)
    {
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');
        $ids = $newsRepo->findLatestByChannel($id_canale, $num);

        return $newsRepo->findMany($ids);
    }

    /**
     * Preleva da database il numero di notizie non scadute del canale $id_canale
     *
     * @static
     * @param  int $id_canale identificativo su database del canale
     * @return int numero notizie
     */
    public function getNumNewsCanale($id_canale)
    {
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');

        return $newsRepo->countByChannelId($id_canale);
    }

    /**
     * Shortcut to DI container
     *
     * @param  string $id
     * @return mixed
     */
    protected function get($id)
    {
        return $this->getBaseCommand()->get($id);
    }
}
