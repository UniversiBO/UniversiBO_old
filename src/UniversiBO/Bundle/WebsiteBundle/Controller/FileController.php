<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $scontext = $this->get('security.context');
        $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
                        ->getToken()->getUser() : null;

        $acl = $this->get('universibo_legacy.acl');

        $files = array();

        foreach ($fileObj as $file) {
            if ($acl->canRead($user, $file)) {
                $files[$file->getCategoriaDesc()][] = $file;
            }
        }

        return $this
                ->render('UniversiBOWebsiteBundle:File:index.html.twig',
                        array('files' => $files));
    }
}
