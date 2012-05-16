<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;

use UniversiBO\Bundle\LegacyBundle\Entity\InfoDidattica;

use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

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

class ShowInfoDidattica extends UniversiboCommand
{

    function execute()
    {
        $frontcontroller = & $this->getFrontController();
        $template = & $frontcontroller->getTemplateEngine();

        $krono = & $frontcontroller->getKrono();
        $user = & $this->getSessionUser();
        $user_ruoli = & $user->getRuoli();

        if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
            Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'id del canale richiesto non e` valido', 'file' => __FILE__, 'line' => __LINE__));

        $id_canale = $_GET['id_canale'];
        $session_user = $this->getSessionUser();

        $info_didattica = InfoDidattica::retrieveInfoDidattica($id_canale);
        $insegnamento = Canale::retrieveCanale($id_canale);
        //var_dump($info_didattica);

        $homepageAlternativaLink = $info_didattica->getHomepageAlternativaLink();

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

        $template->assign('infoDid_title', $insegnamento->getTitolo() );
        $template->assign('infoDid_langHomepageAlternativaLink', 'Le informazioni del corso posso essere consultate anche alla pagina' );
        $template->assign('infoDid_homepageAlternativaLink', $homepageAlternativaLink );

        $template->assign('infoDid_langObiettiviInfo', 'Obiettivi del corso' );
        $template->assign('infoDid_langObiettiviLink', 'Gli obiettivi del corso posso essere consultati anche a questo link' );
        $template->assign('infoDid_obiettiviLink', $obiettiviLink );
        $template->assign('infoDid_obiettiviInfo', $obiettiviInfo );

        $template->assign('infoDid_langProgrammaInfo', 'Programma d\'esame' );
        $template->assignUnicode('infoDid_langProgrammaLink', 'Il programma d\'esame può essere consultato anche a questo link' );
        $template->assign('infoDid_programmaLink', $programmaLink );
        $template->assign('infoDid_programmaInfo', $programmaInfo );

        $template->assign('infoDid_langMaterialeInfo', 'Materiale didattico e testi consigliati' );
        $template->assign('infoDid_langMaterialeLink', 'Il Materiale didattico e testi consigliati possono essere consultati anche a questo link' );
        $template->assign('infoDid_materialeLink', $materialeLink );
        $template->assign('infoDid_materialeInfo', $materialeInfo );

        $template->assignUnicode('infoDid_langModalitaInfo', 'Modalità d\'esame' );
        $template->assignUnicode('infoDid_langModalitaLink', 'Le modalità d\'esame possono essere consultati anche a questo link');
        $template->assign('infoDid_modalitaLink', $modalitaLink );
        $template->assign('infoDid_modalitaInfo', $modalitaInfo );

        $template->assign('infoDid_langAppelliInfo', 'Appelli d\'esame' );
        $template->assign('infoDid_langAppelliLink', 'Gli appelli d\'esame possono essere consultati anche a questo link');
        $template->assign('infoDid_appelliLink', $appelliLink );
        $template->assign('infoDid_appelliInfo', $appelliInfo );
        $template->assignUnicode('infoDid_langAppelliUniwex', 'Ci scusiamo con gli utenti ma al momento non è più possibile visualizzare le informazioni riguardanti gli appelli d\'esame riportati su Uniwex');


        //$this->executePlugin('ShowNewsLatest', array( 'num' => 5  ));
        //$this->executePlugin('ShowFileTitoli', array());
        return 'default';
    }

}

?>
