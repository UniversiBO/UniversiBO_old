<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Links\Link;

/**
 * NewsEdit: si occupa della modifica di una news in un canale
 *
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

        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $userId = $user instanceof User ? $user->getId() : 0;

        //diritti
        $referente = false;
        $moderatore = false;

        $id_canale = $this->getRequest()->get('id_canale');
        $canale = $this->get('universibo_legacy.repository.canale')->find($id_canale);
        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Channel with id='.$id_canale.' not found');
        }

        if ($canale->getServizioLinks() == false) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Il servizio link e` disattivato",
                            'file' => __FILE__, 'line' => __LINE__));
        }

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        $id_link = $this->getRequest()->attributes->get('id_link');
        $link = $this->get('universibo_legacy.repository.links.link')->find($id_link);
        $autore = ($userId == $link->getIdUtente());

        //		//controllo coerenza parametri
        //		$canali_news	= 	$news->getIdCanali();
        //		if (!in_array($id_canale, $canali_news))
        //			 Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'I parametri passati non sono coerenti', 'file' => __FILE__, 'line' => __LINE__));
        //
        $canale_link = $link->getIdCanale();
        if ($id_canale != $canale_link)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => 'I parametri passati non sono coerenti',
                            'file' => __FILE__, 'line' => __LINE__));

        if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore)))
            throw new AccessDeniedHttpException('Not allowed to edit link');

        $this
                ->executePlugin('ShowLink',
                        array('id_link' => $id_link,
                                'id_canale' => $id_canale));

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        // valori default form

        $f31_URI = $link->getUri();
        $f31_Label = $link->getLabel();
        $f31_Description = $link->getDescription();

        $f31_accept = false;

        if (array_key_exists('f31_submit', $_POST)) {
            $f31_accept = true;

            if (!array_key_exists('f31_URI', $_POST)
                    || !array_key_exists('f31_Label', $_POST)
                    || !array_key_exists('f31_Description', $_POST))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $f31_URI = $_POST['f31_URI'];
            $f31_Description = $_POST['f31_Description'];
            $f31_Label = $_POST['f31_Label'];

            if (!preg_match('/^(http(s)?|ftp):\\/\\/|^.{0}$/', $f31_URI)) {
                $f31_accept = false;
                $f31_URI = 'http://';
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'URL del link alla pagina degli obiettivi deve iniziare con https://, http:// o ftp://, verificare di non aver lasciato spazi vuoti',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f31_Label === '') {
                $f31_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Non hai assegnato un\'etichetta al link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f31_Description === '') {
                $f31_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Non hai dato una descrizione del link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if (strlen($f31_Description) > 1000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La descrizione del link deve essere inferiore ai 1000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f31_accept = false;
            }

            if (strlen($f31_Label) > 127) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'etichetta del link deve essere inferiore ai 127 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f31_accept = false;
            }

            if ($f31_accept === true) {
                $linkItem = new Link($id_link, $id_canale,
                        $user->getId(), $f31_URI, $f31_Label,
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
