<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     */
    public function indexAction($channelId, $limit = null)
    {
        $newsRepo = $this->get('universibo_legacy.repository.news.news_item');

        $response = $this->render('UniversiBOWebsiteBundle:News:index.html.twig', array('news' => $newsRepo->findByCanale($channelId, $limit)));
        $response->setSharedMaxAge(300);

        return $response;
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
