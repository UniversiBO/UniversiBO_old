<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
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
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class MyUniversiBOAdd extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $utente = $this->get('security.context')->getToken()->getUser();

        $id_canale = $this->getRequest()->attributes->get('id_canale');
        $canale = $this->get('universibo_legacy.repository.canale2')->find($id_canale);

        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Channel not found');
        }

        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $template->assign('common_canaleURI', $channelRouter->generate($canale));
        $template->assign('common_langCanaleNome', $canale->getNome());

        $ruoli = $utente instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($utente->getId()) : array();
        $this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));

        //		if()
        //		{
        //
        //			return 'success';
        //		}
        //		else
        //		{

        $f15_livelli_notifica = Ruolo::getLivelliNotifica();
        $f15_livello_notifica = $utente->getNotifications();
        $f15_nome = (array_key_exists($id_canale, $ruoli)) ? $ruoli[$id_canale]
                        ->getNome() : '';

        $f15_accept = false;
        if (array_key_exists('f15_submit', $_POST)) {

            $f15_accept = true;
            if (!array_key_exists('f15_nome', $_POST)
                    || !array_key_exists('f15_livello_notifica', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $utente->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f15_accept = false;
            }

            if (!array_key_exists($_POST['f15_livello_notifica'],
                    $f15_livelli_notifica)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $utente->getId(),
                                'msg' => 'Il livello di notifica scelto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f15_accept = false;
            } else
                $f15_livello_notifica = $_POST['f15_livello_notifica'];

            if (strlen($_POST['f15_nome']) > 60) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $utente->getId(),
                                'msg' => 'Il nome scelto deve essere inferiore ai 60 caratteri',
                                'file' => __FILE__, 'line' => __LINE__));
                $f15_accept = false;
            } else
                $f15_nome = $_POST['f15_nome'];

            if ($f15_accept == true) {

                if (array_key_exists($id_canale, $ruoli)) {
                    $ruolo = $ruoli[$id_canale];
                    $ruolo->updateNome($f15_nome);
                    $ruolo->updateTipoNotifica($f15_livello_notifica);
                    $ruolo->setMyUniversiBO(true);

                    $ruolo->updateRuolo();
                } else {
                    $nascosto = false;
                    $ruolo = new Ruolo($utente->getId(), $id_canale,
                            $f15_nome, time(), false, false, true,
                            $f15_livello_notifica, $nascosto);
                    $ruolo->insertRuolo();
                }

                if ($canale->getTipoCanale() == Canale::INSEGNAMENTO) {
                    //trover? un modo per ottenere il cdl! lo giuro!!!
                }

                return 'success';
            }

        }

        $template->assign('f15_nome', $f15_nome);
        $template->assign('f15_livelli_notifica', $f15_livelli_notifica);
        //var_dump($f15_livello_notifica);
        $template->assign('f15_livello_notifica', $f15_livello_notifica);

        return 'default';
    }
}
