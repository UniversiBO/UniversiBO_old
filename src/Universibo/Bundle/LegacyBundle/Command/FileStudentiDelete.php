<?php

namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

/**
 * FileStudentiDelete: elimina un file studente, mostra il form e gestisce la cancellazione
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FileStudentiDelete extends UniversiboCommand {

    public function execute() {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');

        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;

        $referente = false;
        $moderatore = false;
        
        $fileStudentiRepo = $this->get('universibo_legacy.repository.files.file_item_studenti');
        $file = $fileStudentiRepo->find($this->getRequest()->attributes->get('id_file'));
        
        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found');
        }

        $user_ruoli = $userId > 0 ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($userId) : array();
        

        

        $file_canali = $file->getIdCanali();
        $canale = Canale::retrieveCanale($file_canali[0]);

        $template->assign('common_canaleURI', $canale->showMe($router));
        $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());

        $autore = ($userId == $file->getIdUtente());

        $id_canale = $canale->getIdCanale();
        $template->assign('common_canaleURI', $canale->showMe($router));
        $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        $elenco_canali = array($id_canale);

        if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore || $autore))
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
                'msg' => "Non hai i diritti per eliminare il file\n La sessione potrebbe essere scaduta",
                'file' => __FILE__, 'line' => __LINE__));

        //$elenco_canali = array ($id_canale);
        $ruoli_keys = array_keys($user_ruoli);
        $num_ruoli = count($ruoli_keys);
        for ($i = 0; $i < $num_ruoli; $i++) {
            $elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        }

        $f25_canale = $canale->getTitolo();
        $f25_accept = false;

        //postback

        if (array_key_exists('f25_submit', $_POST)) {

            $f25_accept = true;

            $f25_canale_app = array();
            //controllo diritti su ogni canale di cui ? richiesta la cancellazione
            if (array_key_exists('f25_canale', $_POST)) {
                foreach ($_POST['f25_canale'] as $key => $value) {
                    $diritti = $this->get('security.context')->isGranted('ROLE_ADMIN')
                            || (array_key_exists($key, $user_ruoli)
                            && ($user_ruoli[$key]->isReferente()
                            || ($user_ruoli[$key]
                                    ->isModeratore() && $autore)));
                    if (!$diritti) {
                        //$user_ruoli[$key]->getIdCanale();
                        $canale = Canale::retrieveCanale($key);
                        Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                            'msg' => 'Non possiedi i diritti di eliminazione nel canale: '
                            . $canale->getTitolo(),
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => false,
                            'template_engine' => &$template));
                        $f25_accept = false;
                    } else
                        $f25_canale_app[$key] = $value;
                }
            }
            //			elseif(count($f25_canale) > 0)
            //			{
            //				$f25_accept = false;
            //				Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getId(), 'msg' => 'Devi selezionare almeno una pagina:', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
            //			}
        }

        //accettazione della richiesta
        if ($f25_accept == true) {
            //			var_dump($_POST['f25_canale'] );
            //			die();
            //cancellazione dai canali richiesti
            //			foreach ($f25_canale_app as $key => $value)
            //			{
            //				$file->removeCanale($key);
            //				$canale = Canale::retrieveCanale($key);
            //			}

            $file->removeCanale($file_canali[0]);
            $canale = Canale::retrieveCanale($file_canali[0]);
            $file->deleteFileItem();
            $file->deleteAllCommenti();

            $template->assign('fileDelete_langSuccess', "Il file è stato cancellato con successo dalle pagine scelte.");

            return 'success';
        }

        //visualizza notizia
        //$param = array('id_notizie'=>array($_GET['id_news']), 'chk_diritti' => false );
        //$this->executePlugin('ShowNews', $param );

        $template->assign('f25_langAction', "Elimina il file dal canale");
        $template->assign('f25_canale', $f25_canale);
        $template->assign('fileDelete_flagCanali', (count($f25_canale)) ? 'true' : 'false');

        //$this->executePlugin('ShowTopic', array('reference' => 'filestudenti'));
        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
