<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Ruolo;

/**
 * ShowMyUniversiBO is an extension of UniversiboCommand class.
 *
 * Mostra la MyUniversiBO dell'utente loggato, con le ultime 5 notizie e
 * gli ultimi 5 files presenti nei canali da lui aggiunti...
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class MyUniversiBOEdit extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $utente = $this->get('security.context')->getToken()->getUser();
        $router = $this->get('router');

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $utente->getId(),
                            'msg' => "non e` permesso ad utenti non registrati eseguire questa operazione.\n La sessione potrebbe essere scaduta",
                            'file' => __FILE__, 'line' => __LINE__));

        $id_canale = $this->getRequest()->attributes->get('id_canale');
        $canale = $this->get('universibo_legacy.repository.canale2')->find($id_canale);

        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Channel not found');
        }

        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $template->assign('common_canaleURI', $channelRouter->generate($canale));
        $template->assign('common_langCanaleNome', $canale->getNome());

        $ruoli = $utente instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($utente->getId()) : array();
        if (!array_key_exists($id_canale, $ruoli))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $utente->getId(),
                            'msg' => 'Il ruolo richiesto non e` presente',
                            'file' => __FILE__, 'line' => __LINE__));

        $ruolo = $ruoli[$id_canale];
        $this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));

        if (array_key_exists($id_canale, $ruoli)) {
            $f19_livelli_notifica = Ruolo::getLivelliNotifica();
            $f19_livello_notifica = $ruolo->getTipoNotifica();
            $f19_nome = $ruolo->getNome();

            $f19_accept = false;
            if (array_key_exists('f19_submit', $_POST)) {

                $f19_accept = true;
                if (!array_key_exists('f19_nome', $_POST)
                        || !array_key_exists('f19_livello_notifica', $_POST)) {
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $utente->getId(),
                                    'msg' => 'Il form inviato non e` valido',
                                    'file' => __FILE__, 'line' => __LINE__));
                    $f19_accept = false;
                }

                if (!array_key_exists($_POST['f19_livello_notifica'],
                        $f19_livelli_notifica)) {
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $utente->getId(),
                                    'msg' => 'Il livello di notifica scelto non e` valido',
                                    'file' => __FILE__, 'line' => __LINE__));
                    $f19_accept = false;
                } else
                    $f19_livello_notifica = $_POST['f19_livello_notifica'];

                if (strlen($_POST['f19_nome']) > 60) {
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $utente->getId(),
                                    'msg' => 'Il nome scelto deve essere inferiore ai 60 caratteri',
                                    'file' => __FILE__, 'line' => __LINE__));
                    $f19_accept = false;
                } else
                    $f19_nome = $_POST['f19_nome'];

                if ($f19_accept == true) {
                    //$nascosto = false;
                    //$ruolo = new Ruolo($utente->getId(), $id_canale,  , time(), false, false, true, $f19_livello_notifica, $nascosto);

                    $ruolo->updateNome($f19_nome);
                    $ruolo->updateTipoNotifica($f19_livello_notifica);

                    $ruolo->updateRuolo();
                    $canale = Canale::retrieveCanale($id_canale);
                    $template->assign('showUser', $router->generate('universibo_legacy_user', array('id_utente' => $utente->getId())));
                    if ($canale->getTipoCanale() == CANALE_INSEGNAMENTO) {
                        //troverò un modo per ottenere il cdl! lo giuro!!!
                        // ^ peccato che tu non ti sia firmato... SbiellONE
                    }

                    return 'success';
                }

            }

            $template->assign('f19_nome', $f19_nome);
            $template->assign('f19_livelli_notifica', $f19_livelli_notifica);
            $template->assign('f19_livello_notifica', $f19_livello_notifica);

            return 'default';

        } else {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $utente->getId(),
                            'msg' => 'Questa pagina non e` inserita nel tuo MyUniversiBO',
                            'file' => __FILE__, 'line' => __LINE__));
        }

    }

}
