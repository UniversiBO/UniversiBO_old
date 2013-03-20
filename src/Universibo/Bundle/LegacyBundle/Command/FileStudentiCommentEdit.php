<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;

/**
 * FileStudentiCommentEdit: Modifica un commento di un File Studente
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @author Daniele Tiles
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FileStudentiCommentEdit extends UniversiboCommand
{

    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $router = $this->get('router');
        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $request = $this->getRequest();
        $commentId = $request->attributes->get('id_commento');
        $commentRepo = $this->get('universibo_legacy.repository.commenti.commento_item');
        $comment = $commentRepo->find($commentId);

        if (!$comment instanceof CommentoItem) {
            throw new NotFoundHttpException('Comment not found');
        }

        $id_utente = $comment->getIdUtente();
        $id_file_studente = $comment->getIdFileStudente();

        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        $autore = ($id_utente == $user->getId());

        $id_canale = $request->get('id_canale');
        if ($id_canale !== null) {
            if (!preg_match('/^([0-9]{1,9})$/', $id_canale))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = Canale::retrieveCanale($id_canale);
            if ($canale->getServizioFilesStudenti() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => "Il servizio files studenti e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }
            //controllo coerenza parametri
            $file = FileItemStudenti::selectFileItem($id_file_studente);
            $canali_file = $file->getIdCanali();

            // TODO: perché non funziona il controllo???

            //			var_dump($canali_file);
            //			die();
            //			if (!in_array($id_canale, $canali_file))
            //				 Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'I parametri passati non sono coerenti', 'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($id_canale);

            //controllo diritti sul canale
            if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore || $autore))
                throw new AccessDeniedHttpException('Not allowed to edit comment');

        } elseif (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $autore))
            throw new AccessDeniedHttpException('Not allowed to edit comment');

        // valori default form
        // $f27_file = '';
        $f27_commento = $comment->getCommento();
        $f27_voto = $comment->getVoto();

        $this
                ->executePlugin('ShowFileStudentiCommento',
                        array('id_commento' => $commentId));

        $f27_accept = false;

        if (array_key_exists('f27_submit', $_POST)) {
            $f27_accept = true;

            //commento
            if (trim($_POST['f27_commento']) == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Inserisci un commento',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f27_accept = false;
            } else {
                $f27_commento = $_POST['f27_commento'];
            }

            //voto
            if (!preg_match('/^([0-5]{1})$/', $_POST['f27_voto'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Voto non valido', 'file' => __FILE__,
                                'line' => __LINE__, 'log' => false,
                                'template_engine' => &$template));
                $f27_accept = false;
            } else
                $f27_voto = $_POST['f27_voto'];

            //esecuzione operazioni accettazione del form
            if ($f27_accept == true) {

                CommentoItem::updateCommentoItem($commentId, $f27_commento, $f27_voto);
                $template->assign('common_canaleURI',$router->generate('universibo_legacy_file', array('id_file' => $id_file_studente, 'id_canale' => $id_canale)));

                return 'success';
            }

        }
        //end if (array_key_exists('f27_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f27_commento', $f27_commento);
        $template->assign('f27_voto', $f27_voto);

        return 'default';

    }

}
