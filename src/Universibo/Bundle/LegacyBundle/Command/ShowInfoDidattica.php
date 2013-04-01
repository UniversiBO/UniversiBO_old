<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\InfoDidattica;

/**
 * ShowCdl: mostra un corso di laurea
 * Mostra i collegamenti a tutti gli insegnamenti attivi nel corso di laurea
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowInfoDidattica extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $id_canale = $this->getRequest()->attributes->get('id_canale');
        $canale = $this->get('universibo_legacy.repository.canale')->find($id_canale);

        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Channel not found');
        }

        $session_user = $user;

        $info_didattica = InfoDidattica::retrieveInfoDidattica($id_canale);
        $insegnamento = Canale::retrieveCanale($id_canale);
        //var_dump($info_didattica);

        $homepageAlternativaLink = $info_didattica
                ->getHomepageAlternativaLink();

        $obiettiviLink = $info_didattica->getObiettiviEsameLink();
        $obiettiviInfo = $info_didattica->getObiettiviEsame();

        $programmaLink = $info_didattica->getProgrammaLink();
        $programmaInfo = $info_didattica->getProgramma();

        $materialeLink = $info_didattica->getTestiConsigliatiLink();
        $materialeInfo = $info_didattica->getTestiConsigliati();

        $modalitaLink = $info_didattica->getModalitaLink();
        $modalitaInfo = $info_didattica->getModalita();

        //$appelli = '';
        $appelliLink = $info_didattica->getAppelliLink();
        $appelliInfo = $info_didattica->getAppelli();

        $template->assign('infoDid_title', $insegnamento->getTitolo());
        $template
                ->assign('infoDid_langHomepageAlternativaLink',
                        'Le informazioni del corso posso essere consultate anche alla pagina');
        $template
                ->assign('infoDid_homepageAlternativaLink',
                        $homepageAlternativaLink);

        $template->assign('infoDid_langObiettiviInfo', 'Obiettivi del corso');
        $template
                ->assign('infoDid_langObiettiviLink',
                        'Gli obiettivi del corso posso essere consultati anche a questo link');
        $template->assign('infoDid_obiettiviLink', $obiettiviLink);
        $template->assign('infoDid_obiettiviInfo', $obiettiviInfo);

        $template->assign('infoDid_langProgrammaInfo', 'Programma d\'esame');
        $template
                ->assign('infoDid_langProgrammaLink',
                        'Il programma d\'esame può essere consultato anche a questo link');
        $template->assign('infoDid_programmaLink', $programmaLink);
        $template->assign('infoDid_programmaInfo', $programmaInfo);

        $template
                ->assign('infoDid_langMaterialeInfo',
                        'Materiale didattico e testi consigliati');
        $template
                ->assign('infoDid_langMaterialeLink',
                        'Il Materiale didattico e testi consigliati possono essere consultati anche a questo link');
        $template->assign('infoDid_materialeLink', $materialeLink);
        $template->assign('infoDid_materialeInfo', $materialeInfo);

        $template
                ->assign('infoDid_langModalitaInfo', 'Modalità d\'esame');
        $template
                ->assign('infoDid_langModalitaLink',
                        'Le modalità d\'esame possono essere consultati anche a questo link');
        $template->assign('infoDid_modalitaLink', $modalitaLink);
        $template->assign('infoDid_modalitaInfo', $modalitaInfo);

        $template->assign('infoDid_langAppelliInfo', 'Appelli d\'esame');
        $template
                ->assign('infoDid_langAppelliLink',
                        'Gli appelli d\'esame possono essere consultati anche a questo link');
        $template->assign('infoDid_appelliLink', $appelliLink);
        $template->assign('infoDid_appelliInfo', $appelliInfo);
        $template
                ->assign('infoDid_langAppelliUniwex',
                        'Ci scusiamo con gli utenti ma al momento non è più possibile visualizzare le informazioni riguardanti gli appelli d\'esame riportati su Uniwex');

        //$this->executePlugin('ShowNewsLatest', array( 'num' => 5  ));
        //$this->executePlugin('ShowFileTitoli', array());
        return 'default';
    }

}
