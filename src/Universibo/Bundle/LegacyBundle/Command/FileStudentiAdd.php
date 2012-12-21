<?php

namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\AntiVirus\AntiVirusFactory;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

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
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => 0,
                'msg' => "Per questa operazione bisogna essere registrati\n la sessione potrebbe essere terminata",
                'file' => __FILE__, 'line' => __LINE__));
        }

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $channelRouter = $this->get('universibo_legacy.routing.channel');

        $krono = $frontcontroller->getKrono();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this
                        ->get('universibo_legacy.repository.ruolo')
                        ->findByIdUtente($user->getId()) : array();


        $template
                ->assign('common_canaleURI', array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '');
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

        $canale = $this->getRequestCanale();
        $id_canale = $canale->getIdCanale();

        if ($canale->getServizioFilesStudenti() == false)
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
                'msg' => 'Il servizio files e` disattivato',
                'file' => __FILE__, 'line' => __LINE__));

        $template->assign('common_canaleURI', $channelRouter->generate($canale));
        $template
                ->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());
        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = &$user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        $f23_canale = $canale->getNome();


        $diritti = $this->get('security.context')
                        ->isGranted('IS_AUTHENTICATED_FULLY')
                && $canale->isGroupAllowed($user->getLegacyGroups());
        if (!$diritti)
            throw new AccessDeniedException('Not allowed to upload file');

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
                Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
                    'msg' => 'Il form inviato non e` valido',
                    'file' => __FILE__, 'line' => __LINE__));
                $f23_accept = false;
            }

            //titolo
            if (strlen($_POST['f23_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
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
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo giorno di inserimento non e` valido',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } else
                $f23_data_ins_gg = $_POST['f23_data_ins_gg'];

            //f23_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo mese di inserimento non e` valido',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } else
                $f23_data_ins_mm = $_POST['f23_data_ins_mm'];

            //f23_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f23_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo anno di inserimento non e` valido',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f23_data_ins_aa'] < 1970
                    || $_POST['f23_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
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
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo ora di inserimento non e` valido',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_data_ins_ora'] < 0
                    || $_POST['f23_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_data_ins_ora = $_POST['f23_data_ins_ora'];

            //f23_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f23_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($_POST['f23_data_ins_min'] < 0
                    || $_POST['f23_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_data_ins_min = $_POST['f23_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f23_data_ins_mm'], $_POST['f23_data_ins_gg'], $_POST['f23_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'La data di inserimento specificata non esiste',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            }

            $f23_data_inserimento = mktime($_POST['f23_data_ins_ora'], $_POST['f23_data_ins_min'], "0", $_POST['f23_data_ins_mm'], $_POST['f23_data_ins_gg'], $_POST['f23_data_ins_aa']);

            //abstract
            if (strlen($_POST['f23_abstract']) > 3000) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
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
                    Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                        'msg' => 'Si possono inserire al massimo 4 parole chiave',
                        'file' => __FILE__, 'line' => __LINE__,
                        'log' => false,
                        'template_engine' => &$template));
                    $f23_accept = false;
                } else {
                    foreach ($parole_chiave as $parola) {
                        if (strlen($parola > 40)) {
                            Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                                'msg' => 'La lunghezza massima di una parola chiave e` di 40 caratteri',
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
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Il formato del campo categoria non e` ammissibile',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } elseif (!array_key_exists($_POST['f23_categoria'], $f23_categorie)) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'La categoria inviata contiene un valore non ammissibile',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } else
                $f23_categoria = $_POST['f23_categoria'];

            //permessi_download
            if (!preg_match('/^([0-9]{1,3})$/', $_POST['f23_permessi_download'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'I permessi di download non sono validi',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            } elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                if ($_POST['f23_permessi_download'] < 0
                        || $_POST['f23_permessi_download'] > LegacyRoles::ALL) {
                    Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                        'msg' => 'Il valore dei diritti di download non e` ammessibile',
                        'file' => __FILE__, 'line' => __LINE__,
                        'log' => false,
                        'template_engine' => &$template));
                    $f23_accept = false;
                }
                $f23_permessi_download = $_POST['f23_permessi_download'];
            } else {
                if ($_POST['f23_permessi_download'] != LegacyRoles::ALL
                        && $_POST['f23_permessi_download']
                        != (LegacyRoles::STUDENTE
                        | LegacyRoles::DOCENTE
                        | LegacyRoles::TUTOR
                        | LegacyRoles::PERSONALE
                        | LegacyRoles::COLLABORATORE
                        | LegacyRoles::ADMIN)) {
                    Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                        'msg' => 'Il valore dei diritti di download non e` ammessibile',
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
            if ($canale instanceof Canale)
                $f23_permessi_visualizza = $canale->getPermessi();
            else
                $f23_permessi_visualizza = LegacyRoles::ALL;
            // eventualmente dare la possibilit? all'admin di metterli diversamente
            //controllo i diritti_su_tutti_i_canali su cui si vuole fare l'inserimento
            //modifica aggiunta per compatibilità bug explorer con PHP4.3.11 e successivi
            $_FILES['f23_file']['name'] = str_replace('\\', '/', $_FILES['f23_file']['name']);
            if (get_magic_quotes_gpc()) {
                $_FILES['f23_file']['name'] = basename(
                        stripslashes($_FILES['f23_file']['name']));
            } else {
                $_FILES['f23_file']['name'] = basename(
                        $_FILES['f23_file']['name']);
            }

            $estensione = strtolower(substr($_FILES['f23_file']['name'], -4));
            if ($estensione == PHP_EXTENSION) {
                Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
                    'msg' => 'E\' severamente vietato inserire file con estensione .php',
                    'file' => __FILE__, 'line' => __LINE__));
                $f23_accept = false;
            } elseif (!is_uploaded_file($_FILES['f23_file']['tmp_name'])) {
                Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                    'msg' => 'Non e\' stato inviato nessun file',
                    'file' => __FILE__, 'line' => __LINE__,
                    'log' => false,
                    'template_engine' => &$template));
                $f23_accept = false;
            }

            //esecuzione operazioni accettazione del form
            if ($f23_accept == true) {

                $transaction = $this->getContainer()
                        ->get('universibo_legacy.transaction');
                ignore_user_abort(1);
                $transaction->begin();

                $nome_file = FileItem::normalizzaNomeFile(
                                $_FILES['f23_file']['name']);
                $dimensione_file = (int) ($_FILES['f23_file']['size'] / 1024);
                $newFile = new FileItemStudenti(0, $f23_permessi_download,
                                $f23_permessi_visualizza, $user->getId(), $f23_titolo,
                                $f23_abstract, $f23_data_inserimento, time(),
                                $dimensione_file, 0, $nome_file, $f23_categoria,
                                FileItem::guessTipo($_FILES['f23_file']['name']),
                                md5_file($_FILES['f23_file']['tmp_name']), null, '',
                                '', '', '', '');
                /* gli ultimi parametri dipendono da altre tabelle e
                  il loro valore viene insegnato internamente a FileItem
                  bisognerebbe non usare il costruttore per dover fare l'insert
                  ma... */

                $newFile->insertFileItem();
                $fileRepository = $this->get('universibo_legacy.repository.files.file_item_studenti');
                $fileRepository->addToChannel($newFile, $id_canale);

                $newFile->setParoleChiave($f23_parole_chiave);

                $nomeFile = $newFile->getNomeFile();

                if (move_uploaded_file($_FILES['f23_file']['tmp_name'], $frontcontroller->getAppSetting('filesPath')
                                . $nomeFile) === false) {
                    $transaction->rollback();
                    Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
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
                        $transaction->rollback();
                        Error::throwError(_ERROR_DEFAULT, array('id_utente' => $user->getId(),
                            'msg' => 'ATTENZIONE: Il file inviato e\' risultato positivo al controllo antivirus!',
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => false,
                            'template_engine' => &$template));
                    }
                }

                //$num_canali = count($f23_canale);
                //var_dump($f23_canale);

                $canale->setUltimaModifica(time(), true);

                //Ricerco solo i referenti/moderatori per il canale

                $userRepo = $this->get('universibo_website.repository.user');
                $contactService = $this->get('universibo_core.contact.service');

                $arrayRuoli = $canale->getRuoli();
                $keys = array_keys($arrayRuoli);
                $arrayEmailRef = array();
                $i = 0;
                foreach ($keys as $key) {
                    $ruolo = $arrayRuoli[$key];
                    if ($ruolo->isReferente() || $ruolo->isModeratore()) {
                        $user_temp = $userRepo->find($ruolo->getId());
                        if(!$user_temp instanceof User)
                            continue;
                        //Notifichiamo i professori di un nuovo file studente? Noh...
                        if ($user_temp->hasRole('ROLE_MODERATOR')
                                || $user_temp->hasRole('ROLE_ADMIN')) {
                            foreach ($contactService->getUserEmails($user_temp) as $email) {
                                $arrayEmailRef[$i] = $email;
                                $i++;
                            }
                        }
                    }
                }
                $modFileStudenti = explode(';', $frontcontroller->getAppSetting('modFileStudenti'));
                foreach ($modFileStudenti as $usernameMod) {
                    $user_temp = $userRepo->findOneByUsername($usernameMod);
                    if(!$user_temp instanceof User)
                        continue;

                    foreach ($contactService->getUserEmails($user_temp) as $email) {
                        if (!in_array($email, $arrayEmailRef)) {
                            $arrayEmailRef[$i] = $email;
                            $i++;
                        }
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

Autore: ' . $user->getUsername() . '

Link: '
                        . $router
                                ->generate('universibo_legacy_file_download', array('id_file' => $newFile->getIdFile(),
                                    'id_canale' => $canale
                                    ->getIdCanale()), true)
                        . PHP_EOL . '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~';

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
                $transaction->commit();
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
                ->assign('fileAdd_flagCanali', (count($f23_canale)) ? 'true' : 'false');

        //		$template->assign('f23_password', $f23_password);
        $template->assign('f23_permessi_download', $f23_permessi_download);
        $template->assign('f23_permessi_visualizza', $f23_permessi_visualizza);
        $template
                ->assign('f23_data_ins_gg', $krono->k_date('%j', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_mm', $krono->k_date('%m', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_aa', $krono->k_date('%Y', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_ora', $krono->k_date('%H', $f23_data_inserimento));
        $template
                ->assign('f23_data_ins_min', $krono->k_date('%i', $f23_data_inserimento));

        //$this->executePlugin('ShowTopic', array('reference' => 'filestudenti'));
        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
