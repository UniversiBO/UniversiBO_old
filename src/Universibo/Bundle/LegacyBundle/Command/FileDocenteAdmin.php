<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * fileDocenteAdmin: si occupa dell'inserimento di un file in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FileDocenteAdmin extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();
        $user = $this->getSessionUser();
        $user_ruoli = $user->getRuoli();

        if (!$user->isAdmin() && !$user->isDocente()) {
            Error::throwError(_ERROR_DEFAULT,
                    array(
                            'msg' => "Non hai i diritti necessari per accedere a questa pagina\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }
        /*		if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale'])) {
        Error :: throw (_ERROR_DEFAULT, array ('msg' => 'L\'id del canale richiesto non � valido', 'file' => __FILE__, 'line' => __LINE__));
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

        // valori default form
        $f40_files = '';
        $f40_canale = array();

        $elenco_canali = array();
        $id_canale = '';
        if (array_key_exists('id_canale', $_GET)) {
            if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = Canale::retrieveCanale($_GET['id_canale']);

            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();
            $template->assign('common_canaleURI', $canale->showMe());
            $template
                    ->assign('common_langCanaleNome', 'a '
                            . $canale->getTitolo());

        }

        $ruoli_keys = array_keys($user_ruoli);
        $num_ruoli = count($ruoli_keys);
        for ($i = 0; $i < $num_ruoli; $i++) {
            if ($user->isAdmin() || $user_ruoli[$ruoli_keys[$i]]->isReferente())
                $elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        }

        $elenco_canali_retrieve = array();
        $num_canali = count($elenco_canali);
        for ($i = 0; $i < $num_canali; $i++) {
            $id_current_canale = $elenco_canali[$i];
            $current_canale = Canale::retrieveCanale($id_current_canale);
            $elenco_canali_retrieve[$id_current_canale] = $current_canale;
            $didatticaCanale = PrgAttivitaDidattica::factoryCanale(
                    $id_current_canale);
            //			var_dump($didatticaCanale);
            $annoCorso = (count($didatticaCanale) > 0) ? $didatticaCanale[0]
                            ->getAnnoAccademico() : 'altro';
            $nome_current_canale = $current_canale->getTitolo();
            $f40_canale[$annoCorso][$id_current_canale] = array(
                    'nome' => $nome_current_canale,
                    'spunta' => ($id_current_canale == $id_canale) ? 'true'
                            : 'false');
            $listaFile = array();
            $lista = FileItem::selectFileItems(
                    FileItem::selectFileCanale($id_current_canale));
            usort($lista, array($this, '_compareFile'));
            foreach ($lista as $file)
                $listaFile[$file->getIdFile()] = array(
                        'nome' => $file->getTitolo(), 'spunta' => 'false');
            if (count($listaFile) > 0)
                $f40_files[$nome_current_canale] = $listaFile;
        }
        ksort($f40_files);
        krsort($f40_canale);
        $tot = count($f40_canale);
        $list_keys = array_keys($f40_canale);
        for ($i = 0; $i < $tot; $i++)
        //			var_dump($f40_canale[$i]);
            uasort($f40_canale[$list_keys[$i]], array($this, '_compareCanale'));

        //		var_dump($f40_files); die;
        $f40_accept = false;

        if (array_key_exists('f40_submit', $_POST)) {
            $f40_accept = true;
            $f40_canali_inserimento = array();
            $f40_file_inserimento = array();

            //			if ( !array_key_exists('f40_files', $_POST) ||
            //				 !array_key_exists('f40_canale', $_POST) )
            //			{
            ////				var_dump($_POST);die();
            //				Error :: throwError (_ERROR_DEFAULT, array ('msg' => 'Il form inviato non � valido', 'file' => __FILE__, 'line' => __LINE__));
            //				$f40_accept = false;
            //			}

            if (!array_key_exists('f40_files', $_POST)
                    || count($_POST['f40_files']) == 0) {
                Error::throwError(_ERROR_NOTICE,
                        array('msg' => 'Bisogna selezionare almeno un file',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f40_accept = false;
            } else {
                //controllo se i file appartengono ad un canale su cui ha diritto l'utente
                //			var_dump($_POST['f40_files'] );
                foreach ($_POST['f40_files'] as $key => $value) {
                    $fileTemp = &FileItem::selectFileItem($key);
                    $diritti = false;
                    //				var_dump($fileTemp); die;
                    // TODO controllo se fileTemp � effettivamente un oggetto di tipo FileItem
                    foreach ($fileTemp->getIdCanali() as $canaleId) {
                        //					var_dump($canaleId);
                        $diritti = $user->isAdmin()
                                || (array_key_exists($canaleId, $user_ruoli)
                                        && $user_ruoli[$canaleId]
                                                ->isReferente());
                        //					var_dump($diritti);
                        if ($diritti)
                            break;
                    }
                    if (!$diritti) {
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'Non possiedi diritti sufficienti sul file: '
                                                . $fileTemp->getNomeFile(),
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f40_accept = false;
                    } else
                        $f40_file_inserimento[$key] = $fileTemp;
                }
            }

            if (!array_key_exists('f40_canale', $_POST)
                    || count($_POST['f40_canale']) == 0) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Bisogna selezionare almeno una pagina',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f40_accept = false;
            } else {
                //controllo i diritti_su_tutti_i_canali su cui si vuole fare l'inserimento
                foreach ($_POST['f40_canale'] as $key => $value) {
                    // TODO controllo se value � effettivamente un oggetto di tipo Canale e se key � id valido
                    $diritti = $user->isAdmin()
                            || (array_key_exists($key, $user_ruoli)
                                    && $user_ruoli[$key]->isReferente());
                    if (!$diritti) {
                        //$user_ruoli[$key]->getIdCanale();
                        $canale = $elenco_canali_retrieve[$key];
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'Non possiedi i diritti di inserimento nel canale: '
                                                . $canale->getTitolo(),
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f40_accept = false;
                    }

                    $f40_canali_inserimento[] = $key;
                }
            }
            //esecuzione operazioni accettazione del form
            if ($f40_accept == true) {

                $transaction = $this->getContainer()->get('universibo_legacy.transaction');
                ignore_user_abort(1);
                $transaction->begin();

                //$num_canali = count($f40_canale);
                //var_dump($f40_canale);

                foreach ($f40_file_inserimento as $newFile) {
                    $canali_file = &$newFile->getIdCanali();
                    foreach ($f40_canali_inserimento as $key) {
                        //							var_dump($f40_canali_inserimento); die;
                        if (!in_array($key, $canali_file)) {
                            $newFile->addCanale($key);
                            $canaleTemp = Canale::retrieveCanale($key);
                            $canaleTemp->setUltimaModifica(time(), true);
                        }

                    }
                    $newFile->updateFileItem();
                }

                $transaction->commit();
                ignore_user_abort(0);

                return 'success';
            } else {
                //riassegno al form i valori validi
                //				echo 'qui';
                $listaIdFiles = array();
                $listaIdCanali = array();
                $listaIdFiles = array_keys($f40_file_inserimento);
                $listaIdCanali = array_values($f40_canali_inserimento);
                $files = $f40_files;
                $canali = $f40_canale;
                foreach ($files as $name => $item) {
                    $listaFile = array();
                    foreach ($item as $key => $value)
                        $listaFile[$key] = array('nome' => $value['nome'],
                                'spunta' => (in_array($key, $listaIdFiles)) ? 'true'
                                        : 'false');
                    $f40_files[$name] = $listaFile;
                }
                foreach ($canali as $year => $arr) {
                    $listaCanali = array();
                    foreach ($arr as $key => $value)
                        $listaCanali[$key] = array('nome' => $value['nome'],
                                'spunta' => (in_array($key, $listaIdCanali)) ? 'true'
                                        : 'false');
                    $f40_canale[$year] = $listaCanali;
                }
            }

        }
        //end if (array_key_exists('f40_submit', $_POST))

        $template->assign('f40_files', $f40_files);
        $template->assign('f40_canale', $f40_canale);

        // TODO aggiungere l'help
        // $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';

    }

    /**
     * Ordina la struttura dei canali
     *
     * @static
     * @private
     */
    public function _compareCanale($a, $b)
    {
        $nomea = strtolower($a['nome']);
        $nomeb = strtolower($b['nome']);

        return strnatcasecmp($nomea, $nomeb);
    }

    /**
     * Ordina la struttura dei file
     *
     * @static
     * @private
     */
    public function _compareFile($a, $b)
    {
        $nomea = strtolower($a->getTitolo());
        $nomeb = strtolower($b->getTitolo());
        if ($nomea > $nomeb)

            return +1;
        if ($nomea < $nomeb)

            return -1;
    }
}
