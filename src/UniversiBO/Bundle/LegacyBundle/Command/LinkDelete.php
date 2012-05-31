<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\App\CanaleCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\Links\Link;

/**
 * LinkDelete: elimina un link, mostra il form e gestisce la cancellazione
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Cristina Valent
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class LinkDelete extends CanaleCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template->assign('common_canaleURI', 'v2.php?do=ShowMyUniversiBO');
        $template->assign('common_langCanaleNome', 'indietro');

        $user = $this->getSessionUser();

        $referente = false;
        $moderatore = false;

        $user_ruoli = $user->getRuoli();

        if (!array_key_exists('id_link', $_GET)
                || !preg_match('/^([0-9]{1,9})$/', $_GET['id_link'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'id del link richiesto non � valido',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $link = &Link::selectLink($_GET['id_link']);
        if ($link === false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'Il link richiesto non � presente su database',
                            'file' => __FILE__, 'line' => __LINE__));

        $autore = ($user->getIdUser() == $link->getIdUtente());

        if (array_key_exists('id_canale', $_GET)) {
            if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\'id del canale richiesto non � valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = &Canale::retrieveCanale($_GET['id_canale']);

            if ($canale->getServizioLinks() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il servizio links � disattivato',
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();
            $template->assign('common_canaleURI', $canale->showMe());
            $template
                    ->assign('common_langCanaleNome', 'a '
                            . $canale->getTitolo());

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = &$user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

            //controllo coerenza parametri
            $canale_link = $link->getIdCanale();
            if ($id_canale != $canale_link)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            if (!($user->isAdmin() || $referente || ($moderatore && $autore)))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Non hai i diritti per eliminare il Link\n La sessione potrebbe essere scaduta',
                                'file' => __FILE__, 'line' => __LINE__));

        } elseif (!($user->isAdmin() || $autore))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'Non hai i diritti per eliminare il link\n La sessione potrebbe essere scaduta',
                            'file' => __FILE__, 'line' => __LINE__));

        //postback

        if (array_key_exists('f30_submit', $_POST)) {
            if ($link->deleteLink()) {
                $template
                        ->assign('f30_langAction',
                                "Il link � stato eliminato correttamente");

                return 'success';
            }
        }

        //$this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));
        $this
                ->executePlugin('ShowLink',
                        array('id_link' => $_GET['id_link'],
                                'id_canale' => $_GET['id_canale']));

        return 'default';
    }
}
