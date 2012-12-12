<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShowPermalink extends UniversiboCommand
{

    public function execute()
    {
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepo->find($id_notizia = $this->getRequest()->attributes->get('id_notizia'));

        if (!$news instanceof NewsItem) {
            throw new NotFoundHttpException('News not found');
        }

        $template = $this->getFrontController()->getTemplateEngine();
        $template->assign('title', $news->getTitolo() . ' :: UniversiBO');
        $template->assign('news', $this->_newsToArray($news));
    }

    private function _newsToArray(NewsItem $news)
    {
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
        $newsArray['autore_link'] = $this->get('router')->generate('universibo_legacy_user', array('id_utente' => $news->getIdUtente()));
        $newsArray['id_autore'] = $news->getIdUtente();

        $newsArray['scadenza'] = '';
        /*if (($news->getDataScadenza() != NULL) && ( $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore )) {
            $newsArray['scadenza'] = 'Scade il ' . $krono->k_date('%j/%m/%Y', $news->getDataScadenza());
        }*/

        $newsArray['permalink']     = $this->generateUrl('universibo_legacy_permalink', array('id_notizia' => $news->getIdNotizia()));
        $newsArray['modifica'] = '';
        $newsArray['modifica_link'] = '';
        $newsArray['elimina'] = '';
        $newsArray['elimina_link'] = '';

        return $newsArray;
    }
}
