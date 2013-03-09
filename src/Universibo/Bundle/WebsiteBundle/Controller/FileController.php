<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 */
class FileController extends Controller
{
    public function studentBoxAction(array $channel)
    {
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item_studenti');

        $ids = $fileRepo->findIdByChannel($channel['id_canale']);
        $files = $fileRepo->findMany($ids);

        $response = $this->render('UniversiboWebsiteBundle:File:studentBox.html.twig', array(
            'files' => $files,
            'channelId' => $channel['id_canale']
        ));

        $response->setSharedMaxAge(30);
        $response->setPublic();

        return $response;
    }
}
