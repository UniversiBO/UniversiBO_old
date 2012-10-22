<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * FileStudentiComment: si occupa dell'inserimento di un nuovo commento per il File Studente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileStudentiComment extends UniversiboCommand
{

    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Per questa operazione bisogna essere registrati\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $fileId = $this->getRequest()->attributes->get('id_file');
        $fileRepo = $this->get('universibo_legacy.repository.files.file_item_studenti');
        $file = $fileRepo->find($fileId);

        if (!$file instanceof FileItemStudenti) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('File not found');
        }

        //Controllo che non esista già un commento da parte di questo utente
        $template->assign('esiste_CommentoItem', 'false');
        $id_commento = CommentoItem::esisteCommento($fileId, $user->getId());
        if ($id_commento != NULL) {
            $canali = $file->getIdCanali();

            $template->assign('FileStudentiComment_ris', 'Esiste già un tuo commento a questo file.');
            $template->assign('common_canaleURI', $router->generate('universibo_legacy_file', array('id_file' => $id_file, 'id_canale' => $canali[0])));
            $template->assign('FilesStudentiComment_modifica', $router->generate('universibo_legacy_file_studenti_comment_edit', array('id_canale' => $canali[0], 'id_commento' => $id_commento)));
            $template->assign('esiste_CommentoItem', 'true');

            return 'success';
        }

        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');
        $id_file = $fileId;

        // valori default form
        $f26_commento = '';
        $f26_voto = '';

        $f26_accept = false;

        if (array_key_exists('f26_submit', $_POST)) {
            $f26_accept = true;

            if (!array_key_exists('f26_commento', $_POST)
                    || !array_key_exists('f26_voto', $_POST)) {
                //var_dump($_POST);die();
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f26_accept = false;
            }

            //commento
            if (trim($_POST['f26_commento']) == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Inserisci un commento',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f26_accept = false;
            } else {
                $f26_commento = $_POST['f26_commento'];
            }

            //voto
            if (!preg_match('/^([0-5]{1})$/', $_POST['f26_voto'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Voto non valido', 'file' => __FILE__,
                                'line' => __LINE__, 'log' => false,
                                'template_engine' => &$template));
                $f26_accept = false;
            } else
                $f26_voto = $_POST['f26_voto'];

            //esecuzione operazioni accettazione del form
            if ($f26_accept == true) {

                CommentoItem::insertCommentoItem($id_file, $user->getId(),
                        $f26_commento, $f26_voto);

                $canali = $file->getIdCanali();
                $template
                        ->assign('FileStudentiComment_ris',
                                'Il tuo commento è stato inserito con successo.');
                $template->assign('common_canaleURI', $router->generate('universibo_legacy_file', array('id_file' => $id_file, 'id_canale' => $canali[0])));

                return 'success';
            }

        }
        //end if (array_key_exists('f26_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f26_commento', $f26_commento);
        $template->assign('f26_voto', $f26_voto);

        return 'default';

    }
}
