<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Docente;

/**
 *Questa classe consente la visualizzazione e la possibile modifica
 *dei dati di un utente.
 *@author Daniele Tiles
 */

class ShowUser extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $context = $this->get('security.context');
        $current_user = $context->getToken()->getUser();
        $professorRepo = $this->get('universibo_legacy.repository.docente');

        $userId = $this->getRequest()->attributes->get('id_utente');
        $user = $this->get('universibo_website.repository.user')->find($userId);

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => 0,
                            'msg' => 'Le schede degli utenti sono visualizzabili solo se si e` registrati',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $currentUserId = $current_user->getId();

        if (!$user instanceof User || $user->isLocked() || !$user->isEnabled()) {
            throw new NotFoundHttpException('User not found');;
        }

        if (!$current_user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_PROFESSOR')
                && !$user->hasRole('ROLE_TUTOR')
                && $userId != $current_user->getId()) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => 'Non ti e` permesso visualizzare la scheda dell\'utente',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $router = $this->get('router');
        $channelRouter = $this->get('universibo_legacy.routing.channel');

        $arrayRuoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId());
        $canali = array();
        $arrayCanali = array();
        $keys = array_keys($arrayRuoli);
        foreach ($keys as $key) {
            $ruolo = $arrayRuoli[$key];
            if ($ruolo->isMyUniversibo()) {
                $canale = Canale::retrieveCanale($ruolo->getIdCanale());
                if ($canale->isGroupAllowed($current_user->getLegacyGroups())) {
                    $canali = array();
                    $canali['uri'] = $channelRouter->generate($canale);
                    $canali['tipo'] = $canale->getTipoCanale();
                    $canali['label'] = ($canale->getNome() != '') ? $canale
                                    ->getNome() : $canale
                                    ->getNomeMyUniversibo();
                    $canali['ruolo'] = ($ruolo->isReferente()) ? 'R'
                            : (($ruolo->isModeratore()) ? 'M' : 'none');
                    $canali['modifica'] = $router->generate('universibo_legacy_myuniversibo_edit', array('id_canale' => $ruolo->getIdCanale()));
                    $canali['rimuovi'] = $router->generate('universibo_legacy_myuniversibo_remove', array('id_canale' => $ruolo->getIdCanale()));
                    $arrayCanali[] = $canali;
                }
            }
        }
        usort($arrayCanali, array($this, '_compareMyUniversiBO'));
        $email = $user->getEmail();
        $template->assign('showUserLivelli',$this->get('universibo_legacy.translator.role_name')->translate($user->getRoles()));

        $template->assign('showUserNickname', $user->getUsername());
        $template->assign('showUserEmail', $email);
        $pos = strpos($email, '@');
        $firstPart = substr($email, 0, $pos);
        $secondPart = substr($email, $pos + 1, strlen($email) - $pos);
        $template->assign('showEmailFirstPart', $firstPart);
        $template->assign('showEmailSecondPart', $secondPart);
        $template->assign('showCanali', $arrayCanali);

        $stessi = $currentUserId == $userId;
        $template->assign('showDiritti', $stessi);

        $template->assign('showUser_UserHomepage', '');
        if ($user->hasRole('ROLE_PROFESSOR')) {
            $doc = $professorRepo->findByUserId($user->getId());
            $template->assign('showUser_UserHomepage', $doc->getHomepageDocente());
        }
        $template->assign('showSettings', $router->generate('universibo_legacy_settings'));

        return 'default';
    }

}
