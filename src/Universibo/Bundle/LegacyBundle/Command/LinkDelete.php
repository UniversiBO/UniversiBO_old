<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * LinkDelete: elimina un link, mostra il form e gestisce la cancellazione
 *
 *
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

        $router = $this->get('router');
        $template->assign('common_canaleURI', $router->generate('universibo_legacy_myuniversibo'));
        $template->assign('common_langCanaleNome', 'indietro');

        $user = $this->get('security.context')->getToken()->getUser();

        $referente = false;
        $moderatore = false;

        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $linkId = $this->getRequest()->attributes->get('id_link');
        $link = $this->get('universibo_legacy.repository.links.link')->find($linkId);
        if ($link === false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => 'Il link richiesto non e` presente su database',
                            'file' => __FILE__, 'line' => __LINE__));

        $autore = ($user->getId() == $link->getIdUtente());

        $channelId = $this->getRequest()->attributes->get('id_canale');

        if ($channelId !== null) {
            if (!preg_match('/^([0-9]{1,9})$/', $channelId)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
            }

            $canale = $this->get('universibo_legacy.repository.canale2')->find($channelId);
            if (!$canale instanceof Canale) {
                throw new NotFoundHttpException('Channel not found');
            }

            if ($canale->getServizioLinks() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il servizio links e` disattivato',
                                'file' => __FILE__, 'line' => __LINE__));

            $channelRouter = $this->get('universibo_legacy.routing.channel');
            $template->assign('common_canaleURI', $channelRouter->generate($canale));
            $template
                    ->assign('common_langCanaleNome', 'a '
                            . $canale->getTitolo());

            if (array_key_exists($channelId, $user_ruoli)) {
                $ruolo = $user_ruoli[$channelId];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

            //controllo coerenza parametri
            $canale_link = $link->getIdCanale();
            if ($channelId != $canale_link)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore)))
                throw new AccessDeniedHttpException('Not allowed to delete link');
        } elseif (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $autore))
            throw new AccessDeniedHttpException('Not allowed to delete link');

        //postback

        if (array_key_exists('f30_submit', $_POST)) {
            if ($link->deleteLink()) {
                $template
                        ->assign('f30_langAction',
                                "Il link è stato eliminato correttamente");

                return 'success';
            }
        }

        //$this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));
        $this
                ->executePlugin('ShowLink',
                        array('id_link' => $linkId,
                                'id_canale' => $channelId));

        return 'default';
    }
}
