<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowContributes is an extension of UniversiboCommand class.
 *
 * It shows Contribute page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowContribute extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $user = $this->getSessionUser();

        $template->assign('contribute_langTitleAlt', 'Collabora');
        $template
                ->assignUnicode('contribute_langIntro',
                        array(
                                'UniversiBO è un sito che nasce dalla collaborazione tra studenti, docenti e strutture universitarie. I docenti sono stati disponibili a dare il loro contributo e li ringraziamo per questo. Ma per permettere che questo portale continui la sua vita occorre che anche gli studenti collaborino.',
                                'Se pensate che il servizio che offriamo sia utile e desiderate che continui a essere disponibile per tutti allora aiutateci a rendere questo portale ancora migliore.'));
        $template
                ->assign('contribute_langTitle',
                        '-- Come fare per collaborare? --');
        $template
                ->assignUnicode('contribute_langHowToContribute',
                        array(
                                'Non vi chiediamo di dedicare al progetto tutta la vostra vita universitaria!
        Le modalità di collaborazione sono tante e ognuna richiede tempi
        diversi. Eccovi un breve elenco di ciò che potreste fare per aiutarci:',
                                '[list]
        [*]potreste occuparvi di aggiungere [b]contenuti[/b] al sito:
          se avete molto tempo potreste scrivere alcune pagine altrimenti è
          sufficiente che siate solidali con gli altri e rispondiate alle domande
          che vengono poste nei forum.
        [*]attualmente solo docenti e moderatori possono pubblicare news. Ma
          se ne conoscete alcune che pensate tutti debbano conoscere potete segnalarle
          sul forum e invitare i moderatori a pubblicarla come news.
        [*]potreste aiutarci con l\'attività di [b]moderazione[/b] e
          proporre la vostra candidatura al titolo di moderatore;
        [*]segnalateci ogni errore o problema che riscontrate scrivendo a
        [url=mailto:' . $frontcontroller->getAppSetting('infoEmail') . ']'
                                        . $frontcontroller
                                                ->getAppSetting('infoEmail')
                                        . '[/url]
        oppure preferibilmente scrivendo sul forum dedicato.
        [*]oppure potreste aiutaci nella [b]progettazione[/b]: scrivendo
          contenuti, scrivendo il codice che genera le pagine, aiutandoci nell\'amministrazione
          del database, creando immagini grafiche...
        [*]e se non avete la possibilità di utilizzare il computer potreste
          comunque aiutarci attraverso le [b]attività offline[/b]:
          spargere la voce ai tuoi amici dell\'esistenza del sito(più persone
          lo frequenteranno, più persone potranno contribuirne alla sua
          crescita), occuparvi del contatto con le aule, con i docenti...
      [/list]',
                                'Se quindi vi abbiamo convinto con queste poche e semplici parole e volete
        collaborare attivamente al progetto compilate questo questionario
        e vi contatteremo al più presto.'));

        //domande questionario
        $template->assign('question_PersonalInfo', 'Dati personali: ');
        $template
                ->assign('question_PersonalInfoData',
                        array('Nome', 'Cognome', 'E-mail', 'Telefono',
                                'Corso di Laurea'));
        $template
                ->assign('question_q1',
                        'Saresti disponibile a darci un piccolo contributo(di tempo) per il progetto?');
        $template
                ->assignUnicode('question_q1Answers',
                        array('una giornata alla settimana o più;',
                                'poche ore alla settimana;',
                                'pochi minuti alla settimana;'));
        $template
                ->assign('question_q2', 'Quanto tempo ti connetti a Internet?');
        $template
                ->assign('question_q2Answers',
                        array('quasi mai;', 'una volta alla settimana;',
                                'una volta al giorno;', 'vivo connesso;'));
        $template
                ->assignUnicode('question_q3',
                        'Quali di queste attività pensi di poter svolgere (anche più di una scelta)?');
        $template
                ->assignUnicode('question_q3AnswersMulti',
                        array(
                                'attività off-line(contatti con i docenti o studenti, reperimento materiale...);',
                                'moderatore
        (controllare che la gente non scriva cose non permesse...);',
                                'scrittura contenuti riguardanti i corsi che frequento;',
                                'testare le nuove versioni dei sevizi
        provandoli on-line;',
                                'elaborazione grafica di immagini (icone, scritte, ecc...);',
                                'aiutare nella progettazione e programmazione del sito;'));
        $template
                ->assign('question_PersonalNotes',
                        'Altre informazioni personali:');
        $template
                ->assign('question_Privacy',
                        'Acconsento al trattamento dei miei dati personali ai sensi del d. lgs. n. 196/2003;');
        $template->assign('question_Send', 'Invia');
        $template->assign('question_TitleAlt', 'Questionario');

        // valori default form
        $f3_nome = '';
        $f3_cognome = '';
        $f3_mail = '';
        $f3_tel = '';
        $f3_altro = '';
        $f3_cdl = '';
        $f3_offline = false;
        $f3_moderatore = false;
        $f3_contenuti = false;
        $f3_test = false;
        $f3_grafica = false;
        $f3_prog = false;
        $f3_tempo = NULL;
        $f3_internet = NULL;

        $f3_accept = false;

        if (array_key_exists('f3_submit', $_POST)) {
            //var_dump($_POST);
            $f3_accept = true;

            if (!array_key_exists('f3_nome', $_POST)
                    || !array_key_exists('f3_cognome', $_POST)
                    || !array_key_exists('f3_mail', $_POST)
                    || !array_key_exists('f3_tel', $_POST)
                    || !array_key_exists('f3_cdl', $_POST)
                    || !array_key_exists('f3_altro', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il form inviato non � valido',
                                'file' => __FILE__, 'line' => __LINE__));
                var_dump($f3_corsi_di_laurea);
                die();
                $f3_accept = false;
            }

            //nome
            if (strlen($_POST['f3_nome']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il nome indicato pu� essere massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_nome = $f3_nome = $_POST['f3_nome'];

            //cognome
            if (strlen($_POST['f3_cognome']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il cognome indicato pu� essere massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_cognome = $f3_cognome = $_POST['f3_cognome'];

            //telefono
            if ((strlen($_POST['f3_tel']) > 50)
                    || !preg_match('/^([0-9]{1,50})$/', $_POST['f3_tel'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il numero di cellulare indicato pu� essere massimo 20 cifre',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_tel = $f3_tel = $_POST['f3_tel'];

            //mail
            if (strlen($_POST['f3_mail']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\' indirizzo e-mail indicato pu� essere massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } elseif (!preg_match(
                    "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i",
                    $_POST['f3_mail'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Inserire un indirizzo e-mail valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_mail = $f3_mail = $_POST['f3_mail'];

            //altro
            $q3_altro = $f3_altro = $_POST['f3_altro'];

            //tempo
            if (!array_key_exists('f3_tempo', $_POST)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Indica quanto tempo utilizzi una connessione internet',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_tempo = $f3_tempo = $_POST['f3_tempo'];

            //internet
            if (!array_key_exists('f3_internet', $_POST)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Indica quanto tempo libero potresti dedicare al progetto',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_internet = $f3_internet = $_POST['f3_internet'];

            //privacy
            if (!array_key_exists('f3_privacy', $_POST)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'E\' necessario acconsentire al trattamento dei dati personali',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            }

            //attivit? offline check
            if (array_key_exists('f3_offline', $_POST)) {
                $q3_offline = 'S';
                $f3_offline = true;
            } else
                $q3_offline = 'N';

            //moderatore check
            if (array_key_exists('f3_moderatore', $_POST)) {
                $q3_moderatore = 'S';
                $f3_moderatore = true;
            } else
                $q3_moderatore = 'N';

            //stesura contenuti check
            if (array_key_exists('f3_contenuti', $_POST)) {
                $q3_contenuti = 'S';
                $f3_contenuti = true;
            } else
                $q3_contenuti = 'N';

            //test check
            if (array_key_exists('f3_test', $_POST)) {
                $q3_test = 'S';
                $f3_test = true;
            } else
                $q3_test = 'N';

            //grafica check
            if (array_key_exists('f3_grafica', $_POST)) {
                $q3_grafica = 'S';
                $f3_grafica = true;
            } else
                $q3_grafica = 'N';

            //progettazione check
            if (array_key_exists('f3_prog', $_POST)) {
                $q3_prog = 'S';
                $f3_prog = true;
            } else
                $q3_prog = 'N';

            //corso di laurea

            if (strlen($_POST['f3_cdl']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il corso di laurea indicato pu� essere massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f3_accept = false;
            } else
                $q3_cdl = $f3_cdl = $_POST['f3_cdl'];

        }

        // riassegna valori form
        $template->assign('f3_nome', $f3_nome);
        $template->assign('f3_cognome', $f3_cognome);
        $template->assign('f3_mail', $f3_mail);
        $template->assign('f3_tel', $f3_tel);
        $template->assign('f3_cdl', $f3_cdl);
        $template->assign('f3_altro', $f3_altro);
        $template->assign('f3_offline', $f3_offline);
        $template->assign('f3_moderatore', $f3_moderatore);
        $template->assign('f3_contenuti', $f3_contenuti);
        $template->assign('f3_test', $f3_test);
        $template->assign('f3_grafica', $f3_grafica);
        $template->assign('f3_prog', $f3_prog);
        $template->assign('f3_tempo', $f3_tempo);
        $template->assign('f3_internet', $f3_internet);

        //esecuzione operazioni accettazione del form
        if ($f3_accept == true) {
            //salvataggio form
            $db = $frontcontroller->getDbConnection('main');

            $q3_idQuestionario = $db->nextID('questionario_id_questionari');
            $q3_idUtente = $this->getSessionIdUtente();
            $query = 'INSERT INTO questionario (id_questionario, id_utente, data, nome, cognome, mail, telefono, cdl, tempo_disp, tempo_internet, attiv_offline, attiv_moderatore, attiv_contenuti, attiv_test, attiv_grafica, attiv_prog, altro) VALUES ( '
                    . $db->quote($q3_idQuestionario) . ', '
                    . $db->quote($q3_idUtente) . ', ' . $db->quote(time())
                    . ', ' . $db->quote($q3_nome) . ', '
                    . $db->quote($q3_cognome) . ', ' . $db->quote($q3_mail)
                    . ', ' . $db->quote($q3_tel) . ', ' . $db->quote($q3_cdl)
                    . ',' . $db->quote($q3_tempo) . ', '
                    . $db->quote($q3_internet) . ', ' . $db->quote($q3_offline)
                    . ', ' . $db->quote($q3_moderatore) . ', '
                    . $db->quote($q3_contenuti) . ', ' . $db->quote($q3_test)
                    . ', ' . $db->quote($q3_grafica) . ', '
                    . $db->quote($q3_prog) . ', ' . $db->quote($q3_altro)
                    . ');';
            $res = $db->query($query);
            if (DB::isError($res)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => DB::errorMessage($res),
                                'file' => __FILE__, 'line' => __LINE__));

                return false;
            }

            //invio mail notifica
            $session_user = $this->getSessionUser();

            $message = \Swift_Message::newInstance();

            $riceventi = $frontcontroller->getAppSetting('questionariReceiver');
            $array_riceventi = explode(';', $riceventi);
            foreach ($array_riceventi as $key => $value) {
                $message->addTo($value);
            }

            $message->setSubject('[UniversiBO] Nuovo questionario');
            $message->setBody('nome: ' . $f3_nome . "\n" . 'cognome: '
                    . $f3_cognome . "\n" . 'mail: ' . $f3_mail . "\n"
                    . 'telefono: ' . $f3_tel . "\n" . 'corso di laurea: '
                    . $f3_cdl . "\n" . 'username: '
                    . $session_user->getUsername() . "\n" . 'id_utente: '
                    . $q3_idUtente . "\n\n" . 'tempo_disponibile: ' . $f3_tempo
                    . "\n" . 'tempo_internet: ' . $f3_internet . "\n"
                    . 'attivita_offline: ' . $q3_offline . "\n"
                    . 'attivita_moderatore: ' . $q3_moderatore . "\n"
                    . 'attivita_contenuti: ' . $q3_contenuti . "\n"
                    . 'attivita_test: ' . $q3_test . "\n"
                    . 'attivita_grafica: ' . $q3_grafica . "\n"
                    . 'attivita_prog: ' . $q3_prog . "\n"
                    . 'altre_informazioni: ' . $f3_altro . "\n\n");

            //			var_dump($mail);die();
            if (!$frontcontroller->getMailer()->send($message)) {
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'Il questionario e` stato salvato ma e` stato impossibile inviare la notifica ai coordinatori',
                                'file' => __FILE__, 'line' => __LINE__));
            }
            $template
                    ->assignUnicode('question_thanks',
                            "Grazie per aver compilato il questionario, la tua richiesta è stata inoltrata ai ragazzi che si occupano del contatto dei nuovi collaboratori.\n Verrai ricontattatato da loro non appena possibile");

            return 'questionario_success';
        }

        return 'default';

    }
}