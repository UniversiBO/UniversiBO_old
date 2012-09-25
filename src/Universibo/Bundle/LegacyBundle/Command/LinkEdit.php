<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Links\Link;

/**
 * NewsEdit: si occupa della modifica di una news in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class LinkEdit extends CanaleCommand
{

    /**
     * Deve stampare "La notizia ? gi? presente nei seguenti canali"
     */
    public function execute()
    {

        $user = $this->getSessionUser();
        $canale = $this->getRequestCanale();
        $user_ruoli = $user->getRuoli();
        $id_canale = $canale->getIdCanale();

        //diritti
        $referente = false;
        $moderatore = false;

        if (!array_key_exists('id_link', $_GET)
                || !preg_match('/^([0-9]{1,9})$/', $_GET['id_link'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'id del link richiesta non e`	valido',
                            'file' => __FILE__, 'line' => __LINE__));
        }
        if ($canale->getServizioLinks() == false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Il servizio link e` disattivato",
                            'file' => __FILE__, 'line' => __LINE__));

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        $link = Link::selectLink($_GET['id_link']);
        $autore = ($user->getIdUser() == $link->getIdUtente());

        //		//controllo coerenza parametri
        //		$canali_news	= 	$news->getIdCanali();
        //		if (!in_array($id_canale, $canali_news))
        //			 Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'I parametri passati non sono coerenti', 'file' => __FILE__, 'line' => __LINE__));
        //
        $canale_link = $link->getIdCanale();
        if ($id_canale != $canale_link)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'I parametri passati non sono coerenti',
                            'file' => __FILE__, 'line' => __LINE__));

        if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore)))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Non hai i diritti per modificare il link\n La sessione potrebbe essere scaduta",
                            'file' => __FILE__, 'line' => __LINE__));

        $this
                ->executePlugin('ShowLink',
                        array('id_link' => $_GET['id_link'],
                                'id_canale' => $_GET['id_canale']));

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();

        // valori default form

        $f31_URI = $link->getUri();
        $f31_Label = $link->getLabel();
        $f31_Description = $link->getDescription();

        //		$elenco_canali = array ($id_canale);
        //		$ruoli_keys = array_keys($user_ruoli);
        //		$num_ruoli = count($ruoli_keys);
        //		for ($i = 0; $i < $num_ruoli; $i ++)
        //		{
        //			if ($id_canale != $ruoli_keys[$i])
        //				$elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        //		}
        //
        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i < $num_canali; $i ++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale =  Canale :: retrieveCanale($id_current_canale);
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$spunta = (in_array($id_current_canale, $news->getIdCanali())) ? 'true' : 'false';
        //			$f31_canale[] = array ('id_canale' => $id_current_canale, 'nome_canale' => $nome_current_canale, 'spunta' => $spunta);
        //		}

        $f31_accept = false;

        if (array_key_exists('f31_submit', $_POST)) {
            $f31_accept = true;

            if (!array_key_exists('f31_URI', $_POST)
                    || !array_key_exists('f31_Label', $_POST)
                    || !array_key_exists('f31_Description', $_POST))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $f31_URI = $_POST['f31_URI'];
            $f31_Description = $_POST['f31_Description'];
            $f31_Label = $_POST['f31_Label'];

            if (!preg_match('/^(http(s)?|ftp):\\/\\/|^.{0}$/', $f31_URI)) {
                $f31_accept = false;
                $f31_URI = 'http://';
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\'URL del link alla pagina degli obiettivi deve iniziare con https://, http:// o ftp://, verificare di non aver lasciato spazi vuoti',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f31_Label === '') {
                $f31_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Non hai assegnato un\'etichetta al link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f31_Description === '') {
                $f31_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Non hai dato una descrizione del link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if (strlen($f31_Description) > 1000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La descrizione del link deve essere inferiore ai 1000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f31_accept = false;
            }

            if (strlen($f31_Label) > 127) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\'etichetta del link deve essere inferiore ai 127 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f31_accept = false;
            }

            if ($f31_accept === true) {
                $linkItem = new Link($_GET['id_link'], $id_canale,
                        $user->getIdUser(), $f31_URI, $f31_Label,
                        $f31_Description);
                $linkItem->updateLink();
                $canale->setUltimaModifica(time(), true);

                return 'success';
            }
        } //end if (array_key_exists('f31_submit', $_POST))

        $template->assign('f31_URI', $f31_URI);
        $template->assign('f31_Label', $f31_Label);
        $template->assign('f31_Description', $f31_Description);

        //		$this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));
        return 'default';

    }
}
