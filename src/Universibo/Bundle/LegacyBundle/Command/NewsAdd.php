<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;

use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;
/**
 * NewsAdd: si occupa dell'inserimento di una news in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class NewsAdd extends CanaleCommand
{

    public function execute()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $canale = $this->getRequestCanale();
        $ruoloRepo = $this->get('universibo_legacy.repository.ruolo');
        $user_ruoli = $user instanceof User ? $ruoloRepo->findByIdUtente($user->getId()) : array();
        $id_canale = $canale->getIdCanale();
        //		var_dump($user_ruoli);die;
        $referente = false;
        $moderatore = false;

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = &$user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        if ($canale->getServizioNews() == false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Il servizio news e` disattivato",
                            'file' => __FILE__, 'line' => __LINE__));

        if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Non hai i diritti per inserire una notizia\n La sessione potrebbe essere scaduta",
                            'file' => __FILE__, 'line' => __LINE__));

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();

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
        $f7_data_scad_ora = '6';
        $f7_data_scad_min = '00';
        $f7_testo = '';
        $f7_urgente = false;
        $f7_scadenza = false;
        $f7_canale = $ruoloRepo->getRuoliInfoGroupedByYear($user, $id_canale);

        $f7_accept = false;

        if (array_key_exists('f7_submit', $_POST)) {
            $f7_accept = true;

            if (!array_key_exists('f7_titolo', $_POST)
                    || !array_key_exists('f7_data_ins_gg', $_POST)
                    || !array_key_exists('f7_data_ins_mm', $_POST)
                    || !array_key_exists('f7_data_ins_aa', $_POST)
                    || !array_key_exists('f7_data_ins_ora', $_POST)
                    || !array_key_exists('f7_data_ins_min', $_POST)
                    || !array_key_exists('f7_data_scad_gg', $_POST)
                    || !array_key_exists('f7_data_scad_mm', $_POST)
                    || !array_key_exists('f7_data_scad_aa', $_POST)
                    || !array_key_exists('f7_data_scad_ora', $_POST)
                    || !array_key_exists('f7_data_scad_min', $_POST)
                    || !array_key_exists('f7_testo', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f7_accept = false;
            }

            //titolo
            if (strlen($_POST['f7_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } elseif ($_POST['f7_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } else
                $f7_titolo = $_POST['f7_titolo'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo giorno di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
                $checkdate_ins = false;
            } else
                $f7_data_ins_gg = $_POST['f7_data_ins_gg'];

            //f7_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo mese di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
                $checkdate_ins = false;
            } else
                $f7_data_ins_mm = $_POST['f7_data_ins_mm'];

            //f7_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f7_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo anno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f7_data_ins_aa'] < 1970
                    || $_POST['f7_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
                $checkdate_ins = false;
            } else
                $f7_data_ins_aa = $_POST['f7_data_ins_aa'];

            //f7_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } elseif ($_POST['f7_data_ins_ora'] < 0
                    || $_POST['f7_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } else
                $f7_data_ins_ora = $_POST['f7_data_ins_ora'];

            //f7_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } elseif ($_POST['f7_data_ins_min'] < 0
                    || $_POST['f7_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } else
                $f7_data_ins_min = $_POST['f7_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f7_data_ins_mm'],
                            $_POST['f7_data_ins_gg'], $_POST['f7_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            }

            $data_inserimento = mktime($_POST['f7_data_ins_ora'],
                    $_POST['f7_data_ins_min'], "0", $_POST['f7_data_ins_mm'],
                    $_POST['f7_data_ins_gg'], $_POST['f7_data_ins_aa']);
            $data_scadenza = NULL;

            if (array_key_exists('f7_scadenza', $_POST)) {

                $f7_scadenza = true;
                $checkdate_scad = true;
                //data_scad_gg
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_gg'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo giorno di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                    $checkdate_scad = false;
                } else
                    $f7_data_scad_gg = $_POST['f7_data_scad_gg'];

                //f7_data_scad_mm
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_mm'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo mese di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                    $checkdate_scad = false;
                } else
                    $f7_data_scad_mm = $_POST['f7_data_scad_mm'];

                //f7_data_scad_aa
                if (!ereg('^([0-9]{4})$', $_POST['f7_data_scad_aa'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo anno di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                    $checkdate_scad = false;
                } elseif ($_POST['f7_data_scad_aa'] < 1970
                        || $_POST['f7_data_scad_aa'] > 2032) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                    $checkdate_scad = false;
                } else
                    $f7_data_scad_aa = $_POST['f7_data_scad_aa'];

                //f7_data_scad_ora
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_ora'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                } elseif ($_POST['f7_data_scad_ora'] < 0
                        || $_POST['f7_data_scad_ora'] > 23) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                } else
                    $f7_data_scad_ora = $_POST['f7_data_scad_ora'];

                //f7_data_scad_min
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f7_data_scad_min'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo minuto di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                } elseif ($_POST['f7_data_scad_min'] < 0
                        || $_POST['f7_data_scad_min'] > 59) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                } else
                    $f7_data_scad_min = $_POST['f7_data_scad_min'];

                if ($checkdate_scad == true
                        && !checkdate($_POST['f7_data_scad_mm'],
                                $_POST['f7_data_scad_gg'],
                                $_POST['f7_data_scad_aa'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La data di scadenza specificata non esiste',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                }

                //scadenza posteriore a inserimento
                $data_scadenza = mktime($_POST['f7_data_scad_ora'],
                        $_POST['f7_data_scad_min'], "0",
                        $_POST['f7_data_scad_mm'], $_POST['f7_data_scad_gg'],
                        $_POST['f7_data_scad_aa']);

                if ($data_scadenza < $data_inserimento) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La data di scadenza non puo` essere inferiore alla data di inserimento',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f7_accept = false;
                }

            }

            //testo
            if (strlen($_POST['f7_testo']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il testo della notizia deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } elseif ($_POST['f7_testo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il testo della notizia deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            } else
                $f7_testo = $_POST['f7_testo'];

            //flag urgente
            if (array_key_exists('f7_urgente', $_POST)) {
                $f7_urgente = true;
            }

            //diritti_su_tutti_i_canali
            if (array_key_exists('f7_canale', $_POST))
                foreach ($_POST['f7_canale'] as $key => $value) {
                    $diritti = $this->get('security.context')->isGranted('ROLE_ADMIN')
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
                        $f7_accept = false;
                    }
                } else {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Devi selezionare almeno una pagina in cui inserire la notizia.',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f7_accept = false;
            }

            //esecuzione operazioni accettazione del form
            if ($f7_accept == true) {

                //id_news = 0 per inserimento, $id_canali array dei canali in cui inserire
                $notizia = new NewsItem(0, $f7_titolo, $f7_testo,
                        $data_inserimento, $data_scadenza, $data_inserimento,
                        $f7_urgente, false, $user->getId(),
                        $user->getUsername());
                $notizia->insertNewsItem();

                //$num_canali = count($f7_canale);
                //var_dump($f7_canale);
                //var_dump($_POST['f7_canale']);
                foreach ($_POST['f7_canale'] as $key => $value) {
                    $notizia->addCanale($key);
                    $add_canale = Canale::retrieveCanale($key);
                    $add_canale->setUltimaModifica(time(), true);

                    //notifiche
                    $notifica_titolo = 'Nuova notizia inserita in '
                            . $add_canale->getNome();
                    $notifica_titolo = substr($notifica_titolo, 0, 199);
                    $notifica_dataIns = $data_inserimento;
                    $notifica_urgente = $f7_urgente;
                    $notifica_eliminata = false;
                    $notifica_messaggio = '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Titolo: ' . $f7_titolo . '

Testo: ' . $f7_testo . '

Autore: ' . $user->getUsername() . '

Link: ' . $frontcontroller->getAppSetting('rootUrl') . '/'
                            . $add_canale->showMe()
                            . '
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Informazioni per la cancellazione:

Per rimuoverti, vai all\'indirizzo:
' . $frontcontroller->getAppSetting('rootUrl')
                            . '
e modifica il tuo profilo personale nella dopo aver eseguito il login
Per altri problemi contattare lo staff di UniversiBO
' . $frontcontroller->getAppSetting('infoEmail');

                    $notifica_messaggio_sms = mb_convert_encoding('Novità', 'iso-8859-1', 'utf-8'). ' in '
                            . $add_canale->getNome() . ' - ' . $f7_titolo
                            . ' - ' . $f7_testo;
                    $notifica_messaggio_sms = substr(
                            substr_replace($notifica_messaggio_sms, '..', 158),
                            0, 160);

                    $ruoli_canale = $add_canale->getRuoli();
                    foreach ($ruoli_canale as $ruolo_canale) {
                        //la seguente riga l'ho copiata dal diff del deploy precedente
                        //in seguito ad una segnalazione di errore da parte di un tutor
                        //alla riga (ex)335 (ora)339
                        $notifica_user = $ruolo_canale->getUser();
                        //define('NOTIFICA_NONE'   ,0);
                        //define('NOTIFICA_URGENT' ,1);
                        //define('NOTIFICA_ALL'    ,2);
                        if ($ruolo_canale->isMyUniversiBO()
                                && (($f7_urgente
                                        && $ruolo_canale->getTipoNotifica()
                                                == NOTIFICA_URGENT)
                                        || $ruolo_canale->getTipoNotifica()
                                                == NOTIFICA_ALL)) {
                            $notifica_user = $ruolo_canale->getUser();
                            $notifica_destinatario = 'mail://'
                                    . $notifica_user->getEmail();

                            $notifica = new NotificaItem(0, $notifica_titolo,
                                    $notifica_messaggio, $notifica_dataIns,
                                    $notifica_urgente, $notifica_eliminata,
                                    $notifica_destinatario);
                            $notifica->insertNotificaItem();
                        }
                        if ($notifica_user->getPhone() != ''
                                && $ruolo_canale->isMyUniversiBO()
                                && ($f7_urgente
                                        && ($ruolo_canale->getTipoNotifica()
                                                == NOTIFICA_URGENT
                                                || $ruolo_canale
                                                        ->getTipoNotifica()
                                                        == NOTIFICA_ALL))) {

                            $notifica_destinatario = 'sms://'
                                    . $notifica_user->getPhone();

                            $notifica = new NotificaItem(0, '',
                                    $notifica_messaggio_sms, $notifica_dataIns,
                                    $notifica_urgente, $notifica_eliminata,
                                    $notifica_destinatario);
                            $notifica->insertNotificaItem();
                        }
                    }

                    //ultima notifica all'archivio
                    $notifica_destinatario = 'mail://'
                            . $frontcontroller->getAppSetting('rootEmail');

                    $notifica = new NotificaItem(0, $notifica_titolo,
                            $notifica_messaggio, $notifica_dataIns,
                            $notifica_urgente, $notifica_eliminata,
                            $notifica_destinatario);
                    $notifica->insertNotificaItem();
                }

                return 'success';
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
        $template->assignUnicode('f7_canale', $f7_canale);

        //$topics[] =
        $this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));

        return 'default';
    }
}
