<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;

use \Error;

use UniversiBO\Bundle\LegacyBundle\Entity\Facolta;
use UniversiBO\Bundle\LegacyBundle\App\ForumApi;
use UniversiBO\Bundle\LegacyBundle\App\CanaleCommand;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
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
class ShowCdl extends CanaleCommand
{
    /**
     * Inizializza il comando ShowCdl ridefinisce l'initCommand() di CanaleCommand
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);

        $canale = & $this->getRequestCanale();
        //var_dump($canale);

        if ($canale->getTipoCanale() != CANALE_CDL)
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => $this->sessionUser->getIdUser(), 'msg' => 'Il tipo canale richiesto non corrisponde al comando selezionato', 'file' => __FILE__, 'line' => __LINE__));
    }

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        //@todo fatto sopra
        $cdl = & $this -> getRequestCanale();

        if ( !array_key_exists('anno_accademico', $_GET) )
            $anno_accademico = $this->frontController->getAppSetting('defaultAnnoAccademico');
        elseif( !preg_match( '/^([0-9]{4})$/', $_GET['anno_accademico'] ) )
        Error::throwError(_ERROR_DEFAULT, array('id_utente' => $this->sessionUser->getIdUser(), 'msg' => 'L\'anno accademico richiesto non � valido', 'file' => __FILE__, 'line' => __LINE__));
        else
            $anno_accademico = $_GET['anno_accademico'];

        $elencoPrgAttDid = PrgAttivitaDidattica::selectPrgAttivitaDidatticaElencoCdl($cdl -> getCodiceCdl(), $anno_accademico);

        $num_ins = count($elencoPrgAttDid);
        $insAnnoCorso  = NULL;   //ultimo anno dell'insegnamento precedente
        $insCiclo = NULL;   //ultimo ciclo dell'insegnamento precedente
        $cdl_listInsYears = array();    //elenco insegnamenti raggruppati per anni
        $session_user = $this->getSessionUser();
        $session_user_groups = $session_user->getGroups();
        $cdl_listIns = array();

        $forum = new ForumApi;
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
                $allowEdit = ($session_user->isAdmin() || $session_user->isCollaboratore() );
                $fac = Facolta::selectFacoltaCodice($cdl->getCodiceFacoltaPadre());
                $editUri = (!$tempPrgAttDid->isSdoppiato())?
                DidatticaGestione::getEditUrl($tempPrgAttDid->getIdCanale(),$cdl->getIdCanale(), $fac->getIdCanale()) :
                DidatticaGestione::getEditUrl($tempPrgAttDid->getIdCanale(),$cdl->getIdCanale(), $fac->getIdCanale(),$tempPrgAttDid->getIdSdop());
                $cdl_listIns[$insAnnoCorso]['list'][$insCiclo]['list'][] =
                array( 'name' => $tempPrgAttDid->getNome(),
                        'nomeDoc' => $tempPrgAttDid->getNomeDoc(),
                        'uri' => 'v2.php?do=ShowInsegnamento&id_canale='.$tempPrgAttDid->getIdCanale(),
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
        $template -> assign('cdl_prevYear', ($anno_accademico-1).'/'.($anno_accademico) );
        $template -> assign('cdl_thisYear', ($anno_accademico).'/'.($anno_accademico+1) );
        $template -> assign('cdl_nextYear', ($anno_accademico+1).'/'.($anno_accademico+2) );
        $template -> assign('cdl_prevYearUri', 'v2.php?do=ShowCdl&id_canale='.$cdl->getIdCanale().'&anno_accademico='.($anno_accademico-1) );
        $template -> assign('cdl_nextYearUri', 'v2.php?do=ShowCdl&id_canale='.$cdl->getIdCanale().'&anno_accademico='.($anno_accademico+1) );

        $template -> assign('cdl_langList', 'Elenco insegnamenti attivati su UniversiBO');
        $template -> assign('cdl_langGoToForum', 'Link al forum');

        $this->executePlugin('ShowNewsLatest', array( 'num' => 4  ));
        $this->executePlugin('ShowLinks', array( 'num' => 12 ) );

        return 'default';
    }
}
