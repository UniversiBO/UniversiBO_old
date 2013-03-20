<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Entity\Canale;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Facolta;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
/**
 * ShowCdl: mostra un corso di laurea
 * Mostra i collegamenti a tutti gli insegnamenti attivi nel corso di laurea
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowCdl extends CanaleCommand
{
    /**
     * Inizializza il comando ShowCdl ridefinisce l'initCommand() di CanaleCommand
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);
    }

    public function execute()
    {
        $check = $this->ensureChannelType(Canale::CDL);

        if ($check !== null) {
            return $check;
        }

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $context = $this->get('security.context');
        $router = $this->get('router');

        //@todo fatto sopra
        $cdl = $this -> getRequestCanale();

        $prgRepo = $this->get('universibo_legacy.repository.programma');

        $minYear = $prgRepo->findMinAcademicYear($cdl->getCodiceCdl());
        $maxYear = $prgRepo->findMaxAcademicYear($cdl->getCodiceCdl());
        $request = $this->getRequest();
        $academicYear = $request->get('anno_accademico', $maxYear);

        if ($maxYear !== null) {
            $elencoPrgAttDid = $prgRepo->findByCdlAndYear($cdl-> getCodiceCdl(), $academicYear);
        } else {
            $academicYear = $frontcontroller->getAppSetting('defaultAnnoAccademico');
            $minYear = $maxYear = $academicYear;
            $elencoPrgAttDid = array();
        }

        if (!preg_match('/^([0-9]{4})$/', $academicYear) ||
            $academicYear > $maxYear || $academicYear < $minYear) {
            throw new NotFoundHttpException('No subjects in academic year');
        }

        $num_ins = count($elencoPrgAttDid);
        $insAnnoCorso  = NULL;   //ultimo anno dell'insegnamento precedente
        $insCiclo = NULL;   //ultimo ciclo dell'insegnamento precedente
        $session_user = $this->get('security.context')->getToken()->getUser();
        $session_user_groups =  $session_user instanceof User ? $session_user->getLegacyGroups() : 1;
        $cdl_listIns = array();

        $forum = $this->getContainer()->get('universibo_forum.router');
        //3 livelli di innestamento cdl/anno_corso/ciclo/insegnamento
        for ($i=0; $i < $num_ins; $i++) {
            $tempPrgAttDid = $elencoPrgAttDid[$i];
            if ($tempPrgAttDid->isGroupAllowed( $session_user_groups )) {
                if ( $insAnnoCorso != $tempPrgAttDid->getAnnoCorsoUniversibo() ) {
                    $insAnnoCorso = $tempPrgAttDid->getAnnoCorsoUniversibo();
                    $insCiclo = NULL; //$elencoPrgAttDid[$i]->getTipoCiclo();

                    $cdl_listIns[$insAnnoCorso] = array('anno' => $insAnnoCorso, 'name' => 'anno '.$insAnnoCorso, 'list' => array() );
                }

                if ( $insCiclo != $tempPrgAttDid->getTipoCiclo() ) {
                    $insCiclo = $tempPrgAttDid->getTipoCiclo();

                    $cdl_listIns[$insAnnoCorso]['list'][$insCiclo] = array('ciclo' => $insCiclo, 'name' => 'Ciclo '.$insCiclo, 'list' => array() );
                }
                $allowEdit = ($context->isGranted('ROLE_ADMIN') || $context->isGranted('ROLE_MODERATOR') );
                $fac = Facolta::selectFacoltaCodice($cdl->getCodiceFacoltaPadre());
                $editUri = (!$tempPrgAttDid->isSdoppiato())?
                DidatticaGestione::getEditUrl($tempPrgAttDid->getIdCanale(),$cdl->getIdCanale(), $fac->getIdCanale()) :
                DidatticaGestione::getEditUrl($tempPrgAttDid->getIdCanale(),$cdl->getIdCanale(), $fac->getIdCanale(),$tempPrgAttDid->getIdSdop());

                $cdl_listIns[$insAnnoCorso]['list'][$insCiclo]['list'][] =
                array( 'name' => $tempPrgAttDid->getNome(),
                        'nomeDoc' => $tempPrgAttDid->getNomeDoc(),
                        'uri' => $router->generate('universibo_legacy_insegnamento', array('id_canale' => $tempPrgAttDid->getIdCanale())),
                        'editUri' => ($allowEdit)?$editUri:'',
                        'forumUri' =>($tempPrgAttDid->getServizioForum() != false) ? $forum->getForumUri($tempPrgAttDid->getForumForumId()) : '' );
            }
        }
        //var_dump($fac_listCdlType);
        $template -> assign('cdl_list', $cdl_listIns);

        $template -> assign('cdl_langCdl', 'CORSO DI LAUREA');
        $template -> assign('cdl_cdlTitle', $cdl->getTitolo());
        $template -> assign('cdl_langTitleAlt', 'Corsi di Laurea');
        $template -> assign('cdl_cdlName', $cdl->getNome());
        $template -> assign('cdl_cdlCodice', $cdl->getCodiceCdl());

        $template -> assign('cdl_langYear', 'anno accademico' );

        $response = $this->forward('UniversiboWebsiteBundle:Didactics:academicYear', array(
                'min' => $minYear,
                'max' => $maxYear,
                'current' => $academicYear,
                'route' => 'universibo_legacy_cdl',
                'params' => array('id_canale' => $cdl->getIdCanale())
        ));

        $template->assign('cdl_yearBox', $response->getContent());

        $template -> assign('cdl_langList', 'Elenco insegnamenti attivati su UniversiBO');
        $template -> assign('cdl_langGoToForum', 'Link al forum');

        $this->executePlugin('ShowNewsLatest', array( 'num' => 4  ));
        $this->executePlugin('ShowLinks', array( 'num' => 12 ) );

        return 'default';
    }
}
