<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction($channelId, $limit = null)
    {
        $newsRepo = $this->get('universibo_legacy.repository.news.news_item');

        return array('news' => $newsRepo->findByCanale($channelId, $limit));
    }

    public function byIdsAction(array $ids)
    {
        $newsRepo = $this->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepo->findMany($ids);

        return $this->render('UniversiboWebsiteBundle:News:index.html.twig', array('news' => $news));
    }

    /**
     * @Template()
     * @Route("/{id}", name="news_show")
     */
    public function showAction($id)
    {
        $newsRepo = $this->get('universibo_legacy.repository.news.news_item');

        return array('news' => $newsRepo->find($id));
    }
}
