<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;

/**
 * FileStudentiCommentDelete: Cancella un commento di un File Studente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileStudentiCommentDelete extends UniversiboCommand
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

        $channelId = $request->get('id_canale');
        if ($channelId !== null) {
            if (!preg_match('/^([0-9]{1,9})$/', $channelId))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = Canale::retrieveCanale($channelId);
            if ($canale->getServizioFilesStudenti() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => "Il servizio files studenti e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            if (array_key_exists($channelId, $user_ruoli)) {
                $ruolo = $user_ruoli[$channelId];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }
            //controllo coerenza parametri
            $file = FileItemStudenti::selectFileItem($id_file_studente);
            $canali_file = $file->getIdCanali();

            //TODO: perché non funziona il controllo???

            //			var_dump($canali_file);
            //			die();
            //			if (!in_array($channelId, $canali_file))
            //				 Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'I parametri passati non sono coerenti', 'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($channelId);

            //controllo diritti sul canale
            if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore || $autore))
                throw new AccessDeniedHttpException('Not allowed to delete comment');
        } elseif (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $autore))
            throw new AccessDeniedHttpException('Not allowed to delete comment');

        // valori default form
        // $f28_file = '';

        $this
                ->executePlugin('ShowFileStudentiCommento',
                        array('id_commento' => $commentId));

        $f28_accept = false;

        if (array_key_exists('f28_submit', $_POST)) {
            $f28_accept = true;

            //esecuzione operazioni accettazione del form
            if ($f28_accept == true) {

                CommentoItem::deleteCommentoItem($commentId);
                $template->assign('common_canaleURI', $router->generate('universibo_legacy_file', array('id_file' => $id_file_studente, 'id_canale' => $channelId)));

                return 'success';
            }

        }
        //end if (array_key_exists('f28_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging
        return 'default';

    }

}
