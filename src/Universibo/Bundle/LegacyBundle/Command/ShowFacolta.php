<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

use \Error;

use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * ShowFacolta: mostra una facolt?
 * Mostra i collegamenti a tutti i corsi di laurea attivi nella facolt?
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowFacolta extends CanaleCommand
{

    /**
     * Inizializza il comando ShowFacolta ridefinisce l'initCommand() di CanaleCommand
     */
    public function initCommand(FrontController $frontController)
    {

        parent::initCommand($frontController);

        $canale = $this->getRequestCanale();
        //var_dump($canale);

        if ($canale->getTipoCanale() != Canale::FACOLTA)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $this->sessionUser->getIdUser(),
                            'msg' => 'Il tipo canale richiesto non corrisponde al comando selezionato',
                            'file' => __FILE__, 'line' => __LINE__));

    }

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $forum = $this->getContainer()->get('universibo_legacy.forum.api');

        //@todo fatto sopra
        $facolta = $this->getRequestCanale();

        $elencoCdl = Cdl::selectCdlElencoFacolta($facolta->getCodiceFacolta());

        $num_cdl = count($elencoCdl);
        $cdlType = NULL;
        $fac_listCdlType = array();
        $default_anno_accademico = $this->frontController
                ->getAppSetting('defaultAnnoAccademico');
        $session_user = $this->getSessionUser();
        $session_user_groups = $session_user->getGroups();

        //2 livelli di innesstamento facolta/tipocdl/cdl
        for ($i = 0; $i < $num_cdl; $i++) {
            if ($elencoCdl[$i]->isGroupAllowed($session_user_groups)) {
                if ($cdlType != $elencoCdl[$i]->getCategoriaCdl()) {
                    $cdlType = $elencoCdl[$i]->getCategoriaCdl();
                    switch ($cdlType) {
                    case 1:
                        $name = 'CORSI DI LAUREA TRIENNALE';
                        break;
                    case 2:
                        $name = 'CORSI DI LAUREA SPECIALISTICA';
                        break;
                    case 3:
                        $name = 'CORSI DI LAUREA VECCHIO ORDINAMENTO';
                        break;
                    case 4:
                        $name = 'CORSI DI LAUREA TRIENNALE NUOVO ORDINAMENTO';
                        break;
                    case 5:
                        $name = 'CORSI DI LAUREA MAGISTRALE';
                        break;
                    case 6:
                        $name = 'CORSI DI LAUREA MAGISTRALE A CICLO UNICO';
                        break;
                    case 7:
                        $name = 'CORSI DI LAUREA SPECIALISTICA A CICLO UNICO';
                        break;
                    }
                    if (!array_key_exists($cdlType, $fac_listCdlType))
                        $fac_listCdlType[$cdlType] = array('cod' => $cdlType,
                                'name' => $name, 'list' => array());
                }
                $fac_listCdlType[$cdlType]['list'][] = array(
                        'cod' => $elencoCdl[$i]->getCodiceCdl(),
                        'name' => $elencoCdl[$i]->getNome(),
                        'forumUri' => ($elencoCdl[$i]->getServizioForum()
                                != false) ? $forum
                                        ->getForumUri(
                                                $elencoCdl[$i]
                                                        ->getForumForumId())
                                : '',
                        'link' => 'v2.php?do=ShowCdl&amp;id_canale='
                                . $elencoCdl[$i]->getIdCanale()); //.'&amp;anno_accademico='.$default_anno_accademico
            }
        }
        //var_dump($fac_listCdlType);

        /*  esempio:
                $fac_listCdl = array(); //cat := lista di cdl
                $fac_listCdl[] = array('cod' => '0048', 'name' => 'ELETTRONICA', 'link' => 'v2.php?do=ShowCDL&amp;id_cdl=0048&amp;anno_accademico=2003');
                $fac_listCdl[] = array('cod' => '0049', 'name' => 'GESTIONALE', 'link' => 'v2.php?do=ShowCDL&amp;id_cdl=0049&amp;anno_accademico=2003');
                $fac_listCdl[] = array('cod' => '0050', 'name' => 'DEI PROCESSI GESTIONALI', 'link' => 'v2.php?do=ShowCDL&amp;id_cdl=0050&amp;anno_accademico=2003');
                $fac_listCdl[] = array('cod' => '0051', 'name' => 'INFORMATICA', 'link' => 'v2.php?do=ShowCDL&amp;id_cdl=0051&amp;anno_accademico=2003');

                $fac_listCdlType = array(); //fac := lista categorie degli anni di cdl
                $fac_listCdlType[] = array('cod' => '1', 'name' => 'Lauree Triennali/Primo Livello', 'list' => $fac_listCdl);
                $fac_listCdlType[] = array('cod' => '2', 'name' => 'Lauree Specialistiche', 'list' => $fac_listCdl);
                $fac_listCdlType[] = array('cod' => '3', 'name' => 'Lauree Vecchio Ordinamento', 'list' => $fac_listCdl);
         */
        // ordinamento delle categorie
        $sortedFacList = array();
        foreach (array(4, 1, 2, 5, 7, 6, 3) as $n)
            if (isset($fac_listCdlType[$n]))
                $sortedFacList[] = $fac_listCdlType[$n];

        $template->assign('fac_list', $sortedFacList);
        $template->assign('fac_langFac', 'FACOLTA\'');
        $template->assign('fac_facTitle', $facolta->getTitolo());
        $template->assign('fac_langTitleAlt', 'corsi_di_laurea');
        $template->assign('fac_facName', $facolta->getNome());
        $template->assign('fac_facCodice', $facolta->getCodiceFacolta());
        $template->assign('fac_facLink', 'http://' . $facolta->getUri());
        $template
                ->assign('fac_langList',
                        'Elenco corsi di laurea attivati su UniversiBO');

        $param = array('num' => 4);
        $this->executePlugin('ShowNewsLatest', $param);
        $this->executePlugin('ShowLinks', array('num' => 12));

        return 'default';
    }

}
