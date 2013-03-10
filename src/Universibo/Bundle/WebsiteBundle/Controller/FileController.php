<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 */
class FileController extends Controller
{
    public function studentBoxAction($channelId)
    {
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item_studenti');

        $ids = $fileRepo->findIdByChannel($channelId);
        $files = $fileRepo->findMany($ids);

        $response = $this->render('UniversiboWebsiteBundle:File:studentBox.html.twig', array(
            'files' => $files,
            'channelId' => $channelId
        ));

        $response->setMaxAge(30);
        $response->setPrivate();

        return $response;
    }
}
