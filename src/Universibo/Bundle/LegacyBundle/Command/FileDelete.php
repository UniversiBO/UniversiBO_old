<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
/**
 * NewsDelete: elimina una notizia, mostra il form e gestisce la cancellazione
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileDelete extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template->assign('common_canaleURI', '/?do=ShowMyUniversiBO');
        $template->assign('common_langCanaleNome', 'indietro');

        $user = $this->getSessionUser();

        $referente = false;
        $moderatore = false;

        $user_ruoli = $user->getRuoli();

        if (!array_key_exists('id_file', $_GET)
                || !preg_match('/^([0-9]{1,9})$/', $_GET['id_file'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'id del file richiesto non e` valido',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $file = &FileItem::selectFileItem($_GET['id_file']);
        if ($file === false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Il file richiesto non e` presente su database",
                            'file' => __FILE__, 'line' => __LINE__));

        $autore = ($user->getIdUser() == $file->getIdUtente());

        if (array_key_exists('id_canale', $_GET)) {
            if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = &Canale::retrieveCanale($_GET['id_canale']);

            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();
            $template->assign('common_canaleURI', $canale->showMe());
            $template
                    ->assign('common_langCanaleNome',
                            'a ' . $canale->getTitolo());

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = &$user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }

            //controllo coerenza parametri
            $canali_file = $file->getIdCanali();
            if (!in_array($id_canale, $canali_file))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($id_canale);

            if (!($user->isAdmin() || $referente || ($moderatore && $autore)))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => "Non hai i diritti per eliminare il file\n La sessione potrebbe essere scaduta",
                                'file' => __FILE__, 'line' => __LINE__));

        } elseif (!($user->isAdmin() || $autore))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Non hai i diritti per eliminare il file\n La sessione potrebbe essere scaduta",
                            'file' => __FILE__, 'line' => __LINE__));

        $elenco_canali = array_keys($user_ruoli);
        //
        //		$ruoli_keys = array_keys($user_ruoli);
        //		$num_ruoli = count($ruoli_keys);
        //		for ($i = 0; $i < $num_ruoli; $i ++)
        //		{
        //			$elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        //		}
        //
        $file_canali = $file->getIdCanali();

        $f14_canale = array();
        $num_canali = count($file_canali);
        for ($i = 0; $i < $num_canali; $i++) {
            $id_current_canale = $file_canali[$i];
            $current_canale = &Canale::retrieveCanale($id_current_canale);
            $nome_current_canale = $current_canale->getTitolo();
            if (in_array($id_current_canale, $file->getIdCanali())) {
                $f14_canale[] = array('id_canale' => $id_current_canale,
                        'nome_canale' => $nome_current_canale,
                        'spunta' => 'true');
            }
        }

        $f14_accept = false;

        //postback

        if (array_key_exists('f14_submit', $_POST)) {

            $f14_accept = true;

            $f14_canale_app = array();
            //controllo diritti su ogni canale di cui ? richiesta la cancellazione
            if (array_key_exists('f14_canale', $_POST)) {
                foreach ($_POST['f14_canale'] as $key => $value) {
                    $diritti = $user->isAdmin()
                            || (array_key_exists($key, $user_ruoli)
                                    && ($user_ruoli[$key]->isReferente()
                                            || ($user_ruoli[$key]
                                                    ->isModeratore() && $autore)));
                    if (!$diritti) {
                        //$user_ruoli[$key]->getIdCanale();
                        $canale = &Canale::retrieveCanale($key);
                        Error::throwError(_ERROR_NOTICE,
                                array('id_utente' => $user->getIdUser(),
                                        'msg' => 'Non possiedi i diritti di eliminazione nel canale: '
                                                . $canale->getTitolo(),
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f14_accept = false;
                    } else
                        $f14_canale_app[$key] = $value;
                }
            } elseif (count($f14_canale) > 0) {
                $f14_accept = false;
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Devi selezionare almeno una pagina:',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
            }

        }

        //accettazione della richiesta
        if ($f14_accept == true) {
            //			var_dump($_POST['f14_canale'] );
            //			die();
            //cancellazione dai canali richiesti
            foreach ($f14_canale_app as $key => $value) {
                $file->removeCanale($key);
                //$canale = Canale::retrieveCanale($key);
            }

            $file->deleteFileItem();
            /**
             * @TODO elenco dei canali dai quali è stata effetivamente cancellata la notizia
             */
            $template
                    ->assignUnicode('fileDelete_langSuccess',
                            "Il file è stato cancellato con successo dalle pagine scelte.");

            return 'success';
        }

        //visualizza notizia
        //$param = array('id_notizie'=>array($_GET['id_news']), 'chk_diritti' => false );
        //$this->executePlugin('ShowNews', $param );

        $template
                ->assign('f14_langAction',
                        "Elimina il file dalle seguenti pagine:");
        $template->assign('f14_canale', $f14_canale);
        $template
                ->assign('fileDelete_flagCanali',
                        (count($f14_canale)) ? 'true' : 'false');

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
