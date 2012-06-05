<?php
namespace Universibo\Bundle\LegacyBundle\Command\News;

use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowMyNews � un'implementazione di PluginCommand.
 *
 * Mostra le notizie $id_notizia.
 * Nel paramentro di ingresso del deve essere specificato il numero di notizie da visualizzare.
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowMyNews extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  un array di id notizie da visualizzare
     *	  es: array('id_notizia'=>5)
     */
    public function execute($param = array())
    {
        $elenco_id_news		=  $param['id_notizie'];
        $bc        = $this->getBaseCommand();
        $user      = $bc->getSessionUser();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();
        $user_ruoli = $user->getRuoli();
        $personalizza_not_admin = false;

//		$template->assign('showMyNews_addNewsFlag', 'false');
        $personalizza   = false;
        $referente      = false;
        $moderatore     = false;
        $ultimo_accesso = $user->getUltimoLogin();

        //parte ricopiata da studiare...
        $canale_news = count($elenco_id_news);
        if ( $canale_news == 0 ) {
            $template->assign('showMyNews_langNewsAvailable', 'Non ci sono notizie da visualizzare');
            $template->assign('showMyNews_langNewsAvailableFlag', 'false');
        } else {
            $template->assign('showMyNews_langNewsAvailable', 'Ci sono '.$canale_news.' notizie');
            $template->assign('showMyNews_langNewsAvailableFlag', 'true');
        }

        //var_dump($elenco_id_news);
        $elenco_news = NewsItem::selectNewsItems($elenco_id_news);

        $elenco_news_tpl = array();

        if ($elenco_news ==! false ) {

            $ret_news = count($elenco_news);

            for ($i = 0; $i < $ret_news; $i++) {
                $news = $elenco_news[$i];
                //var_dump($news);
                $this_moderatore = ($user->isAdmin() || ($moderatore && $news->getIdUtente()==$user->getIdUser()));

                $elenco_news_tpl[$i]['titolo']       = $news->getTitolo();
                $elenco_news_tpl[$i]['notizia']      = $news->getNotizia();
                $elenco_news_tpl[$i]['data']         = $krono->k_date('%j/%m/%Y', $news->getDataIns());
                //echo $personalizza,"-" ,$ultimo_accesso,"-", $news->getUltimaModifica()," -- ";
                $elenco_news_tpl[$i]['autore']       = $news->getUsername();
                $elenco_news_tpl[$i]['autore_link']  = 'ShowUser&id_utente='.$news->getIdUtente();
                $elenco_news_tpl[$i]['id_autore']    = $news->getIdUtente();

                $elenco_news_tpl[$i]['scadenza']     = '';
                //if ( ($news->getDataScadenza()!=NULL) && ( $user->isAdmin() || $referente || $this_moderatore ) && $flag_chkDiritti)
                //{
                //	$elenco_news_tpl[$i]['scadenza'] = 'Scade il '.$krono->k_date('%j/%m/%Y - %H:%i', $news->getDataScadenza() );
                //}

                //roba mia
                $canali = $news->getIdCanali();
                $num_canali =  count($canali);
                $elenco_canali_tpl = array();
                for ($j = 0; $j < $num_canali; $j++) {
                    $canale = Canale::retrieveCanale($canali[$j]);
                    if ($canale->isGroupAllowed($user->getGroups())) {
                        $canale_tpl = array();
                        $canale_tpl['titolo'] = $canale->getNome();
                        $canale_tpl['link'] = $canale->showMe();
                        $elenco_news_tpl[$i]['canali'][] = $canale_tpl;
                    }
                }
                $elenco_news_tpl[$i]['nuova']	   	 = ($news->getUltimaModifica() > $ultimo_accesso) ? 'true' : 'false';
                $elenco_news_tpl[$i]['modifica']     = '';
                $elenco_news_tpl[$i]['modifica_link']= '';
                $elenco_news_tpl[$i]['elimina']      = '';
                $elenco_news_tpl[$i]['elimina_link'] = '';

            }

        }

        $template->assign('showMyNews_newsList', $elenco_news_tpl);
    }
}
