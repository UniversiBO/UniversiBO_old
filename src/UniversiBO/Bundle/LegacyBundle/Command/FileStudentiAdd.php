<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

use \Error;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItem;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
use UniversiBO\Bundle\LegacyBundle\App\AntiVirus\AntiVirusFactory;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

/**
 * FileStudentiAdd: si occupa dell'inserimento di un file studente in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FileStudentiAdd extends UniversiboCommand
{

    public function execute()
    {

        $frontcontroller = &$this->getFrontController();
        $template = &$frontcontroller->getTemplateEngine();

        $krono = &$frontcontroller->getKrono();
        $user = &$this->getSessionUser();
        $user_ruoli = &$user->getRuoli();

        if ($user->isOspite()) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Per questa operazione bisogna essere registrati\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }
        /*		if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale'])) {
                    Error :: throwError(_ERROR_DEFAULT, array ('msg' => 'L\'id del canale richiesto non ? valido', 'file' => __FILE__, 'line' => __LINE__));
                }

                $canale = & Canale::retrieveCanale($_GET['id_canale']);
                $id_canale = $canale->getIdCanale();
                $template->assign('common_canaleURI', $canale->showMe());
                $template->assign('common_langCanaleNome', $canale->getTitolo());
         */
        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        // valori default form
        $f23_file = '';
        $f23_titolo = '';
        $f23_abstract = '';
        $f23_parole_chiave = array();
        $f23_categorie = FileItem::getCategorie();
        $f23_categoria = 5;
        $f23_data_inserimento = time();
        $f23_permessi_download = '';
        $f23_permessi_visualizza = '';

        //		$f23_password = null;

        if (array_key_exists('id_canale', $_GET)) {
            if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\'id del canale richiesto non � valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = &Canale::retrieveCanale($_GET['id_canale']);

            if ($canale->getServizioFilesStudenti() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il servizio files � disattivato',
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();
            $template->assign('common_canaleURI', $canale->showMe());
            $template
                    ->assign('common_langCanaleNome', 'a '
                            . $canale->getTitolo());
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = &$user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

        }

        $f23_canale = $canale->getNome();

        //		//prendo tutti i canali tra i ruoli pi? il canale corrente (che per l'admin pu� essere diverso)
        //		$ruoli_keys = array_keys($user_ruoli);
        //		$num_ruoli = count($ruoli_keys);
        //		for ($i = 0; $i<$num_ruoli; $i++)
        //		{
        //			// qui c'? errore TODO
        //			if (array_key_exists('id_canale', $_GET))
        //				if ($id_canale != $ruoli_keys[$i] && ($user->isAdmin() || $user_ruoli[$ruoli_keys[$i]]->isModeratore() || $user_ruoli[$ruoli_keys[$i]]->isReferente()) )
        //					$elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        //		}
        //
        //		$elenco_canali_retrieve = array();
        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i<$num_canali; $i++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale = Canale::retrieveCanale($id_current_canale);
        //			$elenco_canali_retrieve[$id_current_canale] = $current_canale;
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$spunta = ($id_canale == $id_current_canale ) ? 'true' :'false';
        //			$f23_canale[] = array ('id_canale'=> $id_current_canale, 'nome_canale'=> $nome_current_canale, 'spunta'=> $spunta);
        //		}
        //
        if (array_key_exists('id_canale', $_GET)) {
            $diritti = !$user->isOspite()
                    && $canale->isGroupAllowed($user->getGroups());
            if (!$diritti)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => "Non hai i diritti per inserire un file\n La sessione potrebbe essere scaduta",
                                'file' => __FILE__, 'line' => __LINE__));
        }

        $f23_accept = false;

        if (array_key_exists('f23_submit', $_POST)) {
            $f23_accept = true;

            if (!array_key_exists('f23_file', $_FILES)
                    || !array_key_exists('f23_titolo', $_POST)
                    || !array_key_exists('f23_data_ins_gg', $_POST)
                    || !array_key_exists('f23_data_ins_mm', $_POST)
                    || !array_key_exists('f23_data_ins_aa', $_POST)
                    || !array_key_exists('f23_data_ins_ora', $_POST)
                    || !array_key_exists('f23_data_ins_min', $_POST)
                    || !array_key_exists('f23_abstract', $_POST)
                    || !array_key_exists('f23_parole_chiave', $_POST)
                    || !array_key_exists('f23_categoria', $_POST)
                    || !array_key_exists('f23_permessi_download', $_POST)
                    || !array_key_exists('f23_permessi_visualizza', $_POST)) {
                //var_dump($_POST);die();
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il form inviato non � valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f23_accept = false;
            }

            //titolo
            if (strlen($_POST['f23_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_titolo = $_POST['f23_titolo'];

            //abstract
            $f23_abstract = $_POST['f23_abstract'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo giorno di inserimento non � valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } else
                $f23_data_ins_gg = $_POST['f23_data_ins_gg'];

            //f23_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo mese di inserimento non � valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } else
                $f23_data_ins_mm = $_POST['f23_data_ins_mm'];

            //f23_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f23_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo anno di inserimento non � valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f23_data_ins_aa'] < 1970
                    || $_POST['f23_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } else
                $f23_data_ins_aa = $_POST['f23_data_ins_aa'];

            //f23_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo ora di inserimento non � valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_data_ins_ora'] < 0
                    || $_POST['f23_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_data_ins_ora = $_POST['f23_data_ins_ora'];

            //f23_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo minuto di inserimento non � valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_data_ins_min'] < 0
                    || $_POST['f23_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_data_ins_min = $_POST['f23_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f23_data_ins_mm'],
                            $_POST['f23_data_ins_gg'],
                            $_POST['f23_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            }

            $f23_data_inserimento = mktime($_POST['f23_data_ins_ora'],
                    $_POST['f23_data_ins_min'], "0", $_POST['f23_data_ins_mm'],
                    $_POST['f23_data_ins_gg'], $_POST['f23_data_ins_aa']);

            //abstract
            if (strlen($_POST['f23_abstract']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La descrizione/abstract del file deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_abstract'] == '') {
                $f23_abstract = $f23_titolo;
            } else
                $f23_abstract = $_POST['f23_abstract'];

            //parole chiave
            if ($_POST['f23_parole_chiave'] != '') {
                $parole_chiave = explode("\n", $_POST['f23_parole_chiave']);
                if (count($parole_chiave) > 4) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getIdUser(),
                                    'msg' => 'Si possono inserire al massimo 4 parole chiave',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f23_accept = false;
                } else {
                    foreach ($parole_chiave as $parola) {
                        if (strlen($parola > 40)) {
                            Error::throwError(_ERROR_NOTICE,
                                    array('id_utente' => $user->getIdUser(),
                                            'msg' => 'La lunghezza massima di una parola chiave � di 40 caratteri',
                                            'file' => __FILE__,
                                            'line' => __LINE__, 'log' => false,
                                            'template_engine' => &$template));
                            $f23_accept = false;
                        } else {
                            $f23_parole_chiave[] = $parola;
                        }
                    }
                }
            }

            //permessi_download
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f23_categoria'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il formato del campo categoria non � ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif (!array_key_exists($_POST['f23_categoria'], $f23_categorie)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La categoria inviata contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_categoria = $_POST['f23_categoria'];

            //permessi_download
            if (!preg_match('/^([0-9]{1,3})$/', $_POST['f23_permessi_download'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'I permessi di download non sono validi',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($user->isAdmin()) {
                if ($_POST['f23_permessi_download'] < 0
                        || $_POST['f23_permessi_download'] > User::ALL) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getIdUser(),
                                    'msg' => 'Il valore dei diritti di download non � ammessibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f23_accept = false;
                }
                $f23_permessi_download = $_POST['f23_permessi_download'];
            } else {
                if ($_POST['f23_permessi_download'] != User::ALL
                        && $_POST['f23_permessi_download']
                                != (User::STUDENTE | User::DOCENTE
                                        | User::TUTOR | User::PERSONALE
                                        | User::COLLABORATORE | User::ADMIN)) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getIdUser(),
                                    'msg' => 'Il valore dei diritti di download non ? ammessibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f23_accept = false;
                }
                $f23_permessi_download = $_POST['f23_permessi_download'];
            }

            //			//password non necessita controlli
            //			if ($_POST['f23_password'] != $_POST['f23_password_confirm'])
            //			{
            //				Error :: throwError(_ERROR_NOTICE, array ('msg' => 'La password e il campo di verifica non corrispondono', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' =>& $template));
            //				$f23_accept = false;
            //			}
            //			elseif($_POST['f23_password'] != '')
            //			{
            //				$f23_password = $_POST['f23_password'];
            //			}
            //e i permessi di visualizzazione??
            // li prendo uguali a quelli del canale,
            if (array_key_exists('id_canale', $_GET))
                $f23_permessi_visualizza = $canale->getPermessi();
            else
                $f23_permessi_visualizza = User::ALL;
            // eventualmente dare la possibilit? all'admin di metterli diversamente

            //controllo i diritti_su_tutti_i_canali su cui si vuole fare l'inserimento

            //modifica aggiunta per compatibilit� bug explorer con PHP4.3.11 e successivi
            $_FILES['f23_file']['name'] = str_replace('\\', '/',
                    $_FILES['f23_file']['name']);
            if (get_magic_quotes_gpc()) {
                $_FILES['f23_file']['name'] = basename(
                        stripslashes($_FILES['f23_file']['name']));
            } else {
                $_FILES['f23_file']['name'] = basename(
                        $_FILES['f23_file']['name']);
            }

            $estensione = strtolower(substr($_FILES['f23_file']['name'], -4));
            if ($estensione == PHP_EXTENSION) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'E\' severamente vietato inserire file con estensione .php',
                                'file' => __FILE__, 'line' => __LINE__));
                $f23_accept = false;
            } elseif (!is_uploaded_file($_FILES['f23_file']['tmp_name'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Non e\' stato inviato nessun file',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f23_accept = false;
            }

            //esecuzione operazioni accettazione del form
            if ($f23_accept == true) {

                $db = FrontController::getDbConnection('main');
                ignore_user_abort(1);
                $db->autoCommit(false);

                $nome_file = FileItem::normalizzaNomeFile(
                        $_FILES['f23_file']['name']);
                $dimensione_file = (int) ($_FILES['f23_file']['size'] / 1024);
                $newFile = new FileItemStudenti(0, $f23_permessi_download,
                        $f23_permessi_visualizza, $user->getIdUser(),
                        $f23_titolo, $f23_abstract, $f23_data_inserimento,
                        time(), $dimensione_file, 0, $nome_file,
                        $f23_categoria,
                        FileItem::guessTipo($_FILES['f23_file']['name']),
                        md5_file($_FILES['f23_file']['tmp_name']), null, '',
                        '', '', '', '');
                /* gli ultimi parametri dipendono da altre tabelle e
                 il loro valore viene insegnato internamente a FileItem
                 bisognerebbe non usare il costruttore per dover fare l'insert
                 ma...*/

                $newFile->insertFileItem();

                $newFile->setParoleChiave($f23_parole_chiave);

                $nomeFile = $newFile->getNomeFile();

                if (move_uploaded_file($_FILES['f23_file']['tmp_name'],
                        $frontcontroller->getAppSetting('filesPath')
                                . $nomeFile) === false) {
                    $db->rollback();
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $user->getIdUser(),
                                    'msg' => 'Errore nella copia del file',
                                    'file' => __FILE__, 'line' => __LINE__));
                }

                //controllo antivirus
                if ($antiVirus = AntiVirusFactory::getAntiVirus(
                        $frontcontroller)) {
                    if ($antiVirus
                            ->checkFile(
                                    $frontcontroller
                                            ->getAppSetting('filesPath')
                                            . $nomeFile) === true) {
                        $db->rollback();
                        Error::throwError(_ERROR_DEFAULT,
                                array('id_utente' => $user->getIdUser(),
                                        'msg' => 'ATTENZIONE: Il file inviato e\' risultato positivo al controllo antivirus!',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                    }
                }

                //$num_canali = count($f23_canale);
                //var_dump($f23_canale);

                $newFile->addCanale($canale->getIdCanale());
                $canale->setUltimaModifica(time(), true);

                //Ricerco solo i referenti/moderatori per il canale

                $arrayRuoli = $canale->getRuoli();
                $keys = array_keys($arrayRuoli);
                $arrayEmailRef = array();
                $i = 0;
                foreach ($keys as $key) {
                    $ruolo = $arrayRuoli[$key];
                    if ($ruolo->isReferente() || $ruolo->isModeratore()) {
                        $user_temp = User::selectUser($ruolo->getIdUser());
                        //Notifichiamo i professori di un nuovo file studente? Noh...
                        if ($user_temp->isCollaboratore()
                                || $user_temp->isAdmin()) {
                            $arrayEmailRef[$i] = $user_temp->getEmail();
                            $i++;
                        }
                    }
                }
                $modFileStudenti = explode(';',
                        $frontcontroller->getAppSetting('modFileStudenti'));
                foreach ($modFileStudenti as $usernameMod) {
                    $user_temp = User::selectUserUsername($usernameMod);
                    if (!in_array($user_temp->getEmail(), $arrayEmailRef)) {
                        $arrayEmailRef[$i] = $user_temp->getEmail();
                        $i++;
                    }
                }

                //						var_dump($arrayEmailRef);
                //						die();

                //Ok: prende i referenti, e solo gli studenti...;)...
                //Bisogna solo creare la notifica...
                //E serve uno script per le pagine SENZA referenti --> admin preposti al lavoro...

                //todo: notifiche solo per referenti e admin che devono controllare la
                //correttezza legale del file

                //notifiche
                $notifica_titolo = 'Nuovo file studente inserito in '
                        . $canale->getNome();
                $notifica_titolo = substr($notifica_titolo, 0, 199);
                $notifica_dataIns = $f23_data_inserimento;
                $notifica_urgente = true;
                $notifica_eliminata = false;
                $notifica_messaggio = '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Titolo File: ' . $f23_titolo . '

Descrizione: ' . $f23_abstract . '

Dimensione: ' . $dimensione_file . ' kB

Autore: ' . $user->getUsername()
                        . '

Link: https://www.universibo.unibo.it/v2.php?do=FileShowInfo&id_file='
                        . $newFile->getIdFile() . '&id_canale='
                        . $canale->getIdCanale()
                        . '
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~';

                //
                //						$ruoli_canale = $canale->getRuoli();
                for ($j = 0; $j < $i; $j++) {
                    $notifica_destinatario = 'mail://' . $arrayEmailRef[$j];
                    $notifica = new NotificaItem(0, $notifica_titolo,
                            $notifica_messaggio, $notifica_dataIns,
                            $notifica_urgente, $notifica_eliminata,
                            $notifica_destinatario);
                    $notifica->insertNotificaItem();
                }
                //
                //						//ultima notifica all'archivio
                //						$notifica_destinatario = 'mail://'.$frontcontroller->getAppSetting('rootEmail');;
                //
                //						$notifica = new NotificaItem(0, $notifica_titolo, $notifica_messaggio, $notifica_dataIns, $notifica_urgente, $notifica_eliminata, $notifica_destinatario );
                //						$notifica->insertNotificaItem();
                //
                //
                $db->autoCommit(true);
                ignore_user_abort(0);

                return 'success';
            }

        }
        //end if (array_key_exists('f23_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f23_file', $f23_file);
        $template->assign('f23_titolo', $f23_titolo);
        $template->assign('f23_abstract', $f23_abstract);
        $template->assign('f23_parole_chiave', $f23_parole_chiave);
        $template->assign('f23_categoria', $f23_categoria);
        $template->assign('f23_categorie', $f23_categorie);
        $template->assign('f23_abstract', $f23_abstract);
        $template->assign('f23_canale', $f23_canale);
        $template
                ->assign('fileAdd_flagCanali',
                        (count($f23_canale)) ? 'true' : 'false');

        //		$template->assign('f23_password', $f23_password);
        $template->assign('f23_permessi_download', $f23_permessi_download);
        $template->assign('f23_permessi_visualizza', $f23_permessi_visualizza);
        $template
                ->assign('f23_data_ins_gg',
                        $krono->k_date('%j', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_mm',
                        $krono->k_date('%m', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_aa',
                        $krono->k_date('%Y', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_ora',
                        $krono->k_date('%H', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_min',
                        $krono->k_date('%i', $f23_data_inserimento));

        //$this->executePlugin('ShowTopic', array('reference' => 'filestudenti'));
        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';

    }
}
