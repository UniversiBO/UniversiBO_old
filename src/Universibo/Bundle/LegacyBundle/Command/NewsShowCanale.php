<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;

/**
 * NewsAdd: si occupa dell'inserimento di una news in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class NewsShowCanale extends CanaleCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $user = $this->getSessionUser();
        $canale = $this->getRequestCanale();
        $user_ruoli = $user->getRuoli();
        $id_canale = $canale->getIdCanale();

        if (!array_key_exists('inizio', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['inizio'] ) || !array_key_exists('qta', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['qta'] )) {
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Parametri non validi','file'=>__FILE__,'line'=>__LINE__ ));
        }

        $template->assign('NewsShowCanale_addNewsFlag', 'false');
        if (array_key_exists($id_canale, $user_ruoli) || $user->isAdmin()) {
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];
                $referente      = $ruolo->isReferente();
                $moderatore     = $ruolo->isModeratore();
            }

            if ( $user->isAdmin() || $referente || $moderatore ) {
                $template->assign('NewsShowCanale_addNewsFlag', 'true');
                $template->assign('NewsShowCanale_addNews', 'Scrivi nuova notizia');
                $template->assign('NewsShowCanale_addNewsUri', 'v2.php?do=NewsAdd&id_canale='.$id_canale);
            }
        }


        $num_news_canale = $this->getNumNewsCanale($id_canale);
        $quantita = $_GET['qta'];
        $template->assign('NewsShowCanale_numPagineFlag', 'false');
        if ($num_news_canale > $quantita) {
            $pages_flag = 'true';
            $num_pagine = ceil($num_news_canale / $quantita);
            $n_pag_list =  array();
            $start = 0;
            for ($i = 1; $i <= $num_pagine; $i++) {
                $n_pag_list[$i] = array('URI' => 'v2.php?do=NewsShowCanale&id_canale='.$id_canale.'&inizio='.$start.'&qta='.$quantita, 'current' => ($_GET['inizio'] == $start) ? 'true' : 'false');
                $start 	= $start + $quantita;
            }
            $template->assign('NewsShowCanale_numPagine', $n_pag_list);
            $template->assign('NewsShowCanale_numPagineFlag', 'true');
        }

        $lista_notizie = $this->getLatestNewsCanale($_GET['inizio'],$quantita,$id_canale);
        $param = array('id_notizie'=> $lista_notizie, 'chk_diritti' => true);
        $this->executePlugin('ShowNews', $param );

        $this->executePlugin('ShowTopic', array('reference' => 'newsutenti'));

        return 'default';
    }

    /**
     * Preleva da database le ultime $num notizie non scadute del canale $id_canale
     *
     * @param  int   $num       numero notize da prelevare
     * @param  int   $id_canale identificativo su database del canale
     * @return array elenco NewsItem , false se non ci sono notizie
     */
    public function getLatestNewsCanale($startNum, $qta, $id_canale)
    {
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');
        return $newsRepo->findLatestByChannel($id_canale, $qta, $startNum); 
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
}
