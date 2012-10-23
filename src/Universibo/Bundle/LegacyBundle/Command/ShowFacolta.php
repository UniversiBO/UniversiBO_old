<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
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

        $this->ensureChannelType(Canale::FACOLTA);
    }

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $forumRouter = $this->getContainer()->get('universibo_forum.router');
        $router = $this->get('router');

        //@todo fatto sopra
        $facolta = $this->getRequestCanale();

        $elencoCdl = Cdl::selectCdlElencoFacolta($facolta->getCodiceFacolta());

        $num_cdl = count($elencoCdl);
        $cdlType = NULL;
        $fac_listCdlType = array();
        $default_anno_accademico = $this->frontController
                ->getAppSetting('defaultAnnoAccademico');
        $session_user = $this->get('security.context')->getToken()->getUser();
        $session_user_groups = $session_user instanceof User ? $session_user->getLegacyGroups() : 1;

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
                                != false) ? $forumRouter
                                        ->getForumUri(
                                                $elencoCdl[$i]
                                                        ->getForumForumId())
                                : '',
                        'link' => $router->generate('universibo_legacy_cdl', array('id_canale' => $elencoCdl[$i]->getIdCanale())));
            }
        }

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
