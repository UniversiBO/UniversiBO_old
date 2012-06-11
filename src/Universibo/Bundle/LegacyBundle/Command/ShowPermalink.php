<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

use \Error;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShowPermalink extends UniversiboCommand
{

    public function execute()
    {
        if (!array_key_exists('id_notizia', $_GET)
                || !preg_match('/^[0-9]+$/', $id_notizia = $_GET['id_notizia'])) {
            $user = $this->getSessionUser();
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'ID news non valido', 'file' => __FILE__,
                            'line' => __LINE__));
        }
        
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepo->find($id_notizia);
        
        if(!$news instanceof NewsItem) {
            Error::throwError(_ERROR_DEFAULT,
                array('id_notizia' => $id_notizia,
                        'msg' => 'ID news non trovato', 'file' => __FILE__,
                        'line' => __LINE__));
        }

        $template = $this->getFrontController()->getTemplateEngine();
        $template->assignUnicode('news', $this->_newsToArray($news));
    }

    private function _newsToArray(NewsItem $news)
    {
        $user = $this->getSessionUser();
        $krono = $this->getFrontController()->getKrono();

        $newsArray = array();

        $newsArray['id_notizia'] = $news->getIdNotizia();
        $newsArray['titolo'] = $news->getTitolo();
        $newsArray['notizia'] = $news->getNotizia();
        $newsArray['data'] = $krono
                ->k_date('%j/%m/%Y - %H:%i', $news->getDataIns());
        //echo $personalizza,"-" ,$ultimo_accesso,"-", $news->getUltimaModifica()," -- ";
        $newsArray['nuova'] = '';//($personalizza_not_admin == true && $ultimo_accesso < $news->getUltimaModifica()) ? 'true' : 'false';
        $newsArray['autore'] = $news->getUsername();
        $newsArray['autore_link'] = 'ShowUser&id_utente='
                . $news->getIdUtente();
        $newsArray['id_autore'] = $news->getIdUtente();

        $newsArray['scadenza'] = '';
        /*if (($news->getDataScadenza() != NULL) && ( $user->isAdmin() || $referente || $this_moderatore )) {
            $newsArray['scadenza'] = 'Scade il ' . $krono->k_date('%j/%m/%Y', $news->getDataScadenza());
        }*/

        $newsArray['modifica'] = '';
        $newsArray['modifica_link'] = '';
        $newsArray['elimina'] = '';
        $newsArray['elimina_link'] = '';

        return $newsArray;
    }
}
