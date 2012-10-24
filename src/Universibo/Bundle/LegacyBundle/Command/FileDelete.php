<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
/**
 * NewsDelete: elimina una notizia, mostra il form e gestisce la cancellazione
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileDelete extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');

        $template->assign('common_canaleURI', $router->generate('universibo_legacy_myuniversibo'));
        $template->assign('common_langCanaleNome', 'indietro');

        $user = $this->get('security.context')->getToken()->getUser();

        $referente = false;
        $moderatore = false;

        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $request = $this->getRequest();
        $fileId = $request->attributes->get('id_file');

        $fileRepo = $this->get('universibo_legacy.repository.files.file_item');
        $file = $fileRepo->find($fileId);

        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found');
        }

        $autore = ($user->getId() == $file->getIdUtente());
        $channelId = intval($request->get('id_canale', 0));
        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        if ($channelId > 0) {
            $canale = $channelRepo->find($channelId);

            if (!$canale instanceof Canale || !$canale->getServizioFiles()) {
                throw new NotFoundHttpException('Channel not found');
            }

            $template->assign('common_canaleURI', $canale->showMe($router));
            $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());

            if (array_key_exists($channelId, $user_ruoli)) {
                $ruolo = &$user_ruoli[$channelId];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

            //controllo coerenza parametri
            $canali_file = $file->getIdCanali();
            if (!in_array($channelId, $canali_file))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($channelId);

            if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore)))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => "Non hai i diritti per eliminare il file\n La sessione potrebbe essere scaduta",
                                'file' => __FILE__, 'line' => __LINE__));

        } elseif (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $autore))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Non hai i diritti per eliminare il file\n La sessione potrebbe essere scaduta",
                            'file' => __FILE__, 'line' => __LINE__));

        $elenco_canali = array_keys($user_ruoli);
        //
        //		$ruoli_keys = array_keys($user_ruoli);
        //		$num_ruoli = count($ruoli_keys);
        //		for ($i = 0; $i < $num_ruoli; $i ++)
        //		{
        //			$elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        //		}
        //
        $file_canali = $file->getIdCanali();

        $f14_canale = array();
        $num_canali = count($file_canali);
        for ($i = 0; $i < $num_canali; $i++) {
            $id_current_canale = $file_canali[$i];
            $current_canale = $channelRepo->find($id_current_canale);
            $nome_current_canale = $current_canale->getTitolo();
            if (in_array($id_current_canale, $file->getIdCanali())) {
                $f14_canale[] = array('id_canale' => $id_current_canale,
                        'nome_canale' => $nome_current_canale,
                        'spunta' => 'true');
            }
        }

        $f14_accept = false;

        //postback

        if (array_key_exists('f14_submit', $_POST)) {

            $f14_accept = true;

            $f14_canale_app = array();
            //controllo diritti su ogni canale di cui ? richiesta la cancellazione
            if (array_key_exists('f14_canale', $_POST)) {
                foreach ($_POST['f14_canale'] as $key => $value) {
                    $diritti = $this->get('security.context')->isGranted('ROLE_ADMIN')
                            || (array_key_exists($key, $user_ruoli)
                                    && ($user_ruoli[$key]->isReferente()
                                            || ($user_ruoli[$key]
                                                    ->isModeratore() && $autore)));
                    if (!$diritti) {
                        //$user_ruoli[$key]->getIdCanale();
                        $canale = $channelRepo->find($key);
                        Error::throwError(_ERROR_NOTICE,
                                array('id_utente' => $user->getId(),
                                        'msg' => 'Non possiedi i diritti di eliminazione nel canale: '
                                                . $canale->getTitolo(),
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f14_accept = false;
                    } else
                        $f14_canale_app[$key] = $value;
                }
            } elseif (count($f14_canale) > 0) {
                $f14_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Devi selezionare almeno una pagina:',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

        }

        //accettazione della richiesta
        if ($f14_accept == true) {
            //			var_dump($_POST['f14_canale'] );
            //			die();
            //cancellazione dai canali richiesti
            foreach ($f14_canale_app as $key => $value) {
                $file->removeCanale($key);
            }

            $file->deleteFileItem();
            /**
             * @TODO elenco dei canali dai quali è stata effetivamente cancellata la notizia
             */
            $template
                    ->assign('fileDelete_langSuccess',
                            "Il file è stato cancellato con successo dalle pagine scelte.");

            return 'success';
        }

        $template
                ->assign('f14_langAction',
                        "Elimina il file dalle seguenti pagine:");
        $template->assign('f14_canale', $f14_canale);
        $template
                ->assign('fileDelete_flagCanali',
                        (count($f14_canale)) ? 'true' : 'false');

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
