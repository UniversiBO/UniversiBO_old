<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \DB;
use Universibo\Bundle\LegacyBundle\App\Constants;

use Universibo\Bundle\LegacyBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ScriptIscriviDocenti2 is an extension of UniversiboCommand class.
 *
 * Si occupa dell'iscrizione di nuovi docenti
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptIscriviDocenti2 extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $container = $this->getContainer();
        $db = $container->get('doctrine.dbal.default_connection');
        $userRepo = $container->get('universibo_legacy.repository.user');

        $notifica = Constants::NOTIFICA_NONE;

        $res = $db->executeQuery('SELECT cod_doc, nome_doc, email FROM docente2 WHERE cod_doc NOT IN (SELECT cod_doc FROM docente WHERE 1=1)');

        while (false !== ($row = $res->fetch())) {
            $exploded = explode('@', $row[2]);
            $username = $exploded[0];

            if (!$userRepo->usernameExists($username)) {
                $randomPassword = User::generateRandomPassword();
                //$pippo = $fc->getAppSetting('defaultStyle');
                //var_dump($pippo);
                $new_user = new User(-1, User::DOCENTE, $username,
                        $randomPassword, $row[2], $notifica, 0, '', '',
                        $fc->getAppSetting('defaultStyle'));

                if ($userRepo->insertUser($new_user) == false)
                    die(
                            'Errore inserimento: username ' . $username
                                    . ' | mail ' . $row[2]);

                $forum = $this->getContainer()->get('universibo_legacy.forum.api');
                $forum->insertUser($new_user, $randomPassword);

                $query3 = 'INSERT INTO docente (id_utente, cod_doc, nome_doc) VALUES ('
                        . $new_user->getIdUser() . ',' . $db->quote($row[0])
                        . ',' . $db->quote($row[1]) . ')';
                $res3 = $db->executeQuery($query3);

                //$query4 = 'INSERT INTO utente_canale (id_utente,id_canale,ultimo_accesso,ruolo,my_universibo,notifica,nome,nascosto)
                //	VALUES ('.$new_user->getIdUser().','. $db->quote($row[0]) .','.$db->quote($row[1]).')';
                //$result = $db->executeQuery($query4);

                $mail = $fc->getMail();
                $mail->AddAddress($row[2]);

                echo $row[2] . "\n";

                $mail->Subject = "Registrazione UniversiBO";

                $mail->Body = "Gentile docente,\n"
                        . "Le abbiamo creato un account su UniversiBO.\n\n"
                        . "Il progetto portato avanti dalla comunita` degli studenti dell'universita` di Bologna collaborando con le strutture di Ateneo, si propone la realizzazione di un sito web in grado di raccogliere ed integrare le informazioni ora disponibili in una moltitudine di siti e permettere lo scambio e la condivisione tra docenti e studenti di informazioni su temi attinenti principalmente la didattica.\n\n"
                        . "Per accedere al sito utilizzi l'indirizzo "
                        . $fc->getAppSetting('rootUrl') . "\n\n"
                        . "Le informazioni per permetterle l'accesso ai servizi offerti sono:\n"
                        . "Username: " . $username . "\n" . "Password: "
                        . $randomPassword . "\n\n"
                        . "Questa password e' stata generata in modo casuale, nella sezione Impostazioni Personali del sito e' disponibile la funzionalita' per poterla cambiare\n\n"
                        . "Andando nelle pagine dei suoi corsi in UniversiBO disporra' dei diritti che le permetteranno di usufruire di alcuni servizi:\n"
                        . "- Pubblicare notizie che verranno automaticamente notificate agli studenti interessati via e-mail o sms\n"
                        . "- Inserire file (slides, dispense, temi d'esame, etc.)\n"
                        . "- Pubblicare o inserire link verso altri siti con le informazioni sul corso: Programma, Obiettivi del Corso, Testi Consigliati, ecc..\n"
                        .
                        //"- E' attiva l'integrazione automatica con Uniwex o la gestione manuale delle informazioni riguardanti gli appelli d'esame\n".
                        "- Inserire una raccolta di link verso siti esterni contenenti informazioni attinenti al suo insegnamento\n"
                        . "- Interagire attivamente con gli studenti attraverso il forum\n"
                        . "Come usare questi servizi lo trova spiegato nella sezione \"Help\" .\n"
                        . "Attraverso il sito e` disponibile una funzionalita` che le permette inoltre di delegare queste attivita' anche ad altri utenti iscritti al sito di sua scelta.\n"
                        . "Se vuole iscrivere dei suoi \"assistenti\" risponda a questa mail con i loro nomi e indirizzi email ed uno username di loro gradimento.\n"
                        . "Provvederemo ad iscriverli al piu' presto e a dar loro i diritti opportuni nelle sue pagine.\n\n"
                        . "Per qualsiasi problema non esiti a contattarci\n"
                        . "Grazie per la disponibilita'\n\n"
                        . "Qualora avesse ricevuto questa e-mail per errore lo segnali rispondendo immediatamente a questo messaggio\n\n";

                // e-mail not sent anymore for bureaucracy issues
                //if (!$mail->Send())
                //    die('email:' . $row2['last_id'] . ' - ' . $mail->ErrorInfo);
                echo $username . " fake sent" . "\n";
            } else {
                echo $username . ' - non iscritto' . "\n";
            }
        }
    }
}
