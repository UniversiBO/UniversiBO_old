<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\AntiVirus\AntiVirusFactory;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;
/**
 * FileAdd: si occupa dell'inserimento di un file in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileAdd extends UniversiboCommand
{
    public function execute()
    {
        $context = $this->get('security.context');
        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => 0,
                            'msg' => "Per questa operazione bisogna essere registrati\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $request = $this->getRequest();
        $router = $this->get('router');
        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();
        $user = $context->getToken()->getUser();
        $ruoloRepo = $this->get('universibo_legacy.repository.ruolo');
        $user_ruoli = $ruoloRepo->findByIdUtente($user->getId());

        $template->assign('common_canaleURI',$request->server->get('HTTP_REFERER', ''));
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        // valori default form
        $f12_file = '';
        $f12_titolo = '';
        $f12_abstract = '';
        $f12_parole_chiave = array();
        $f12_categorie = FileItem::getCategorie();
        $f12_categoria = 5;
        $f12_data_inserimento = time();
        $f12_permessi_download = '';
        $f12_permessi_visualizza = '';
        $f12_password = null;

        $elenco_canali = array();

        $channelId = $this->getRequest()->get('id_canale');

        $filesPath = $this->get('kernel')->getRootDir().'/data/uploads/';
        if ($channelId !== null) {
            if (!preg_match('/^([0-9]{1,9})$/', $channelId))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $channelRepo = $this->get('universibo_legacy.repository.canale2');
            $canale = $channelRepo->find($channelId);

            if (!$canale instanceof Canale) {
                throw new NotFoundHttpException('Channel not found');
            }

            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $channelId = $canale->getIdCanale();
            $template->assign('common_canaleURI', $channelRouter->generate($canale));
            $template
                    ->assign('common_langCanaleNome', 'a '
                            . $canale->getTitolo());
            if (array_key_exists($channelId, $user_ruoli)) {
                $ruolo = $user_ruoli[$channelId];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

            $elenco_canali = array($channelId);
            $f12_canale = $ruoloRepo->getRuoliInfoGroupedByYear($user, $channelId);
        } else
            $f12_canale = $ruoloRepo->getRuoliInfoGroupedByYear($user);

        $f12_accept = false;

        if (array_key_exists('f12_submit', $_POST)) {
            $f12_accept = true;

            if (!array_key_exists('f12_file', $_FILES)
                    || !array_key_exists('f12_titolo', $_POST)
                    || !array_key_exists('f12_data_ins_gg', $_POST)
                    || !array_key_exists('f12_data_ins_mm', $_POST)
                    || !array_key_exists('f12_data_ins_aa', $_POST)
                    || !array_key_exists('f12_data_ins_ora', $_POST)
                    || !array_key_exists('f12_data_ins_min', $_POST)
                    || !array_key_exists('f12_abstract', $_POST)
                    || !array_key_exists('f12_parole_chiave', $_POST)
                    || !array_key_exists('f12_categoria', $_POST)
                    || !array_key_exists('f12_permessi_download', $_POST)
                    || !array_key_exists('f12_permessi_visualizza', $_POST)
                    || !array_key_exists('f12_password', $_POST)
                    || !array_key_exists('f12_password_confirm', $_POST)) {
                //var_dump($_POST);die();
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f12_accept = false;
            }

            //titolo
            if (strlen($_POST['f12_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($_POST['f12_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } else
                $f12_titolo = $_POST['f12_titolo'];

            //abstract
            $f12_abstract = $_POST['f12_abstract'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f12_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo giorno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
                $checkdate_ins = false;
            } else
                $f12_data_ins_gg = $_POST['f12_data_ins_gg'];

            //f12_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f12_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo mese di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
                $checkdate_ins = false;
            } else
                $f12_data_ins_mm = $_POST['f12_data_ins_mm'];

            //f12_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f12_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo anno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f12_data_ins_aa'] < 1970
                    || $_POST['f12_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
                $checkdate_ins = false;
            } else
                $f12_data_ins_aa = $_POST['f12_data_ins_aa'];

            //f12_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f12_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo ora di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($_POST['f12_data_ins_ora'] < 0
                    || $_POST['f12_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } else
                $f12_data_ins_ora = $_POST['f12_data_ins_ora'];

            //f12_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f12_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($_POST['f12_data_ins_min'] < 0
                    || $_POST['f12_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } else
                $f12_data_ins_min = $_POST['f12_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f12_data_ins_mm'],
                            $_POST['f12_data_ins_gg'],
                            $_POST['f12_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            }

            $f12_data_inserimento = mktime($_POST['f12_data_ins_ora'],
                    $_POST['f12_data_ins_min'], "0", $_POST['f12_data_ins_mm'],
                    $_POST['f12_data_ins_gg'], $_POST['f12_data_ins_aa']);

            //abstract
            if (strlen($_POST['f12_abstract']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La descrizione/abstract del file deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($_POST['f12_abstract'] == '') {
                $f12_abstract = $f12_titolo;
            } else {
                $f12_abstract = $_POST['f12_abstract'];
            }

            //parole chiave
            if ($_POST['f12_parole_chiave'] != '') {
                $parole_chiave = explode("\n", $_POST['f12_parole_chiave']);
                if (count($parole_chiave) > 4) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Si possono inserire al massimo 4 parole chiave',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f12_accept = false;
                } else {
                    foreach ($parole_chiave as $parola) {
                        if (strlen($parola > 40)) {
                            Error::throwError(_ERROR_NOTICE,
                                    array('id_utente' => $user->getId(),
                                            'msg' => 'La lunghezza massima di una parola chiave e` di 40 caratteri',
                                            'file' => __FILE__,
                                            'line' => __LINE__, 'log' => false,
                                            'template_engine' => &$template));
                            $f12_accept = false;
                        } else {
                            $f12_parole_chiave[] = $parola;
                        }
                    }
                }
            }

            //permessi_download
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f12_categoria'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo categoria non e` ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif (!array_key_exists($_POST['f12_categoria'], $f12_categorie)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La categoria inviata contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } else
                $f12_categoria = $_POST['f12_categoria'];

            //permessi_download
            if (!preg_match('/^([0-9]{1,3})$/', $_POST['f12_permessi_download'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'I permessi di download non sono validi',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($context->isGranted('ROLE_ADMIN')) {
                if ($_POST['f12_permessi_download'] < 0
                        || $_POST['f12_permessi_download'] > LegacyRoles::ALL) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il valore dei diritti di download non è ammissibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f12_accept = false;
                }
                $f12_permessi_download = $_POST['f12_permessi_download'];
            } else {
                if ($_POST['f12_permessi_download'] != LegacyRoles::ALL
                        && $_POST['f12_permessi_download']
                                != (LegacyRoles::ALL & ~LegacyRoles::OSPITE)) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il valore dei diritti di download non è ammissibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f12_accept = false;
                }
                $f12_permessi_download = $_POST['f12_permessi_download'];
            }

            //password non necessita controlli
            if ($_POST['f12_password'] != $_POST['f12_password_confirm']) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La password e il campo di verifica non corrispondono',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            } elseif ($_POST['f12_password'] != '') {
                $f12_password = $_POST['f12_password'];
            }
            //e i permessi di visualizzazione??
            // li prendo uguali a quelli del canale,
            if ($canale instanceof Canale)
                $f12_permessi_visualizza = $canale->getPermessi();
            else
                $f12_permessi_visualizza = LegacyRoles::ALL;
            // eventualmente dare la possibilità all'admin di metterli diversamente

            $f12_canali_inserimento = array();
            //controllo i diritti_su_tutti_i_canali su cui si vuole fare l'inserimento
            if (array_key_exists('f12_canale', $_POST))
                foreach ($_POST['f12_canale'] as $key => $value) {
                    $diritti = $context->isGranted('ROLE_ADMIN')
                            || (array_key_exists($key, $user_ruoli)
                                    && ($user_ruoli[$key]->isReferente()
                                            || $user_ruoli[$key]
                                                    ->isModeratore()));
                    if (!$diritti) {
                        //$user_ruoli[$key]->getIdCanale();
                        $canale = Canale::retrieveCanale($key);
                        Error::throwError(_ERROR_NOTICE,
                                array('id_utente' => $user->getId(),
                                        'msg' => 'Non possiedi i diritti di inserimento nel canale: '
                                                . $canale->getTitolo(),
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f12_accept = false;
                    }

                    $f12_canali_inserimento = $_POST['f12_canale'];
                }

            //modifica aggiunta per compatibilità bug explorer con PHP4.3.11 e successivi
            $_FILES['f12_file']['name'] = str_replace('\\', '/',
                    $_FILES['f12_file']['name']);
            if (get_magic_quotes_gpc()) {
                $_FILES['f12_file']['name'] = basename(
                        stripslashes($_FILES['f12_file']['name']));
            } else {
                $_FILES['f12_file']['name'] = basename(
                        $_FILES['f12_file']['name']);
            }

            //controllo estensioni non permesse
            $estensione = strtolower(substr($_FILES['f12_file']['name'], -4));
            if ($estensione == PHP_EXTENSION) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'E\' severamente vietato inserire file con estensione .php',
                                'file' => __FILE__, 'line' => __LINE__));
                $f12_accept = false;
            } elseif (!is_uploaded_file($_FILES['f12_file']['tmp_name'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Non e\' stato inviato nessun file',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f12_accept = false;
            }

            //esecuzione operazioni accettazione del form
            if ($f12_accept == true) {

                $transaction = $this->getContainer()->get('universibo_legacy.transaction');

                ignore_user_abort(1);
                $transaction->begin();

                $nome_file = FileItem::normalizzaNomeFile(
                        $_FILES['f12_file']['name']);
                $dimensione_file = (int) ($_FILES['f12_file']['size'] / 1024);
                $newFile = new FileItem(0, $f12_permessi_download,
                        $f12_permessi_visualizza, $user->getId(),
                        $f12_titolo, $f12_abstract, $f12_data_inserimento,
                        time(), $dimensione_file, 0, $nome_file,
                        $f12_categoria,
                        FileItem::guessTipo($_FILES['f12_file']['name']),
                        md5_file($_FILES['f12_file']['tmp_name']),
                        ($f12_password == null) ? $f12_password
                                : FileItem::passwordHashFunction($f12_password),
                        '', '', '', '', '');
                /* gli ultimi parametri dipendono da altre tabelle e
                 il loro valore viene insegnato internamente a FileItem
                bisognerebbe non usare il costruttore per dover fare l'insert
                ma...*/

                $newFile->insertFileItem();

                $newFile->setParoleChiave($f12_parole_chiave);

                $nomeFile = $newFile->getNomeFile();

                if (move_uploaded_file($_FILES['f12_file']['tmp_name'],
                        $filesPath
                                . $nomeFile) === false) {
                    $transaction->rollback();
                    Error::throwError(_ERROR_DEFAULT,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Errore nella copia del file',
                                    'file' => __FILE__, 'line' => __LINE__));
                }

                $antiVirus = AntiVirusFactory::getAntiVirus($frontcontroller);
                //controllo antivirus
                if ($antiVirus) {
                    if ($antiVirus
                            ->checkFile(
                                    $frontcontroller
                                            ->getAppSetting('filesPath')
                                            . $nomeFile) === true) {
                        $transaction->rollback();
                        Error::throwError(_ERROR_DEFAULT,
                                array('id_utente' => $user->getId(),
                                        'msg' => 'ATTENZIONE: Il file inviato e\' risultato positivo al controllo antivirus!',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                    }
                }

                //$num_canali = count($f12_canale);
                //var_dump($f12_canale);
                if (array_key_exists('f12_canale', $_POST))
                    $userRepo = $this->get('universibo_core.repository.user');
                    foreach ($_POST['f12_canale'] as $key => $value) {
                        $newFile->addCanale($key);
                        $canale = Canale::retrieveCanale($key);
                        $canale->setUltimaModifica(time(), true);

                        //notifiche
                        $notifica_titolo = 'Nuovo file inserito in '
                                . $canale->getNome();
                        $notifica_titolo = substr($notifica_titolo, 0, 199);
                        $notifica_dataIns = $f12_data_inserimento;
                        $notifica_urgente = false;
                        $notifica_eliminata = false;
                        $notifica_messaggio = '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                            Titolo File: ' . $f12_titolo
                                . '

                            Descrizione: ' . $f12_abstract
                                . '

                            Dimensione: ' . $dimensione_file
                                . ' kB

                            Autore: ' . $user->getUsername()
                                . '

                            Link: '
                                . $router->generate('universibo_legacy_file', array('id_file' => $newFile->getIdFile()), true)
                                . '
                            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                            Informazioni per la cancellazione:

                            Per rimuoverti, vai all\'indirizzo:
                            ' . $frontcontroller->getAppSetting('rootUrl')
                                . '
                            e modifica il tuo profilo personale nella dopo aver eseguito il login
                            Per altri problemi contattare lo staff di UniversiBO
                            ' . $frontcontroller->getAppSetting('infoEmail');

                        $ruoli_canale = $canale->getRuoli();
                        foreach ($ruoli_canale as $ruolo_canale) {
                            //define('NOTIFICA_NONE'   ,0);
                            //define('NOTIFICA_URGENT' ,1);
                            //define('NOTIFICA_ALL'    ,2);
                            if ($ruolo_canale->isMyUniversiBO()
                                    && ($ruolo_canale->getTipoNotifica()
                                            == NOTIFICA_URGENT
                                            || $ruolo_canale->getTipoNotifica()
                                                    == NOTIFICA_ALL)) {
                                $notifica_user = $userRepo->find($ruolo_canale->getId());
                                if(!$notifica_user instanceof User)
                                    continue;

                                foreach ($notifica_user->getContacts() as $contact) {
                                    $notifica_destinatario = 'mail://'
                                        . $contact->getValue();

                                    $notifica = new NotificaItem(0,
                                        $notifica_titolo, $notifica_messaggio,
                                        $notifica_dataIns, $notifica_urgente,
                                        $notifica_eliminata,
                                        $notifica_destinatario);
                                    $notifica->insertNotificaItem();
                                }
                            }
                        }

                        //ultima notifica all'archivio
                        $notifica_destinatario = 'mail://'
                                . $frontcontroller->getAppSetting('rootEmail');
                        ;

                        $notifica = new NotificaItem(0, $notifica_titolo,
                                $notifica_messaggio, $notifica_dataIns,
                                $notifica_urgente, $notifica_eliminata,
                                $notifica_destinatario);
                        $notifica->insertNotificaItem();

                    }

                $transaction->commit();
                ignore_user_abort(0);

                return 'success';
            }

        }
        //end if (array_key_exists('f12_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f12_file', $f12_file);
        $template->assign('f12_titolo', $f12_titolo);
        $template->assign('f12_abstract', $f12_abstract);
        $template->assign('f12_parole_chiave', $f12_parole_chiave);
        $template->assign('f12_categoria', $f12_categoria);
        $template->assign('f12_categorie', $f12_categorie);
        $template->assign('f12_abstract', $f12_abstract);
        $template->assign('f12_canale', $f12_canale);
        $template
                ->assign('fileAdd_flagCanali',
                        (count($f12_canale)) ? 'true' : 'false');

        $template->assign('f12_password', $f12_password);
        $template->assign('f12_permessi_download', $f12_permessi_download);
        $template->assign('f12_permessi_visualizza', $f12_permessi_visualizza);
        $template
                ->assign('f12_data_ins_gg',
                        $krono->k_date('%j', $f12_data_inserimento));
        $template
                ->assign('f12_data_ins_mm',
                        $krono->k_date('%m', $f12_data_inserimento));
        $template
                ->assign('f12_data_ins_aa',
                        $krono->k_date('%Y', $f12_data_inserimento));
        $template
                ->assign('f12_data_ins_ora',
                        $krono->k_date('%H', $f12_data_inserimento));
        $template
                ->assign('f12_data_ins_min',
                        $krono->k_date('%i', $f12_data_inserimento));

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';

    }
}
