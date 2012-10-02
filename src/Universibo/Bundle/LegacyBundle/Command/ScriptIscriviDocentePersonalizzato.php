<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \DB;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * ScriptIscriviDocentePersonalizzato is an extension of UniversiboCommand class.
 *
 * Si occupa dell'iscrizione di nuovi docenti
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ScriptIscriviDocentePersonalizzato extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();
        $db = $fc->getDbConnection('main');

        $notifica = NOTIFICA_NONE;

        if (!array_key_exists('cod_doc', $_GET)
                || !array_key_exists('new_username', $_GET))
            die('Errore: mancano dei parametri');

        $res = &$db
                ->query(
                        'SELECT cod_doc, nome_doc, email FROM docente2 WHERE cod_doc = '
                                . $db->quote($_GET['cod_doc'])
                                . ' AND cod_doc NOT IN (SELECT cod_doc FROM docente
         WHERE 1=1)');

        $row = $res->fetch(\PDO::FETCH_NUM);

        $username = $_GET['new_username'];

        if (User::selectUserUsername($username) == false) {
            $randomPassword = User::generateRandomPassword();
            //$pippo = $fc->getAppSetting('defaultStyle');
            //var_dump($pippo);
            $new_user = new User(-1, 'ROLE_PROFESSOR', $username, $randomPassword,
                    $row[2], $notifica, 0, '', '',
                    $fc->getAppSetting('
            defaultStyle'));

            if ($new_user->insertUser() == false)
                die(
                        'Errore inserimento: username ' . $username
                                . ' | mail ' . $row[2]);

            $forum = $this->getContainer()->get('universibo_legacy.forum.api');
            $forum->insertUser($new_user);

            $query3 = 'INSERT INTO docente (id_utente, cod_doc, nome_doc) VALUES ('
                    . $new_user->getId() . ',' . $db->quote($row[0]) . ','
                    . $db->quote($row[1]) . '
            )';
            $res3 = $db->query($query3);
            if (DB::isError($res3))
                die($query3);

            //$query4 = 'INSERT INTO utente_canale (id_utente,id_canale,ultimo_accesso,ruolo,my_universibo,notifica,nome,nascosto)
            //      VALUES ('.$new_user->getId().','. $db->quote($row[0]) .','.$db->quote($row[1]).')';
            //$result = $db->query($query4);
            //if (DB::isError($res4)) die($query4);

            $mail = $fc->getMail();
            $mail->AddAddress($row[2]);

            echo $row[2] . "\n";

            $mail->Subject = "Registrazione UniversiBO";

            $mail->Body = "Gentile docente,\n"
                    . "Le abbiamo creato un account su UniversiBO.\n\n"
                    . "Il progetto portato avanti dalla comunita` degli studenti dell'universita` di Bologna collaborando con le strutture di Ateneo, si propone l
            a realizzazione di un sito web in grado di raccogliere ed integrare le informazioni ora disponibili in una moltitudine di siti e permettere lo scambio e la condivisione tra docen
            ti e studenti di informazioni su temi attinenti principalmente la didattica.\n\n"
                    . "Per accedere al sito utilizzi l'indirizzo "
                    . $fc->getAppSetting('rootUrl') . "\n\n"
                    . "Le informazioni per permetterle l'accesso ai servizi offerti sono:\n"
                    . "Username: " . $username . "\n" . "Password: "
                    . $randomPassword . "\n\n"
                    . "Questa password e' stata generata in modo casuale, nella sezione Impostazioni Personali del sito e' disponibile la funzionalita' per pote
            rla cambiare\n\n"
                    . "Andando nelle pagine dei suoi corsi in UniversiBO disporra' dei diritti che le permetteranno di usufruire di alcuni servizi:\n"
                    . "- Pubblicare notizie che verranno automaticamente notificate agli studenti interessati via e-mail o sms\n"
                    . "- Inserire file (slides, dispense, temi d'esame, etc.)\n"
                    . "- Pubblicare o inserire link verso altri siti con le informazioni sul corso: Programma, Obiettivi del Corso, Testi Consigliati, ecc..\n"
                    .
                    //"- E' attiva l'integrazione automatica con Uniwex o la gestione manuale delle informazioni riguardanti gli appelli d'esame\n".
                    "- Inserire una raccolta di link verso siti esterni contenenti informazioni attinenti al suo insegnamento\n"
                    . "- Interagire attivamente con gli studenti attraverso il forum\n"
                    . "Come usare questi servizi lo trova spiegato nella sezione \"Help\" .\n"
                    . "Attraverso il sito e` disponibile una funzionalita` che le permette inoltre di delegare queste attivita' anche ad altri utenti iscritti al
    sito di sua scelta.\n"
                    . "Se vuole iscrivere dei suoi \"assistenti\" risponda a questa mail con i loro nomi e indirizzi email ed uno username di loro gradimento.\n
    "
                    . "Provvederemo ad iscriverli al piu' presto e a dar loro i diritti opportuni nelle sue pagine.\n\n"
                    . "Per qualsiasi problema non esiti a contattarci\n"
                    . "Grazie per la disponibilita'\n\n"
                    . "Qualora avesse ricevuto questa e-mail per errore lo segnali rispondendo immediatamente a questo messaggio\n\n";

            if (!$mail->Send())
                die('email:' . $row2['last_id'] . ' - ' . $mail->ErrorInfo);
            echo $username . "sent" . "\n";
        } else {
            echo $username . ' - non iscritto' . "\n";
        }

        $res->free();

    }
}
