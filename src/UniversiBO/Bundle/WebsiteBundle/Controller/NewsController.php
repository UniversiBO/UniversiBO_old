<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use UniversiBO\Bundle\LegacyBundle\Auth\UniversiBOAcl;

use UniversiBO\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

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
