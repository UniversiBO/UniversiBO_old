<?php   

use UniversiBO\Legacy\App\CanaleCommand;

/**
 * AddNewsCanale: aggiunge una notizia, mostra il form e gestisce l'inserimento
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class AddNewsCanale extends CanaleCommand {

    function execute() {
        $user = getSessionUser();
        $user_ruoli = & $user->getRuoli();
        $id_canale = $canale->getIdCanale();

        if (array_key_exists($id_canale, $user_ruoli) || $user->isAdmin()) {
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = & $user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
                $ultimo_accesso = $ruolo->getUltimoAccesso();
            }

            if ($user->isAdmin() || $referente || $moderatore) {

                $frontcontroller = & $this->getFrontController();
                $template = & $frontcontroller->getTemplateEngine();

                $krono = & $frontcontroller->getKrono();

                // valori default form
                $f7_titolo = '';
                $f7_data_ins_gg = $krono->k_date('%j');
                $f7_data_ins_mm = $krono->k_date('%m');
                $f7_data_ins_aa = $krono->k_date('%Y');
                $f7_data_ins_ora = $krono->k_date('%H');
                $f7_data_ins_min = $krono->k_date('%i');
                $f7_data_scad_gg = '';
                $f7_data_scad_mm = '';
                $f7_data_scad_aa = '';
                $f7_data_scad_ora = '';
                $f7_data_scad_min = '';
                $f7_testo = '';
                $f7_urgente = false;
                $f7_scadenza = false;
                /*
                 * @todo da gestire
                */
                $f7_canale[] = array ();

                $f7_accept = false;

                if (array_key_exists('f7_submit', $_POST)) {
                    //var_dump($_POST);
                    $f7_accept = true;

                    if (!array_key_exists('f7_titolo', $_POST) || !array_key_exists('f7_data_ins_gg', $_POST) || !array_key_exists('f7_data_ins_mm', $_POST) || !array_key_exists('f7_data_ins_aa', $_POST) || !array_key_exists('f7_data_ins_ora', $_POST) || !array_key_exists('f7_data_ins_min', $_POST) || !array_key_exists('f7_data_scad_gg', $_POST) || !array_key_exists('f7_data_scad_mm', $_POST) || !array_key_exists('f7_data_scad_aa', $_POST) || !array_key_exists('f7_data_scad_ora', $_POST) || !array_key_exists('f7_data_scad_min', $_POST) || !array_key_exists('f7_testo', $_POST)) {
                        Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il form inviato non è valido', 'file' => __FILE__, 'line' => __LINE__));
                        $f7_accept = false;
                    }

                    //titolo
                    if (strlen($_POST['f7_titolo']) > 150) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il titolo deve essere inferiore ai 150 caratteri', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    }
                    elseif ($_POST['f7_titolo'] == '') {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il titolo deve essere inserito obbligatoriamente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_titolo = $_POST['f7_titolo'];

                    //data_ins_gg
                    if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_gg'])) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo giorno di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_data_ins_gg = $_POST['f7_data_ins_gg'];

                    //f7_data_ins_mm
                    if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_mm'])) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo mese di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_data_ins_mm = $_POST['f7_data_ins_mm'];

                    //f7_data_ins_aa
                    if (!preg_match('/^([0-9]{4})$/', $_POST['f7_data_ins_aa'])) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo anno di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    }
                    elseif ($_POST['f7_data_ins_aa'] < 1970 || $_POST['f7_data_ins_aa'] > 2032) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_data_ins_aa = $_POST['f7_data_ins_aa'];

                    //f7_data_ins_ora
                    if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_ora'])) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo ora di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    }
                    elseif ($_POST['f7_data_ins_ora'] < 0 || $_POST['f7_data_ins_ora'] > 23) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_data_ins_ora = $_POST['f7_data_ins_ora'];

                    //f7_data_ins_min
                    if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_min'])) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo minuto di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    }
                    elseif ($_POST['f7_data_ins_min'] < 0 || $_POST['f7_data_ins_min'] > 59) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_data_ins_min = $_POST['f7_data_ins_min'];

                    if (!checkdate($_POST['f7_data_ins_mm'], $_POST['f7_data_ins_gg'], $_POST['f7_data_ins_aa']))
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'La data di inserimento specificata non esiste',  'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template)); $f7_accept = false;

                    if (array_key_exists('f7_scadenza', $_POST)) {

                        $f7_scadenza = true;

                        if (!checkdate($_POST['f7_data_scad_mm'], $_POST['f7_data_scad_gg'], $_POST['f7_data_scad_aa']))
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'La data di scadenza specificata non esiste', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template)); $f7_accept = false;

                        //data_scad_gg
                        if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_gg'])) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo giorno di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        } else
                            $f7_data_scad_gg = $_POST['f7_data_scad_gg'];

                        //f7_data_scad_mm
                        if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_mm'])) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo mese di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        } else
                            $f7_data_scad_mm = $_POST['f7_data_scad_mm'];

                        //f7_data_scad_aa
                        if (!preg_match('/^([0-9]{4})$/', $_POST['f7_data_scad_aa'])) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo anno di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        }
                        elseif ($_POST['f7_data_scad_aa'] < 1970 || $_POST['f7_data_scad_aa'] > 2032) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        } else
                            $f7_data_scad_aa = $_POST['f7_data_scad_aa'];

                        //f7_data_scad_ora
                        if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_ora'])) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo ora di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        }
                        elseif ($_POST['f7_data_scad_ora'] < 0 || $_POST['f7_data_scad_ora'] > 23) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        } else
                            $f7_data_scad_ora = $_POST['f7_data_scad_ora'];

                        //f7_data_scad_min
                        if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_min'])) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il formato del campo minuto di inserimento non è valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        }
                        elseif ($_POST['f7_data_scad_min'] < 0 || $_POST['f7_data_scad_min'] > 59) {
                            Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                            $f7_accept = false;
                        } else
                            $f7_data_scad_min = $_POST['f7_data_scad_min'];

                        //scadenza posteriore a inserimento
                        $data_scadenza = mktime($_POST['f7_data_scad_ora'], $_POST['f7_data_scad_min'], "0", $_POST['f7_data_scad_mm'], $_POST['f7_data_scad_gg'], $_POST['f7_data_scad_aa']);
                        $data_inserimento = mktime($_POST['f7_data_ins_ora'], $_POST['f7_data_ins_min'], "0", $_POST['f7_data_ins_mm'], $_POST['f7_data_ins_gg'], $_POST['f7_data_ins_aa']);

                        if ($data_scadenza < $data_inserimento)
                            $f7_accept = false;

                    }

                    //testo
                    if (strlen($_POST['f7_testo']) > 3000) {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il testo della notizia deve essere inferiore ai 3000 caratteri', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    }
                    elseif ($_POST['f7_testo'] == '') {
                        Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il testo della notizia deve essere inserito obbligatoriamente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                        $f7_accept = false;
                    } else
                        $f7_testo = $_POST['f7_testo'];

                    //flag urgente
                    if (array_key_exists('f7_urgente', $_POST)) {
                        $f7_urgente = true;
                    }

                    //esecuzione operazioni accettazione del form
                    if ($f7_accept == true) {

                        //id_news = 0 per inserimento, $id_canali array dei canali in cui inserire
                        //$id_notizia, $titolo, $notizia, $dataIns, $dataScadenza, $ultimaModifica, $urgente, $eliminata, $id_utente, $username
                        $notizia = new NewsItem(0, $f7_titolo, $f7_testo, $data_inserimento, $data_scadenza, $data_inserimento, ($f7_urgente) ? 'S' : 'N', 'N', $user->getIdUser(), $user->getUsername());
                        if ($notizia->insertNewsItem($id_canali)) {
                            return 'success';
                        }
                    }

                } //end if (array_key_exists('f7_submit', $_POST))

                $template->assign('f7_titolo', $f7_titolo);
                $template->assign('f7_data_ins_mm', $f7_data_ins_mm);
                $template->assign('f7_data_ins_gg', $f7_data_ins_gg);
                $template->assign('f7_data_ins_aa', $f7_data_ins_aa);
                $template->assign('f7_data_ins_ora', $f7_data_ins_ora);
                $template->assign('f7_data_ins_min', $f7_data_ins_min);
                $template->assign('f7_data_scad_gg', $f7_data_scad_gg);
                $template->assign('f7_data_scad_mm', $f7_data_scad_mm);
                $template->assign('f7_data_scad_aa', $f7_data_scad_aa);
                $template->assign('f7_data_scad_ora', $f7_data_scad_ora);
                $template->assign('f7_data_scad_min', $f7_data_scad_min);
                $template->assign('f7_testo', $f7_testo);
                $template->assign('f7_urgente', $f7_urgente);
                $template->assign('f7_scadenza', $f7_scadenza);
                $template->assign('f7_canale', $f7_canale);

                return 'default';

            }
        }
    }
}
