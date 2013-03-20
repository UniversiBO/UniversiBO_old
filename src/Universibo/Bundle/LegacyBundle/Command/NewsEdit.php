<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;

/**
 * NewsEdit: si occupa della modifica di una news in un canale
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class NewsEdit extends CanaleCommand
{

    /**
     * Deve stampare "La notizia ? gi? presente nei seguenti canali"
     */
    public function execute()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $canale = $this->getRequestCanale();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $id_canale = $canale->getIdCanale();

        //diritti
        $referente = false;
        $moderatore = false;

        $id_news = $this->getRequest()->attributes->get('id_news');

        if ($canale->getServizioNews() == false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => "Il servizio news e` disattivato",
                            'file' => __FILE__, 'line' => __LINE__));

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];

            $referente = $ruolo->isReferente();
            $moderatore = $ruolo->isModeratore();
        }

        $news = NewsItem::selectNewsItem($id_news);
        if ($news == false)
            Error::throwError(_ERROR_DEFAULT,
                    array(
                            'msg' => 'L\'id della notizia richiesta '
                                    . $id_news . ' non e` valido',
                            'file' => __FILE__, 'line' => __LINE__));

        $autore = ($user->getId() == $news->getIdUtente());

        //controllo coerenza parametri
        $canali_news = $news->getIdCanali();
        if (!in_array($id_canale, $canali_news))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => 'I parametri passati non sono coerenti',
                            'file' => __FILE__, 'line' => __LINE__));

        if (!($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $autore)))
            throw new AccessDeniedHttpException('Not allowed to edit news');

        $param = array('id_notizie' => array($id_news),
                'chk_diritti' => false);
        $this->executePlugin('ShowNews', $param);

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $krono = $frontcontroller->getKrono();
        $data_inserimento = $news->getDataIns();
        // valori default form
        $f8_titolo = $news->getTitolo();
        $f8_data_ins_gg = $krono->k_date('%j', $data_inserimento);
        $f8_data_ins_mm = $krono->k_date('%m', $data_inserimento);
        $f8_data_ins_aa = $krono->k_date('%Y', $data_inserimento);
        $f8_data_ins_ora = $krono->k_date('%H', $data_inserimento);
        $f8_data_ins_min = $krono->k_date('%i', $data_inserimento);
        $f8_data_scad_gg = '';
        $f8_data_scad_mm = '';
        $f8_data_scad_aa = '';
        $f8_data_scad_ora = '';
        $f8_data_scad_min = '';
        $f8_testo = $news->getNotizia();
        $f8_urgente = $news->isUrgente();
        $f8_scadenza = $news->getDataScadenza() != null;

        if ($f8_scadenza === true) {
            $data_scadenza = $news->getDataScadenza();
            $f8_data_scad_gg = $krono->k_date('%j', $data_scadenza);
            $f8_data_scad_mm = $krono->k_date('%m', $data_scadenza);
            $f8_data_scad_aa = $krono->k_date('%Y', $data_scadenza);
            $f8_data_scad_ora = $krono->k_date('%H', $data_scadenza);
            $f8_data_scad_min = $krono->k_date('%i', $data_scadenza);
        }

        //		$elenco_canali = array ($id_canale);
        //		$ruoli_keys = array_keys($user_ruoli);
        //		$num_ruoli = count($ruoli_keys);
        //		for ($i = 0; $i < $num_ruoli; $i ++)
        //		{
        //			if ($id_canale != $ruoli_keys[$i])
        //				$elenco_canali[] = $user_ruoli[$ruoli_keys[$i]]->getIdCanale();
        //		}
        //
        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i < $num_canali; $i ++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale =  Canale :: retrieveCanale($id_current_canale);
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$spunta = (in_array($id_current_canale, $news->getIdCanali())) ? 'true' : 'false';
        //			$f8_canale[] = array ('id_canale' => $id_current_canale, 'nome_canale' => $nome_current_canale, 'spunta' => $spunta);
        //		}

        $lista_canali = $news->getIdCanali();
        $num_canali = count($lista_canali);
        for ($i = 0; $i < $num_canali; $i++) {
            $id_current_canale = $lista_canali[$i];
            $current_canale = Canale::retrieveCanale($id_current_canale);
            $nome_current_canale = $current_canale->getTitolo();
            $f8_canale[] = array('nome_canale' => $nome_current_canale);
        }

        $f8_accept = false;

        if (array_key_exists('f8_submit', $_POST)) {
            $f8_accept = true;

            if (!array_key_exists('f8_titolo', $_POST)
                    || !array_key_exists('f8_data_ins_gg', $_POST)
                    || !array_key_exists('f8_data_ins_mm', $_POST)
                    || !array_key_exists('f8_data_ins_aa', $_POST)
                    || !array_key_exists('f8_data_ins_ora', $_POST)
                    || !array_key_exists('f8_data_ins_min', $_POST)
                    || !array_key_exists('f8_data_scad_gg', $_POST)
                    || !array_key_exists('f8_data_scad_mm', $_POST)
                    || !array_key_exists('f8_data_scad_aa', $_POST)
                    || !array_key_exists('f8_data_scad_ora', $_POST)
                    || !array_key_exists('f8_data_scad_min', $_POST)
                    || !array_key_exists('f8_testo', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f8_accept = false;
            }

            //titolo
            if (strlen($_POST['f8_titolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } elseif ($_POST['f8_titolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il titolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } else
                $f8_titolo = $_POST['f8_titolo'];

            $checkdate_ins = true;
            //data_ins_gg
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_ins_gg'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo giorno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
                $checkdate_ins = false;
            } else
                $f8_data_ins_gg = $_POST['f8_data_ins_gg'];

            //f8_data_ins_mm
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_ins_mm'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo mese di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
                $checkdate_ins = false;
            } else
                $f8_data_ins_mm = $_POST['f8_data_ins_mm'];

            //f8_data_ins_aa
            if (!preg_match('/^([0-9]{4})$/', $_POST['f8_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo anno di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
                $checkdate_ins = false;
            } elseif ($_POST['f8_data_ins_aa'] < 1970
                    || $_POST['f8_data_ins_aa'] > 2032) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
                $checkdate_ins = false;
            } else
                $f8_data_ins_aa = $_POST['f8_data_ins_aa'];

            //f8_data_ins_ora
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_ins_ora'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } elseif ($_POST['f8_data_ins_ora'] < 0
                    || $_POST['f8_data_ins_ora'] > 23) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } else
                $f8_data_ins_ora = $_POST['f8_data_ins_ora'];

            //f8_data_ins_min
            if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_ins_min'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } elseif ($_POST['f8_data_ins_min'] < 0
                    || $_POST['f8_data_ins_min'] > 59) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } else
                $f8_data_ins_min = $_POST['f8_data_ins_min'];

            if ($checkdate_ins == true
                    && !checkdate($_POST['f8_data_ins_mm'],
                            $_POST['f8_data_ins_gg'], $_POST['f8_data_ins_aa'])) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La data di inserimento specificata non esiste',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            }

            $data_inserimento = mktime($_POST['f8_data_ins_ora'],
                    $_POST['f8_data_ins_min'], "0", $_POST['f8_data_ins_mm'],
                    $_POST['f8_data_ins_gg'], $_POST['f8_data_ins_aa']);
            $data_scadenza = NULL;

            if (array_key_exists('f8_scadenza', $_POST)) {

                $f8_scadenza = true;
                $checkdate_scad = true;
                //data_scad_gg
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_scad_gg'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo giorno di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                    $checkdate_scad = false;
                } else
                    $f8_data_scad_gg = $_POST['f8_data_scad_gg'];

                //f8_data_scad_mm
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_scad_mm'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo mese di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                    $checkdate_scad = false;
                } else
                    $f8_data_scad_mm = $_POST['f8_data_scad_mm'];

                //f8_data_scad_aa
                if (!preg_match('/^([0-9]{4})$/', $_POST['f8_data_scad_aa'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo anno di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => $template));
                    $f8_accept = false;
                    $checkdate_scad = false;
                } elseif ($_POST['f8_data_scad_aa'] < 1970
                        || $_POST['f8_data_scad_aa'] > 2032) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo anno di inserimento deve essere compreso tra il 1970 e il 2032',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => $template));
                    $f8_accept = false;
                    $checkdate_scad = false;
                } else
                    $f8_data_scad_aa = $_POST['f8_data_scad_aa'];

                //f8_data_scad_ora
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_scad_ora'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo ora di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                } elseif ($_POST['f8_data_scad_ora'] < 0
                        || $_POST['f8_data_scad_ora'] > 23) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 23',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                } else
                    $f8_data_scad_ora = $_POST['f8_data_scad_ora'];

                //f8_data_scad_min
                if (!preg_match('/^([0-9]{1,2})$/', $_POST['f8_data_scad_min'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il formato del campo minuto di inserimento non \u00e8 valido',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                } elseif ($_POST['f8_data_scad_min'] < 0
                        || $_POST['f8_data_scad_min'] > 59) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'Il campo ora di inserimento deve essere compreso tra 0 e 59',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                } else
                    $f8_data_scad_min = $_POST['f8_data_scad_min'];

                if ($checkdate_scad == true
                        && !checkdate($_POST['f8_data_scad_mm'],
                                $_POST['f8_data_scad_gg'],
                                $_POST['f8_data_scad_aa'])) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La data di scadenza specificata non esiste',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                }

                //scadenza posteriore a inserimento
                $data_scadenza = mktime($_POST['f8_data_scad_ora'],
                        $_POST['f8_data_scad_min'], "0",
                        $_POST['f8_data_scad_mm'], $_POST['f8_data_scad_gg'],
                        $_POST['f8_data_scad_aa']);

                if ($data_scadenza < $data_inserimento) {
                    Error::throwError(_ERROR_NOTICE,
                            array('id_utente' => $user->getId(),
                                    'msg' => 'La data di scadenza e` minore della data di inserimento',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));
                    $f8_accept = false;
                }

            }

            //testo
            if (strlen($_POST['f8_testo']) > 3000) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il testo della notizia deve essere inferiore ai 3000 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } elseif ($_POST['f8_testo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il testo della notizia deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f8_accept = false;
            } else
                $f8_testo = $_POST['f8_testo'];

            //flag urgente
            if (array_key_exists('f8_urgente', $_POST)) {
                $f8_urgente = true;
            }

            //diritti_su_tutti_i_canali
            //			if (array_key_exists('f8_canale', $_POST))
            //			{
            //				foreach ($_POST['f8_canale'] as $key => $value)
            //				{
            //					$diritti = $this->get('security.context')->isGranted('ROLE_ADMIN') || (array_key_exists($key, $user_ruoli) && ($user_ruoli[$key]->isReferente() || $user_ruoli[$key]->isModeratore()));
            //					if (!$diritti)
            //					{
            //						if(!preg_match('/^([0-9]{1,9})$/', $key))
            //						{
            //							Error :: throwError(_ERROR_DEFAULT, array ('msg' => 'Il form inviato non ? valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => true));
            //						}
            //						//$user_ruoli[$key]->getIdCanale();
            //						$canale =  Canale :: retrieveCanale($key);
            //						Error :: throwError(_ERROR_NOTICE, array ('msg' => 'Non possiedi i diritti di modifica nel canale: '.$canale->getTitolo(), 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
            //						$f8_accept = false;
            //					}
            //				}
            //			}
            //			else
            //			{
            //				$f8_accept = false;
            //				Error :: throwError(_ERROR_NOTICE, array ('msg' => 'Devi selezionare almeno un canale', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
            //			}

            //esecuzione operazioni accettazione del form
            if ($f8_accept == true) {
                //var_dump($f8_testo);
                $news->setTitolo($f8_titolo);
                $news->setDataIns($data_inserimento);
                $news->setDataScadenza($data_scadenza);
                $news->setUrgente($f8_urgente);
                $news->setNotizia($f8_testo);
                $news->setUltimaModifica(time());
                //$news->setIdUtente($user->getId());

                $news->updateNewsItem();

                //$num_canali = count($f8_canale);
                //var_dump($f8_canale);
                //var_dump($_POST['f8_canale']);

                //				$num_canali = count($elenco_canali);
                //				for ($i = 0; $i < $num_canali; $i ++)
                //				{
                //					$id_current_canale = $elenco_canali[$i];
                //					$current_canale =  Canale :: retrieveCanale($id_current_canale);
                //					$nome_current_canale = $current_canale->getTitolo();
                //					foreach ($_POST['f8_canale'] as $key => $value)
                //					{
                //						/*$news->addCanale($key);
                //						$canale = Canale::retrieveCanale($key);
                //						$canale->setUltimaModifica(time(), true);*/
                //						$spunta = ($id_current_canale == $key) ? 'true' : 'false';
                //						if ($spunta == 'true')
                //							break;
                //					}
                //					$spunta = (array_key_exists($id_current_canale, $_POST['f8_canale'])) ? 'true' : 'false';
                //					$f8_canale[] = array ('id_canale' => $id_current_canale, 'nome_canale' => $nome_current_canale, 'spunta' => $spunta);
                //				}
                //				foreach ($f8_canale as $key => $value)
                //				{
                //					if ($value['spunta'] == 'true') $news->addCanale($value['id_canale']);
                //					$canale = Canale::retrieveCanale($value['id_canale']);
                //					$canale->setUltimaModifica(time(), true);
                //
                //				}

                /**
                 * @todo l'ultima modifica influenza tutti i canali
                 */
                $canale->setUltimaModifica(time(), true);

                $template
                        ->assign('NewsEdit_langSuccess',
                                "La notizia è stata modificata con successo.");

                return 'success';
            }

        } //end if (array_key_exists('f8_submit', $_POST))

        $template->assign('f8_titolo', $f8_titolo);
        $template->assign('f8_data_ins_mm', $f8_data_ins_mm);
        $template->assign('f8_data_ins_gg', $f8_data_ins_gg);
        $template->assign('f8_data_ins_aa', $f8_data_ins_aa);
        $template->assign('f8_data_ins_ora', $f8_data_ins_ora);
        $template->assign('f8_data_ins_min', $f8_data_ins_min);
        $template->assign('f8_data_scad_gg', $f8_data_scad_gg);
        $template->assign('f8_data_scad_mm', $f8_data_scad_mm);
        $template->assign('f8_data_scad_aa', $f8_data_scad_aa);
        $template->assign('f8_data_scad_ora', $f8_data_scad_ora);
        $template->assign('f8_data_scad_min', $f8_data_scad_min);
        $template->assign('f8_testo', $f8_testo);
        $template->assign('f8_urgente', $f8_urgente);
        $template->assign('f8_scadenza', $f8_scadenza);
        $template->assign('f8_canale', $f8_canale);

        $this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));

        return 'default';

    }

}
