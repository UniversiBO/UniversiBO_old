<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

/**
 * FileAdd: si occupa dell'inserimento di un file in un canale
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileEdit extends FileCommon
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $krono = $frontcontroller->getKrono();
        $router = $this->get('router');

        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $userId = $user instanceof User ? $user->getId() : 0;

        $file = $this->get('universibo_legacy.repository.files.file_item')->find($this->getRequest()->attributes->get('id_file'));

        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found');
        }

        $template->assign('fileEdit_fileUri', $router->generate('universibo_legacy_file', array('id_file' => $file->getIdFile())));

        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        $autore = $user instanceof User && ($user->getId() == $file->getIdUtente());

        $canale = $this->getRequestCanale(false);
        if ($canale instanceof Canale) {
            $id_canale = $canale->getIdCanale();
            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $channelRouter = $this->get('universibo_legacy.routing.channel');
            $template->assign('common_canaleURI', $channelRouter->generate($canale));
            $template->assign('common_langCanaleNome', 'a '. $canale->getTitolo());
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }
            //controllo coerenza parametri
            $canali_file = $file->getIdCanali();
            if (!in_array($id_canale, $canali_file))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $userId,
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($id_canale);

            //controllo diritti sul canale
            if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore))) {
                throw new AccessDeniedHttpException('Not allowed to edit file');
            }
        } elseif (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $autore))
            throw new AccessDeniedHttpException('Not allowed to edit file');

        // valori default form
        // $f13_file = '';
        $f13_titolo = $file->getTitolo();
        $f13_abstract = $file->getDescrizione();
        $f13_parole_chiave = $file->getParolechiave();
        $f13_categorie = FileItem::getCategorie();
        $f13_categoria = $file->getIdCategoria();
        $f13_tipi = FileItem::getTipi();
        $f13_tipo = $file->getIdTipoFile();
        $f13_data_inserimento = $file->getDataInserimento();
        $f13_permessi_download = $file->getPermessiDownload();
        $f13_permessi_visualizza = $file->getPermessiVisualizza();
        $f13_password_enable = ($file->getPassword() != null);
        $f13_canale = array();
        $f13_password = '';

        //prendo tutti i canali tra i ruoli più (??) il canale corrente (che per l'admin puo` essere diverso)
        $elenco_canali = $file->getIdCanali();
        $num_canali = count($elenco_canali);
        for ($i = 0; $i < $num_canali; $i++) {
            $id_current_canale = $elenco_canali[$i];
            $current_canale = Canale::retrieveCanale($id_current_canale);
            $nome_current_canale = $current_canale->getTitolo();
            $f13_canale[] = array('nome_canale' => $nome_current_canale);
        }

        $f13_accept = false;

        if (array_key_exists('f13_submit', $_POST)) {
            $f13_accept = true;

            if (!array_key_exists('f13_titolo', $_POST)
                    || !array_key_exists('f13_data_ins_gg', $_POST)
                    || !array_key_exists('f13_data_ins_mm', $_POST)
                    || !array_key_exists('f13_data_ins_aa', $_POST)
                    || !array_key_exists('f13_data_ins_ora', $_POST)
                    || !array_key_exists('f13_data_ins_min', $_POST)
                    || !array_key_exists('f13_abstract', $_POST)
                    || !array_key_exists('f13_parole_chiave', $_POST)
                    || !array_key_exists('f13_categoria', $_POST)
                    || !array_key_exists('f13_tipo', $_POST)
                    || !array_key_exists('f13_permessi_download', $_POST)
                    || !array_key_exists('f13_permessi_visualizza', $_POST)
                    || !array_key_exists('f13_password', $_POST)
                    || !array_key_exists('f13_password_confirm', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f13_accept = false;
            }

            //titolo
            if (strlen($_POST['f13_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif ($_POST['f13_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } else
                $f13_titolo = $_POST['f13_titolo'];

            //abstract
            $f13_abstract = $_POST['f13_abstract'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f13_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo giorno di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
                $checkdate_ins = false;
            } else
                $f13_data_ins_gg = $_POST['f13_data_ins_gg'];

            //f13_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f13_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo mese di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
                $checkdate_ins = false;
            } else
                $f13_data_ins_mm = $_POST['f13_data_ins_mm'];

            //f13_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f13_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo anno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f13_data_ins_aa'] < 1970
                    || $_POST['f13_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
                $checkdate_ins = false;
            } else
                $f13_data_ins_aa = $_POST['f13_data_ins_aa'];

            //f13_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f13_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif ($_POST['f13_data_ins_ora'] < 0
                    || $_POST['f13_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } else
                $f13_data_ins_ora = $_POST['f13_data_ins_ora'];

            //f13_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f13_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif ($_POST['f13_data_ins_min'] < 0
                    || $_POST['f13_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } else
                $f13_data_ins_min = $_POST['f13_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f13_data_ins_mm'],
                            $_POST['f13_data_ins_gg'],
                            $_POST['f13_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            }

            $f13_data_inserimento = mktime($_POST['f13_data_ins_ora'],
                    $_POST['f13_data_ins_min'], "0", $_POST['f13_data_ins_mm'],
                    $_POST['f13_data_ins_gg'], $_POST['f13_data_ins_aa']);

            //abstract
            if (strlen($_POST['f13_abstract']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La descrizione/abstract del file deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif ($_POST['f13_abstract'] == '') {
                $f13_abstract = $f13_titolo;
            } else
                $f13_abstract = $_POST['f13_abstract'];

            //parole chiave
            $f13_parole_chiave = array();
            if ($_POST['f13_parole_chiave'] != '') {
                $parole_chiave = preg_split('/((?<!\\\|\r)\n)|((?<!\\\)\r\n)/', $_POST['f13_parole_chiave']);

                foreach ($parole_chiave as $parola) {
                    if (strlen($parola > 40)) {
                        Error::throwError(_ERROR_NOTICE,
                                array('id_utente' => $user->getId(),
                                        'msg' => 'La lunghezza massima di una parola chiave e` di 40 caratteri',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f13_accept = false;
                    } else {
                        if ($parola != '')
                            $f13_parole_chiave[] = $parola;
                    }
                }

                if (count($f13_parole_chiave) > 4) {
                    var_dump($f13_parole_chiave);
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Si possono inserire al massimo 4 parole chiave',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f13_accept = false;
                }
            }

            //categoria
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f13_categoria'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo categoria non e` ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif (!array_key_exists($_POST['f13_categoria'], $f13_categorie)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La categoria inviata contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } else
                $f13_categoria = $_POST['f13_categoria'];

            //tipi
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f13_tipo'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo tipo non e` ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } elseif (!array_key_exists($_POST['f13_tipo'], $f13_tipi)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il tipo inviato contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f13_accept = false;
            } else
                $f13_tipo = $_POST['f13_tipo'];

            $f13_permessi_download = $_POST['f13_permessi_download'];
            $this->validateDownloadPermissions($f13_permessi_download, $user, $template);

            $edit_password = true;
            //password
            if (array_key_exists('f13_password_enable', $_POST)) {
                if ($_POST['f13_password'] != $_POST['f13_password_confirm']) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La password e il campo di verifica non corrispondono',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f13_accept = false;
                } elseif ($file->getPassword() == null
                        && $_POST['f13_password'] == '') {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La password inserita e` vuota',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f13_accept = false;
                } elseif ($file->getPassword() != null
                        && $_POST['f13_password'] == '') {
                    $edit_password = false;
                } else
                    $f13_password = $_POST['f13_password'];
            } else {
                $f13_password = null;
            }

            //e i permessi di visualizzazione??
            // li prendo uguali a quelli del canale,
            if ($canale instanceof Canale)
                $f13_permessi_visualizza = $canale->getPermessi();
            else
                $f13_permessi_visualizza = LegacyRoles::ALL;
            // eventualmente dare la possibilità all'admin di metterli diversamente

            //esecuzione operazioni accettazione del form
            if ($f13_accept == true) {

                $transaction = $this->getContainer()->get('universibo_legacy.transaction');
                ignore_user_abort(1);
                $transaction->begin();

                $file->setPermessiDownload($f13_permessi_download);
                $file->setPermessiVisualizza($f13_permessi_visualizza);
                $file->setTitolo($f13_titolo);
                $file->setDescrizione($f13_abstract);
                $file->setDataInserimento($f13_data_inserimento);
                $file->setIdCategoria($f13_categoria);
                $file->setIdTipoFile($f13_tipo);
                if ($edit_password)
                    $file
                            ->setPassword(
                                    ($f13_password == null) ? $f13_password
                                            : FileItem::passwordHashFunction(
                                                    $f13_password));

                $file->updateFileItem();
                $file->setParoleChiave($f13_parole_chiave);

                foreach ($elenco_canali as $value) {
                    $canale = Canale::retrieveCanale($value);
                    $canale->setUltimaModifica(time(), true);
                }

                $transaction->commit();
                ignore_user_abort(0);

                return 'success';
            }

        }
        //end if (array_key_exists('f13_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f13_titolo', $f13_titolo);
        $template->assign('f13_abstract', $f13_abstract);
        $template->assign('f13_parole_chiave', $f13_parole_chiave);
        $template->assign('f13_categoria', $f13_categoria);
        $template->assign('f13_categorie', $f13_categorie);
        $template->assign('f13_tipo', $f13_tipo);
        $template->assign('f13_tipi', $f13_tipi);
        $template->assign('f13_abstract', $f13_abstract);
        $template->assign('f13_canale', $f13_canale);
        $template
                ->assign('fileEdit_flagCanali',
                        (count($f13_canale)) ? 'true' : 'false');

        $template->assign('f13_password', $f13_password);
        $template->assign('f13_password_confirm', $f13_password);
        $template
                ->assign('f13_password_enable',
                        ($f13_password_enable) ? 'true' : 'false');
        $template->assign('f13_permessi_download', $f13_permessi_download);
        $template->assign('f13_permessi_visualizza', $f13_permessi_visualizza);
        $template
                ->assign('f13_data_ins_gg',
                        $krono->k_date('%j', $f13_data_inserimento));
        $template
                ->assign('f13_data_ins_mm',
                        $krono->k_date('%m', $f13_data_inserimento));
        $template
                ->assign('f13_data_ins_aa',
                        $krono->k_date('%Y', $f13_data_inserimento));
        $template
                ->assign('f13_data_ins_ora',
                        $krono->k_date('%H', $f13_data_inserimento));
        $template
                ->assign('f13_data_ins_min',
                        $krono->k_date('%i', $f13_data_inserimento));

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';

    }
}
