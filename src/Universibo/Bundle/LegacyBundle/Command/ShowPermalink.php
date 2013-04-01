<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPLv2
 */
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;

/**
 * ShowPermalink Legacy Command
 */
class ShowPermalink extends UniversiboCommand
{

    /**
     * Controller action
     *
     * @throws NotFoundHttpException
     */
    public function execute()
    {
        $newsRepo = $this->getContainer()->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepo->find($id_notizia = $this->getRequest()->attributes->get('id_notizia'));

        $isExpired = function(NewsItem $news) {
            return $news->getDataScadenza() > 0 && $news->getDataScadenza() < time();
        };

        if (!$news instanceof NewsItem || $isExpired($news)) {
            throw new NotFoundHttpException('News not found');
        }

        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user instanceof User) {
            $user = null;
        }

        $acl = $this->get('universibo_legacy.acl');
        $channelIds = $newsRepo->getChannelIdList($news);

        $channelRepo = $this->get('universibo_legacy.repository.canale2');
        $channels = $channelRepo->findManyById($channelIds);

        $channelRouter = $this->get('universibo_legacy.routing.channel');

        $channelList = array();

        foreach ($channels as $channel) {
            if ($acl->canRead($user, $channel)) {
                $channelList[] = [
                    'name' => $channel->getNome(),
                    'uri' => $channelRouter->generate($channel)
                ];
            }
        }

        $template = $this->getFrontController()->getTemplateEngine();
        $template->assign('common_title', $news->getTitolo() . ' :: UniversiBO');
        $template->assign('news', $this->_newsToArray($news));
        $template->assign('channels', $channelList);
    }

    /**
     * Populates an array from a NewsItem object
     *
     * @param  NewsItem $news
     * @return array
     */
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
