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
     * @Template()
     */
    public function indexAction($channelId)
    {
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');
        
        $files = array();
        
        foreach ($fileRepo->findByChannel($channelId) as $file) {
            $files[$file->getCategoriaDesc()][] = $file;
        }
        
        return array('files' => $files);
    }
}
