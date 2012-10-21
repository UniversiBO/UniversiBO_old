<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

/**
 * FileDownload: si occupa del download di un file
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileDownload extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;
        $request = $this->getRequest();
        $router = $this->get('router');

        $fileId = $request->attributes->get('id_file');
        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');
        $file = $fileRepo->find($fileId);

        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found!');
        }

        $template->assign('fileDownload_InfoURI', $router->generate('universibo_legacy_file', array('id_file' => $file->getIdFile())));

        $groups = $user instanceof User ? $user->getLegacyGroups() : 1;
        if ($file->getPermessiDownload() & $groups) {
            $directoryFile = $frontcontroller->getAppSetting('filesPath');
            //$directoryFileUri = $frontcontroller->getAppSetting('directoryFileUri');

            $nomeFile = $directoryFile . $file->getNomeFile();
            //echo $nomeFile;die();

            if (!file_exists($nomeFile))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $userId,
                                'msg' => 'Impossibile trovare il file richiesto, contattare l\'amministratore del sito',
                                'file' => __FILE__, 'line' => __LINE__));
            if (md5_file($nomeFile) != $file->getHashFile())
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $userId,
                                'msg' => 'Il file richiesto risulta corrotto, contattare l\'amministratore del sito',
                                'file' => __FILE__, 'line' => __LINE__));

            if ($file->getPassword() != null) {
                if (!array_key_exists('f11_submit', $_POST)) {
                    $this
                            ->executePlugin('ShowTopic',
                                    array('reference' => 'filesutenti'));

                    return 'file_download_password';
                }

                if (!array_key_exists('f11_file_password', $_POST))
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $userId,
                                    'msg' => 'Il form inviato non e` valido',
                                    'file' => __FILE__, 'line' => __LINE__));

                if ($file->getPassword()
                        != FileItem::passwordHashFunction(
                                $_POST['f11_file_password'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('msg' => 'La password inviata ? errata',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $this
                            ->executePlugin('ShowTopic',
                                    array('reference' => 'filesutenti'));

                    return 'file_download_password';
                }
            }

            $file->addDownload();

            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) {
                // had to make it MSIE 5.5 because if 6 has no "attachment;"
                // in it defaults to "inline"
                $attachment = "";
            } else {
                $attachment = "attachment;";
            }
            header('Content-Type: application/octet-stream');
            header('Cache-Control: private');
            header('Pragma: dummy-pragma');
            header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT"); // Date in the past
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
            header("Content-Length: " . @filesize($nomeFile));
            ////header("Content-type: application/force-download");
            header("Content-type: application/octet-stream");
            header("Content-Transfer-Encoding: binary");
            header(
                    "Content-disposition: $attachment filename="
                            . basename($nomeFile));

            //echo $nomeFile;
            readfile($nomeFile);

            exit();

            return;

        }

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this
                    ->executePlugin('ShowTopic',
                            array('reference' => 'filesutenti'));

            return 'file_download_iscriviti';
        }

        Error::throwError(_ERROR_DEFAULT,
                array('id_utente' => $userId,
                        'msg' => 'Non e` permesso eseguire il download del file.
                Non possiedi i diritti necessari.', 'file' => __FILE__,
                        'line' => __LINE__, 'log' => true));

    }
}
