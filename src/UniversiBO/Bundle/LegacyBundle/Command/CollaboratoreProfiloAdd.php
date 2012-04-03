<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\Collaboratore;

/**
 * CollaboratoreProfiloAdd: si occupa dell'inserimento del profilo di un collaboratore
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Cristina Valent
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class CollaboratoreProfiloAdd extends UniversiboCommand {

    function execute() {

        $user = $this->getSessionUser();
        //$canale = & $this->getRequestCanale();
        //		$user_ruoli = $user->getRuoli();
        //		$id_canale = $canale->getIdCanale();

        //$admin = $user->isAdmin();
        // TODO controllo di questo parametro
        $id_coll = $_GET['id_coll'];

        if (!($user->isAdmin() || ($user->getIdUser() == $id_coll)))
            Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => "Non hai i diritti per inserire una notizia\n La sessione potrebbe essere scaduta", 'file' => __FILE__, 'line' => __LINE__));

        $frontcontroller = & $this->getFrontController();
        $template = & $frontcontroller->getTemplateEngine();

        // valori default form
        $f36_foto = '';
        $f36_ruolo = '';
        $f36_email= '';
        $f36_recapito = '';
        $f36_intro = '';
        $f36_obiettivi = '';

        /*
         //come fo a prendere l'uri dove si trova l'utente?

        $template->assign('back_command', $id_canale);
        $template->assign('back_id_canale', $id_canale);
        */

        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i<$num_canali; $i++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale = Canale::retrieveCanale($id_current_canale);
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$spunta = ($id_canale == $id_current_canale ) ? 'true' :'false';
        //			$f7_canale[] = array ('id_canale'=> $id_current_canale, 'nome_canale'=> $nome_current_canale, 'spunta'=> $spunta);
        //		}

        $f36_accept = false;

        if (array_key_exists('f36_submit', $_POST)) {
            $f36_accept = true;

            if (!array_key_exists('f36_ruolo', $_POST) || !array_key_exists('f36_intro', $_POST) || !array_key_exists('f36_obiettivi', $_POST)) {
                Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il form inviato non e` valido', 'file' => __FILE__, 'line' => __LINE__));
                $f36_accept = false;
            }

            //ruolo
            if (strlen($_POST['f36_ruolo']) > 150) {
                Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il ruolo deve essere inferiore ai 150 caratteri', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                $f36_accept = false;
            }
            elseif ($_POST['f36_ruolo'] == '') {
                Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il ruolo deve essere inserito obbligatoriamente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                $f36_accept = false;
            } else
                $f36_ruolo = $_POST['f36_ruolo'];
             
             
            //intro
            if ($_POST['f36_intro'] == '') {
                Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'intro del profilo deve essere inserito obbligatoriamente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                $f36_accept = false;
            } else
                $f36_intro = $_POST['f36_intro'];

            //obiettivi
            if ($_POST['f36_obiettivi'] == '') {
                Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Gli obiettivi del profilo devono essere inseriti obbligatoriamente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                $f36_accept = false;
            } else
                $f36_obiettivi= $_POST['f36_obiettivi'];

            //foto
            if (array_key_exists('f36_foto', $_POST)) {
                $f36_foto = $_POST['f36_foto'];
            }

            //email
            if (array_key_exists('f36_email', $_POST)) {
                $f36_email = $_POST['f36_email'];
            }
             
            //recapito
            if (array_key_exists('f36_recapito', $_POST)) {
                $f36_recapito = $_POST['f36_recapito'];
            }
             
            //esecuzione operazioni accettazione del form
            if ($f36_accept == true) {

                //id_news = 0 per inserimento, $id_canali array dei canali in cui inserire
                $collaboratore = new Collaboratore($user->getIdUser(), $f36_intro, $f36_recapito, $f36_obiettivi, $f36_foto, $f36_ruolo);
                $collaboratore->insertCollaboratoreItem();
                 
                //$num_canali = count($f7_canale);
                //var_dump($f7_canale);
                //var_dump($_POST['f7_canale']);


            } //end if (array_key_exists('f7_submit', $_POST))
        }
        $template->assign('f36_foto', $f36_foto);
        $template->assign('f36_ruolo', $f36_ruolo);
        $template->assign('f36_email', $f36_email);
        $template->assign('f36_recapito', $f36_recapito);
        $template->assign('f36_intro', $f36_intro);
        $template->assign('f36_obiettivi', $f36_obiettivi);
        //$template->assign('f36_canale', $f36_canale);

        //$topics[] =
        //$this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));

        return 'default';
    }
}
