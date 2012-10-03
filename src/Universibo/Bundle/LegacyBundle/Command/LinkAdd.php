<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Links\Link;

/**
 * LinkAdd: si occupa dell'inserimento di un link in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class LinkAdd extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $router = $this->get('router');

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => 0,
                            'msg' => "Per questa operazione bisogna essere registrati\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }
        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        // valori default form
        $f29_URI = 'http://';
        $f29_Label = '';
        $f29_Description = '';

        $f29_accept = false;

        $id_canale = $this->getRequest()->attributes->get('id_canale');

        $canale = $this->get('universibo_legacy.repository.canale')->find($id_canale);
        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Channel not found');
        }

        if ($canale->getServizioLinks() == false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Il servizio links e` disattivato",
                            'file' => __FILE__, 'line' => __LINE__));

        $id_canale = $canale->getIdCanale();
        $template->assign('common_canaleURI', $canale->showMe($router));
        $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());
        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        if (array_key_exists('f29_submit', $_POST)) {
            $f29_accept = true;

            if (!array_key_exists('f29_URI', $_POST)
                    || !array_key_exists('f29_Label', $_POST)
                    || !array_key_exists('f29_Description', $_POST))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $f29_URI = $_POST['f29_URI'];
            $f29_Description = $_POST['f29_Description'];
            $f29_Label = $_POST['f29_Label'];

            if (!preg_match('/^(http(s)?|ftp):\\/\\/|^.{0}$/', $f29_URI)) {
                $f29_accept = false;
                $f29_URI = 'http://';
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'URL del link alla pagina degli obiettivi deve iniziare con https://, http:// o ftp://, verificare di non aver lasciato spazi vuoti',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f29_Label === '') {
                $f29_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Non hai assegnato un\'etichetta al link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if ($f29_Description === '') {
                $f29_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Non hai dato una descrizione del link',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

            if (strlen($f29_Description) > 1000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La descrizione del link deve essere inferiore ai 1000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f29_accept = false;
            }

            if (strlen($f29_Label) > 127) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'etichetta del link deve essere inferiore ai 127 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f29_accept = false;
            }

            if ($f29_accept === true) {
                $linkItem = new Link(0, $id_canale, $user->getId(),
                        $f29_URI, $f29_Label, $f29_Description);
                $linkItem->insertLink();
                $canale->setUltimaModifica(time(), true);

                return 'success';
            }

        }

        $template->assign('f29_URI', $f29_URI);
        $template->assign('f29_Label', $f29_Label);
        $template->assign('f29_Description', $f29_Description);

        //$this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));
        return 'default';

    }
}
