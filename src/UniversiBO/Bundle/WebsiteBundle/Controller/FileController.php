<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/file")
 */
class FileController extends Controller
{
    /**
     */
    public function indexAction($channelId)
    {
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');
        return $this->getMyResponse($fileRepo->findByChannel($channelId));
    }
    
    public function byIdsAction(array $ids)
    {
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');
        return $this->getMyResponse($fileRepo->findManyById($ids));
    }
    
    private function getMyResponse(array $fileObj)
    {
        $files = array();
        
        foreach ($fileObj as $file) {
        	$files[$file->getCategoriaDesc()][] = $file;
        }
        
        return $this->render('UniversiBOWebsiteBundle:File:index.html.twig',array('files' => $files));
    }
}
