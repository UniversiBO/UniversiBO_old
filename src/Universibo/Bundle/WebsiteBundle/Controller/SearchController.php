<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Template()
     */
    public function boxAction()
    {
        return array();
    }

    /**
     * @Template()
     * @Method("get")
     * @Route("", name="search_search")
     */
    public function searchAction()
    {
        $query = $this->getRequest()->query->get('query', '');
        $index = $this->get('universibo_website.search.lucene');

        $news = array();
        $files = array();

        foreach ($index->find($query) as $hit) {
            switch ($hit->type) {
                case 'news':
                    $news[] = $hit->dbId;
                    break;
                case 'file':
                    $files[] = $hit->dbId;
                    break;
            }
        }

        return array('news' => $news, 'files' => $files, 'searchQuery' => $query);
    }
}
