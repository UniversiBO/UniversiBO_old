<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

use \Error;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\InfoDidattica;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;

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
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $this->sessionUser->getIdUser(),
                            'msg' => 'Il tipo canale richiesto non corrisponde al comando selezionato',
                            'file' => __FILE__, 'line' => __LINE__));
        }
    }

    public function execute()
    {
        $session_user = $this->get('security.context')->getToken()->getUser();
        $session_user_groups = $session_user->getGroups();
        $id_canale = $this->getRequestIdCanale();
        $insegnamento = $this->getRequestCanale();

        $user_ruoli = $session_user->getRuoli();

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

        $template->assign('ins_ContattoDocenteUri', '');
        $template->assign('ins_infoDidEditUri', '');
        if ($session_user->isAdmin()
                || (array_key_exists($id_canale, $user_ruoli)
                        && $user_ruoli[$id_canale]->isReferente())) {
            $template
                    ->assign('ins_infoDidEdit',
                            '/?do=InfoDidatticaEdit&id_canale='
                                    . $id_canale);
            if ($session_user->isAdmin() || $session_user->isCollaboratore())
                if (!$contatto) {
                    $template
                            ->assign('ins_ContattoDocenteUri',
                                    '/?do=ContattoDocenteAdd&cod_doc='
                                            . $coddoc . '&id_canale='
                                            . $id_canale);
                    $template
                            ->assign('ins_ContattoDocente',
                                    'Crea il contatto di questo docente');
                } else {
                    $template
                            ->assign('ins_ContattoDocenteUri',
                                    '/?do=ShowContattoDocente&cod_doc='
                                            . $coddoc . '&id_canale='
                                            . $id_canale);
                    $template
                            ->assign('ins_ContattoDocente',
                                    'Visualizza lo stato di questo docente');
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
            $obiettivi = '[url=/?do=ShowInfoDidattica&id_canale='
                    . $id_canale . '#obiettivi]Obiettivi del corso[/url]';

        if ($info_didattica->getProgrammaLink() == ''
                && $info_didattica->getProgramma() == '')
            $programma = 'Programma d\'esame';
        elseif ($info_didattica->getProgrammaLink() != ''
                && $info_didattica->getProgramma() == '')
            $programma = '[url=' . $info_didattica->getProgrammaLink()
                    . ']Programma d\'esame[/url]';
        else
            $programma = '[url=/?do=ShowInfoDidattica&id_canale='
                    . $id_canale . '#programma]Programma d\'esame[/url]';

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
            $materiale = '[url=/?do=ShowInfoDidattica&id_canale='
                    . $id_canale
                    . '#modalita]Materiale didattico e
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
            $modalita = '[url=/?do=ShowInfoDidattica&id_canale='
                    . $id_canale . '#modalita]Modalità d\'esame[/url]';

        if ($info_didattica->getAppelliLink() == ''
                && $info_didattica->getAppelli() == '')
            $appelli = 'Appelli d\'esame';
        elseif ($info_didattica->getAppelliLink() != ''
                && $info_didattica->getAppelli() == '')
            $appelli = '[url=' . $info_didattica->getAppelliLink()
                    . ']Appelli d\'esame[/url]';
        else
            $appelli = '[url=/?do=ShowInfoDidattica&id_canale='
                    . $id_canale . '#appelli]Appelli d\'esame[/url]';

        $orario = '[url=#]Orario delle lezioni[/url]';

        $forum = 'Forum';
        if ($insegnamento->getServizioForum()) {
            $forumApi = $this->getContainer()->get('universibo_legacy.forum.api');
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

        $template->assignUnicode('ins_tabella', $tpl_tabella);

        $template->assign('ins_title', $insegnamento->getTitolo());

        $this->executePlugin('ShowNewsLatest', array('num' => 5));
        $this->executePlugin('ShowLinks', array('num' => 12));
        $this->executePlugin('ShowFileTitoli', array());
        $this->executePlugin('ShowFileStudentiTitoli', array('num' => 12));

        return 'default';
    }

}
