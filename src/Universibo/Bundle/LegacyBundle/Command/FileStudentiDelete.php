<?php

namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\Entity\Ruolo;

/**
 * FileStudentiDelete: elimina un file studente, mostra il form e gestisce la cancellazione
 *
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FileStudentiDelete extends UniversiboCommand
{
    public function execute()
    {
        $context = $this->get('security.context');

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->throwUnauthorized();
        }

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $user = $context->getToken()->getUser();
        $userId = $user->getId();

        $fileId = $this->getRequest()->attributes->get('id_file');
        $fileStudentiRepo = $this->get('universibo_legacy.repository.files.file_item_studenti');
        $file = $fileStudentiRepo->find($fileId);

        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found');
        }

        $canDelete = $context->isGranted('ROLE_ADMIN') ||
                $file->getIdUtente() == $userId;

        $file_canali = $file->getIdCanali();

        if (!$canDelete) {
            $roleRepo = $this->get('universibo_legacy.repository.ruolo');

            foreach ($file_canali as $channelId) {
                $currentRole = $roleRepo->find($userId, $channelId);

                if ($currentRole instanceof Ruolo &&
                    ($currentRole->isModeratore() ||
                        $currentRole->isReferente())) {
                    $canDelete = true;
                    break;
                }
            }
        }

        if (!$canDelete) {
            throw new AccessDeniedHttpException('Not allowed to delete file');
        }

        $channelRepo  = $this->get('universibo_legacy.repository.canale2');
        $channel = $channelRepo->find($file_canali[0]);

        $cRouter = $this->get('universibo_legacy.routing.channel');

        $template->assign('common_canaleURI', $cRouter->generate($channel));
        $template->assign('common_langCanaleNome', 'a ' . $channel->getTitolo());

        $f25_canale = $channel->getTitolo();

        //accettazione della richiesta
        if (array_key_exists('f25_submit', $_POST)) {
            $file->removeCanale($file_canali[0]);
            $file->deleteFileItem();
            $file->deleteAllCommenti();

            $template->assign('fileDelete_langSuccess', "Il file è stato cancellato con successo dalle pagine scelte.");

            return 'success';
        }

        $template->assign('f25_langAction', "Elimina il file dal canale");
        $template->assign('f25_canale', $f25_canale);
        $template->assign('fileDelete_flagCanali', (count($f25_canale)) ? 'true' : 'false');

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
