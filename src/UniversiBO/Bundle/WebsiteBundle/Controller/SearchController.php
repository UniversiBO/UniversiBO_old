<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;


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

        $path = $this->get('kernel')->getRootDir() . '/data/lucene';


        return array();
    }
}
