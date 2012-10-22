<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;
use Universibo\Bundle\LegacyBundle\Entity\InfoDidattica;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * ShowCdl: mostra un corso di laurea
 * Mostra i collegamenti a tutti gli insegnamenti attivi nel corso di laurea
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowInsegnamento extends CanaleCommand
{
    /**
     * Inizializza il comando ShowInsegnamento ridefinisce l'initCommand() di CanaleCommand
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);

        $canale = $this->getRequestCanale();
        //var_dump($canale);

        if ($canale->getTipoCanale() != Canale::INSEGNAMENTO) {
            throw new NotFoundHttpException('Wrong channel type');
        }
    }

    public function execute()
    {
        $session_user = $this->get('security.context')->getToken()->getUser();
        $session_user_groups = $session_user instanceof User ? $session_user->getLegacyGroups() : 1;
        $id_canale = $this->getRequestIdCanale();
        $insegnamento = $this->getRequestCanale();

        $user_ruoli = $session_user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        // ??
        $insegnamento->getTitolo();
        //var_dump($insegnamento);

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        //		echo "qua\n";
        $array_prg = $insegnamento->getElencoAttivitaPadre();
        //		var_dump($prg); die;

        $coddoc = $array_prg[0]->getCodDoc();
        //		var_dump($coddoc); die;

        $contatto = ContattoDocente::getContattoDocente($coddoc);

        $context = $this->get('security.context');
        $router = $this->get('router');

        $template->assign('ins_ContattoDocenteUri', '');
        $template->assign('ins_infoDidEditUri', '');
        if ($context->isGranted('ROLE_ADMIN') || (array_key_exists($id_canale, $user_ruoli)
                        && $user_ruoli[$id_canale]->isReferente())) {
            $template->assign('ins_infoDidEdit', $router->generate('universibo_legacy_insegnamento_info_edit', array('id_canale' => $id_canale)));
            if ($context->isGranted('ROLE_ADMIN') ||$context->isGranted('ROLE_COLLABORATOR'))
                if (!$contatto) {
                    $template->assign('ins_ContattoDocenteUri', $router->generate('universibo_legacy_contact_professor_add', array('id_canale' => $id_canale, 'cod_doc' => $coddoc)));
                    $template->assign('ins_ContattoDocente', 'Crea il contatto di questo docente');
                } else {
                    $template->assign('ins_ContattoDocenteUri', $router->generate('universibo_legacy_contact_professor', array('id_canale' => $id_canale, 'cod_doc' => $coddoc)));
                    $template->assign('ins_ContattoDocente', 'Visualizza lo stato di questo docente');
                }
        } else {
            $template->assign('ins_infoDidEdit', false);
        }
        $info_didattica = InfoDidattica::retrieveInfoDidattica($id_canale);
        //var_dump($info_didattica);

        $template
                ->assign('ins_langHomepageAlternativaLink',
                        'Le informazioni del corso posso essere consultate anche alla pagina');
        $template
                ->assign('ins_homepageAlternativaLink',
                        $info_didattica->getHomepageAlternativaLink());

        if ($info_didattica->getObiettiviEsameLink() == ''
                && $info_didattica->getObiettiviEsame() == '')
            $obiettivi = 'Obiettivi del corso';
        elseif ($info_didattica->getObiettiviEsameLink() != ''
                && $info_didattica->getObiettiviEsame() == '')
            $obiettivi = '[url=' . $info_didattica->getObiettiviEsameLink()
                    . ']Obiettivi del corso[/url]';
        else
            $obiettivi = '[url='.
        $router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $id_canale)).
                '#obiettivi]Obiettivi del corso[/url]';

        if ($info_didattica->getProgrammaLink() == ''
                && $info_didattica->getProgramma() == '')
            $programma = 'Programma d\'esame';
        elseif ($info_didattica->getProgrammaLink() != ''
                && $info_didattica->getProgramma() == '')
            $programma = '[url=' . $info_didattica->getProgrammaLink()
                    . ']Programma d\'esame[/url]';
        else
            $programma = '[url='.$router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $id_canale)). '#programma]Programma d\'esame[/url]';

        if ($info_didattica->getTestiConsigliatiLink() == ''
                && $info_didattica->getTestiConsigliati() == '') {
            $materiale = 'Materiale didattico e
testi consigliati';
        } elseif ($info_didattica->getTestiConsigliatiLink() != ''
                && $info_didattica->getTestiConsigliati() == '')
            $materiale = '[url=' . $info_didattica->getTestiConsigliatiLink()
                    . ']Materiale didattico e
testi consigliati[/url]';
        else
            $materiale = '[url='.$router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $id_canale)).  '#modalita]Materiale didattico e
testi consigliati[/url]';
        '';

        if ($info_didattica->getModalitaLink() == ''
                && $info_didattica->getModalita() == '')
            $modalita = 'Modalità d\'esame';
        elseif ($info_didattica->getModalitaLink() != ''
                && $info_didattica->getModalita() == '')
            $modalita = '[url=' . $info_didattica->getModalitaLink()
                    . ']Modalità d\'esame[/url]';
        else
            $modalita = '[url='.$router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $id_canale)). '#modalita]Modalità d\'esame[/url]';

        if ($info_didattica->getAppelliLink() == ''
                && $info_didattica->getAppelli() == '')
            $appelli = 'Appelli d\'esame';
        elseif ($info_didattica->getAppelliLink() != ''
                && $info_didattica->getAppelli() == '')
            $appelli = '[url=' . $info_didattica->getAppelliLink()
                    . ']Appelli d\'esame[/url]';
        else
            $appelli = '[url='.$router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $id_canale)). '#appelli]Appelli d\'esame[/url]';

        $orario = '[url=#]Orario delle lezioni[/url]';

        $forum = 'Forum';
        if ($insegnamento->getServizioForum()) {
            $forumApi = $this->getContainer()->get('universibo_forum.router');
            $link = $forumApi->getForumUri($insegnamento->getForumForumId());
            $forum = '[url=' . $link . ']Forum[/url]';
        }

        $tpl_tabella[] = $obiettivi;
        $tpl_tabella[] = $programma;
        $tpl_tabella[] = $materiale;
        $tpl_tabella[] = $modalita;
        $tpl_tabella[] = $appelli;
        // per rimettere l'orario decommentare qui
        //$tpl_tabella[] = $orario;
        $tpl_tabella[] = $forum;

        $template->assign('ins_tabella', $tpl_tabella);

        $template->assign('ins_title', $insegnamento->getTitolo());

        $this->executePlugin('ShowNewsLatest', array('num' => 5));
        $this->executePlugin('ShowLinks', array('num' => 12));
        $this->executePlugin('ShowFileTitoli', array());
        $this->executePlugin('ShowFileStudentiTitoli', array('num' => 12));

        return 'default';
    }

}
