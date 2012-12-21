<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

/**
 * FileAdd: si occupa dell'inserimento di un file in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileStudentiEdit extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $channelRouter = $this->get('universibo_legacy.routing.channel');

        $krono = $frontcontroller->getKrono();

        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $file = $this->get('universibo_legacy.repository.files.file_item_studenti')->find($this->getRequest()->attributes->get('id_file'));

        if (!$file instanceof FileItem) {
            throw new NotFoundHttpException('File not found');
        }

        $template->assign('fileEdit_fileUri', $router->generate('universibo_legacy_file', array('id_file' => $file->getIdFile())));
        $file_canali = $file->getIdCanali();

        $canale = Canale::retrieveCanale($file_canali[0]);
        $template->assign('common_canaleURI', $channelRouter->generate($canale));
        $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());

        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;
        $moderatore = false;

        $autore = ($user->getId() == $file->getIdUtente());
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');

        $channelId = $this->getRequest()->get('id_canale');

        if ($channelId !== null) {
            if (!preg_match('/^([0-9]{1,9})$/', $channelId))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = Canale::retrieveCanale($channelId);
            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();
            $template->assign('common_canaleURI', $channelRouter->generate($canale));
            $template
                    ->assign('common_langCanaleNome',
                            'a ' . $canale->getTitolo());
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }
            //controllo coerenza parametri
            $canali_file = $file->getIdCanali();
            if (!in_array($id_canale, $canali_file))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));

            $elenco_canali = array($id_canale);

            //controllo diritti sul canale
            if (!($autore||$admin))
                throw new AccessDeniedHttpException('Not allowed to edit file');

        } elseif (!($admin || $autore))
            throw new AccessDeniedHttpException('Not allowed to edit file');

        // valori default form
        // $f24_file = '';
        $f24_titolo = $file->getTitolo();
        $f24_abstract = $file->getDescrizione();
        $f24_parole_chiave = $file->getParolechiave();
        $f24_categorie = FileItem::getCategorie();
        $f24_categoria = $file->getIdCategoria();
        $f24_tipi = FileItem::getTipi();
        $f24_tipo = $file->getIdTipoFile();
        $f24_data_inserimento = $file->getDataInserimento();
        $f24_permessi_download = $file->getPermessiDownload();
        $f24_permessi_visualizza = $file->getPermessiVisualizza();
        //$f24_password_enable = ($file->getPassword() != null);
        $f24_canale = array();
        //$f24_password = '';

        //prendo tutti i canali tra i ruoli più (??) il canale corrente (che per l'admin puo` essere diverso)
        $elenco_canali = $file->getIdCanali();
        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i<$num_canali; $i++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale = Canale::retrieveCanale($id_current_canale);
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$f24_canale[] = array ('nome_canale'=> $nome_current_canale);
        //		}

        $f24_accept = false;

        if (array_key_exists('f24_submit', $_POST)) {
            $f24_accept = true;

            if (!array_key_exists('f24_titolo', $_POST)
                    || !array_key_exists('f24_data_ins_gg', $_POST)
                    || !array_key_exists('f24_data_ins_mm', $_POST)
                    || !array_key_exists('f24_data_ins_aa', $_POST)
                    || !array_key_exists('f24_data_ins_ora', $_POST)
                    || !array_key_exists('f24_data_ins_min', $_POST)
                    || !array_key_exists('f24_abstract', $_POST)
                    || !array_key_exists('f24_parole_chiave', $_POST)
                    || !array_key_exists('f24_categoria', $_POST)
                    || !array_key_exists('f24_tipo', $_POST)
                    || !array_key_exists('f24_permessi_download', $_POST)
                    || !array_key_exists('f24_permessi_visualizza', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f24_accept = false;
            }

            //titolo
            if (strlen($_POST['f24_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif ($_POST['f24_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } else
                $f24_titolo = $_POST['f24_titolo'];

            //abstract
            $f24_abstract = $_POST['f24_abstract'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f24_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo giorno di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
                $checkdate_ins = false;
            } else
                $f24_data_ins_gg = $_POST['f24_data_ins_gg'];

            //f24_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f24_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo mese di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
                $checkdate_ins = false;
            } else
                $f24_data_ins_mm = $_POST['f24_data_ins_mm'];

            //f24_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f24_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo anno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f24_data_ins_aa'] < 1970
                    || $_POST['f24_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
                $checkdate_ins = false;
            } else
                $f24_data_ins_aa = $_POST['f24_data_ins_aa'];

            //f24_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f24_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif ($_POST['f24_data_ins_ora'] < 0
                    || $_POST['f24_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } else
                $f24_data_ins_ora = $_POST['f24_data_ins_ora'];

            //f24_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f24_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif ($_POST['f24_data_ins_min'] < 0
                    || $_POST['f24_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } else
                $f24_data_ins_min = $_POST['f24_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f24_data_ins_mm'],
                            $_POST['f24_data_ins_gg'],
                            $_POST['f24_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            }

            $f24_data_inserimento = mktime($_POST['f24_data_ins_ora'],
                    $_POST['f24_data_ins_min'], "0", $_POST['f24_data_ins_mm'],
                    $_POST['f24_data_ins_gg'], $_POST['f24_data_ins_aa']);

            //abstract
            if (strlen($_POST['f24_abstract']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'La descrizione/abstract del file deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif ($_POST['f24_abstract'] == '') {
                $f24_abstract = $f24_titolo;
            } else
                $f24_abstract = $_POST['f24_abstract'];

            //parole chiave
            $f24_parole_chiave = array();
            if ($_POST['f24_parole_chiave'] != '') {
                $parole_chiave = explode("\r\n", $_POST['f24_parole_chiave']);

                foreach ($parole_chiave as $parola) {
                    if (strlen($parola > 40)) {
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'La lunghezza massima di una parola chiave e` di 40 caratteri',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f24_accept = false;
                    } else {
                        if ($parola != '')
                            $f24_parole_chiave[] = $parola;
                    }
                }

                if (count($f24_parole_chiave) > 4) {
                    var_dump($f24_parole_chiave);
                    Error::throwError(_ERROR_NOTICE,
                            array(
                                    'msg' => 'Si possono inserire al massimo 4 parole chiave',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f24_accept = false;
                }
            }

            //categoria
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f24_categoria'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo categoria non e` ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif (!array_key_exists($_POST['f24_categoria'], $f24_categorie)) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'La categoria inviata contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } else
                $f24_categoria = $_POST['f24_categoria'];

            //tipi
            if (!preg_match('/^([0-9]{1,9})$/', $_POST['f24_tipo'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo tipo non e` ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif (!array_key_exists($_POST['f24_tipo'], $f24_tipi)) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il tipo inviato contiene un valore non ammissibile',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } else
                $f24_tipo = $_POST['f24_tipo'];

            //permessi_download
            if (!preg_match('/^([0-9]{1,3})$/', $_POST['f24_permessi_download'])) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f24_accept = false;
            } elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                if ($_POST['f24_permessi_download'] < 0
                        || $_POST['f24_permessi_download'] > LegacyRoles::ALL) {
                    Error::throwError(_ERROR_NOTICE,
                            array(
                                    'msg' => 'Il valore dei diritti di download non e` ammessibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f24_accept = false;
                }
                $f24_permessi_download = $_POST['f24_permessi_download'];
            } else {
                if ($_POST['f24_permessi_download'] != LegacyRoles::ALL
                        && $_POST['f24_permessi_download']
                                != ('ROLE_STUDENT' | 'ROLE_PROFESSOR'
                                        | 'ROLE_TUTOR' | 'ROLE_STAFF'
                                        | 'ROLE_MODERATOR' | 'ROLE_ADMIN')) {
                    Error::throwError(_ERROR_NOTICE,
                            array(
                                    'msg' => 'Il valore dei diritti di download non e` ammissibile',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f24_accept = false;
                }
                $f24_permessi_download = $_POST['f24_permessi_download'];

            }

            $edit_password = true;
            $f24_password = null;

            //e i permessi di visualizzazione??
            // li prendo uguali a quelli del canale,
            if ($canale instanceof Canale)
                $f24_permessi_visualizza = $canale->getPermessi();
            else
                $f24_permessi_visualizza = LegacyRoles::ALL;
            // eventualmente dare la possibilit? all'admin di metterli diversamente

            //esecuzione operazioni accettazione del form
            if ($f24_accept == true) {

                $transaction = $this->getContainer()->get('universibo_legacy.transaction');
                ignore_user_abort(1);
                $transaction->begin();

                $file->setPermessiDownload($f24_permessi_download);
                $file->setPermessiVisualizza($f24_permessi_visualizza);
                $file->setTitolo($f24_titolo);
                $file->setDescrizione($f24_abstract);
                $file->setDataInserimento($f24_data_inserimento);
                $file->setIdCategoria($f24_categoria);
                $file->setIdTipoFile($f24_tipo);
                if ($edit_password)
                    $file
                            ->setPassword(
                                    ($f24_password == null) ? $f24_password
                                            : FileItem::passwordHashFunction(
                                                    $f24_password));

                $file->updateFileItem();
                $file->setParoleChiave($f24_parole_chiave);

                foreach ($elenco_canali as $value) {
                    $canale = Canale::retrieveCanale($value);
                    $canale->setUltimaModifica(time(), true);
                }

                $transaction->commit();
                ignore_user_abort(0);

                return 'success';
            }

        }
        //end if (array_key_exists('f24_submit', $_POST))

        // resta da sistemare qui sotto, fare il form e fare debugging

        $template->assign('f24_titolo', $f24_titolo);
        $template->assign('f24_abstract', $f24_abstract);
        $template->assign('f24_parole_chiave', $f24_parole_chiave);
        $template->assign('f24_categoria', $f24_categoria);
        $template->assign('f24_categorie', $f24_categorie);
        $template->assign('f24_tipo', $f24_tipo);
        $template->assign('f24_tipi', $f24_tipi);
        $template->assign('f24_abstract', $f24_abstract);
        $template->assign('f24_canale', $f24_canale);
        $template
                ->assign('fileEdit_flagCanali',
                        (count($f24_canale)) ? 'true' : 'false');

        //		$template->assign('f24_password', $f24_password);
        //		$template->assign('f24_password_confirm', $f24_password);
        //		$template->assign('f24_password_enable', ($f24_password_enable) ? 'true' : 'false' );
        $template->assign('f24_permessi_download', $f24_permessi_download);
        $template->assign('f24_permessi_visualizza', $f24_permessi_visualizza);
        $template
                ->assign('f24_data_ins_gg',
                        $krono->k_date('%j', $f24_data_inserimento));
        $template
                ->assign('f24_data_ins_mm',
                        $krono->k_date('%m', $f24_data_inserimento));
        $template
                ->assign('f24_data_ins_aa',
                        $krono->k_date('%Y', $f24_data_inserimento));
        $template
                ->assign('f24_data_ins_ora',
                        $krono->k_date('%H', $f24_data_inserimento));
        $template
                ->assign('f24_data_ins_min',
                        $krono->k_date('%i', $f24_data_inserimento));

        //$this->executePlugin('ShowTopic', array('reference' => 'filestudenti'));
        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';

    }
}
