<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Command\DidatticaGestione;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\Facolta;
use Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;
use Universibo\Bundle\LegacyBundle\Entity\Ruolo;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * -DidatticaGestione: per le correzioni didattiche
 *
 * @version 2.0.0
 * @author evaimitico
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class DidatticaGestione extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $request = $this->getRequest();
        $professorRepo = $this->get('universibo_legacy.repository.docente');

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => "Non hai i diritti necessari per accedere a questa pagina\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $template->assign('common_canaleURI',$request->server->get('HTTP_REFERER'));
        $template->assign('common_langCanaleNome', 'indietro');
        $template->assign('DidatticaGestione_baseUrl', $router->generate('universibo_legacy_didattica_gestione'));

        $id_facolta = '';
        $id_cdl = '';

        $f41_cur_sel = '';
        $edit = 'false';
        $docenteEdit = false;

        $esamiAlternativi = '';

        $idSdop = $request->get('id_sdop', '');

        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        // controllo se è stato scelta un'attività sdoppiata
        if (preg_match('/^([0-9]{1,9})$/', $idSdop)) {
            $prg_sdop = PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata(
                    (int) $idSdop);
            if ($prg_sdop !== false) {
                $edit = 'true';
                $cdl = Cdl::selectCdlCodice($prg_sdop->getCodiceCdl());
                $fac = Facolta::selectFacoltaCodice(
                        $cdl->getCodiceFacoltaPadre());
                $f41_cur_sel['insegnamento'] = $prg_sdop->getNome();
                $f41_cur_sel['docente'] = $prg_sdop->getNomeDoc();
                $f41_cur_sel['codice docente'] = $prg_sdop->getCodDoc();
                $f41_cur_sel['ciclo'] = $prg_sdop->getTipoCiclo();
                $f41_cur_sel['anno'] = $prg_sdop->getAnnoCorsoUniversibo();
                $f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - '
                        . $prg_sdop->getCodiceCdl();
                $f41_cur_sel['facolta'] = $fac->getTitolo();
                $f41_cur_sel['status'] = 'sdoppiato';

                $f41_edit_sel['ciclo'] = $prg_sdop->getTipoCiclo();
                $f41_edit_sel['anno'] = $prg_sdop->getAnnoCorsoUniversibo();
                $f41_edit_sel['codice docente'] = $prg_sdop->getCodDoc();

                unset($cdl);
                unset($fac);
            }
        }

        // controllo canale scelto
        $channelId = $request->get('id_canale', '');
        if (preg_match('/^([0-9]{1,9})$/', $channelId)) {
            //			if (!preg_match('/^([0-9]{1,9})$/', $channelId))
            //				Error :: throwError (_ERROR_DEFAULT, array ('msg' => 'L\'id del canale richiesto non e` valido', 'file' => __FILE__, 'line' => __LINE__));

            if ($channelRepo->getTipoCanaleFromId($channelId)
                    == Canale::INSEGNAMENTO) {
                $canale = $channelRepo->find(intval($channelId));
                $channelId = $canale->getIdCanale();
                if ($edit == 'false') {
                    $f41_cur_sel['insegnamento'] = $canale->getTitolo();
                    $listaPrgs = $canale->getElencoAttivitaPadre();
                    $prg = $listaPrgs[0];
                    $cdl = Cdl::selectCdlCodice($prg->getCodiceCdl());
                    $fac = Facolta::selectFacoltaCodice(
                            $cdl->getCodiceFacoltaPadre());
                    $f41_cur_sel['docente'] = $prg->getNomeDoc();
                    $f41_cur_sel['codice docente'] = $prg->getCodDoc();
                    $f41_cur_sel['ciclo'] = $prg->getTipoCiclo();
                    $f41_cur_sel['anno'] = $prg->getAnnoCorsoUniversibo();
                    $f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - '
                            . $prg->getCodiceCdl();
                    $f41_cur_sel['facolta'] = $fac->getTitolo();

                    $f41_edit_sel['ciclo'] = $prg->getTipoCiclo();
                    $f41_edit_sel['anno'] = $prg->getAnnoCorsoUniversibo();
                    $f41_edit_sel['codice docente'] = $prg->getCodDoc();
                    $edit = 'true';

                    $esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale(
                            $channelId, $prg);
                } else
                    $esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale(
                            $channelId, $prg_sdop);
                //				$esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale($channelId);
                if (count($esamiAlternativi) == 0)
                    $esamiAlternativi = '';
                // la modifica del docente è permessa solo quando è insegnamento padre e  non e` attivo il forum dell'insegnamento

                if ($idSdop != '' && ($canale->getForumForumId() == null
                                || $canale->getForumForumId() == 0))
                    $docenteEdit = true;
                else
                    unset($f41_edit_sel['codice docente']);

            }
        }

        $facultyId = $request->get('id_fac');
        // controllo facolta` scelta
        if (preg_match('/^([0-9]{1,9})$/',$facultyId)) {
            $facultyId = intval($facultyId);
            if ($channelRepo->getTipoCanaleFromId($facultyId) == Canale::FACOLTA) {
                $fac = $channelRepo->find($facultyId);
                $id_facolta = $fac->getIdCanale();
                $f41_cur_sel['facolta'] = $fac->getTitolo();
            }
        }

        $cdlId = $request->get('id_cdl');
        // controllo cdl
        if (preg_match('/^([0-9]{1,9})$/', $cdlId)) {
            if ($channelRepo->getTipoCanaleFromId($cdlId) == Canale::CDL) {
                $cdl = $channelRepo->find(intval($cdlId));
                // controllo coerenza tra facolta`, cdl e insegnamento
                if ($id_facolta != '')
                    if ($cdl->getCodiceFacoltaPadre()
                            == $fac->getCodiceFacolta())
                        if ($channelId == ''
                                || in_array($cdl->getCodiceCdl(),
                                        $canale->getElencoCodiciCdl())) {
                            $id_cdl = $cdl->getIdCanale();
                            $f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - '
                                    . $cdl->getCodiceCdl();
                        } else {
                            $id_facolta = '';
                            unset($f41_cur_sel['facolta']);
                        }
            }
        }

        $f41_accept = false;
        $listaDocenti = '';

        //submit della ricerca docente
        if (array_key_exists('f41_search', $_POST)) {

            if (!array_key_exists('f41_username', $_POST)
                    || !array_key_exists('f41_email', $_POST))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'La ricerca docente effettuata non e` valida',
                                'file' => __FILE__, 'line' => __LINE__));

            $f41_accept = true;

            if ($_POST['f41_username'] == '' && $_POST['f41_email'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Specificare almeno uno dei due criteri di ricerca docente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f41_accept = false;
            }

            if ($_POST['f41_username'] == '')
                $f41_username = '%';
            else
                $f41_username = $_POST['f41_username'];

            if ($_POST['f41_email'] == '')
                $f41_email = '%';
            else
                $f41_email = $_POST['f41_email'];

            if ($f41_accept) {
                $users_search = User::selectUsersSearch($f41_username,
                        $f41_email);
                $listaDocenti = array();

                foreach ($users_search as $v)
                    if ($v->hasRole('ROLE_PROFESSOR')) {
                        $doc = $professorRepo->findByUserId($v->getId());
                        if ($doc != false)
                            $listaDocenti[] = array(
                                    'nome' => $doc->getNomeDoc(),
                                    'codice' => $doc->getCodDoc());
                    }
                if (count($listaDocenti) == 0)
                    $listaDocenti = '';
            }

        }

        $f41_accept = false;
        // submit della modifica delle attivita`
        if (array_key_exists('f41_submit', $_POST) && $channelId != '') {
            $f41_accept = true;
            //			var_dump($_POST); die;
            if (!array_key_exists('f41_edit_sel', $_POST)
                    || !is_array($_POST['f41_edit_sel'])
                    || count($_POST['f41_edit_sel']) == 0) {
                Error::throwError(_ERROR_NOTICE,
                        array(
                                'msg' => 'Nessun parametro specificato, nessuna modifica effettuata',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f41_accept = false;
            } else {
                $prgs = array();
                $tmpEdit = $_POST['f41_edit_sel'];

                if ($idSdop != '')
                    $prgs[] = PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata(
                            (int) $idSdop);
                else
                    $prgs[] = $prg;
                //				var_dump($prgs); die;
                if (array_key_exists('f41_alts', $_POST))
                    foreach ($_POST['f41_alts'] as $key => $value) {
                        if (strstr($key, '#') != false) {
                            list($id_channel, $idSdoppiamento) = explode('#',
                                    $key);
                            //							var_dump($key); var_dump($idSdoppiamento); die;
                            $prgs[] = PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata(
                                    (int) $idSdoppiamento);
                        } else {
                            $channel = $channelRepo->find($key);
                            $atts = $channel->getElencoAttivitaPadre();
                            $prgs[] = $atts[0];
                        }

                    }
                $tot = count($prgs);
                $mods = array();
                if (array_key_exists('codice docente', $tmpEdit)) {
                    if (!preg_match('/^([0-9]{1,9})$/',
                            $tmpEdit['codice docente'])
                            || !$professorRepo->find(
                                    $tmpEdit['codice docente'])) {
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'Codice docente invalido, nessuna modifica effettuata',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f41_accept = false;
                    } else
                        for ($i = 0; $i < $tot; $i++)
                        //$prgs[$i]->setCodDoc($tmpEdit['codice docente']);
                            $this
                                    ->_updateVal($prgs[$i], $i, $mods,
                                            $tmpEdit['codice docente'], 'doc',
                                            $template);

                }
                if (array_key_exists('ciclo', $tmpEdit)) {
                    if (!preg_match('/^([0-4,E]{1})$/', $tmpEdit['ciclo'])) {
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'Ciclo invalido, nessuna modifica effettuata',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f41_accept = false;
                    } else
                        for ($i = 0; $i < $tot; $i++)
                        //$prgs[$i]->setTipoCiclo($tmpEdit['ciclo']);
                            $this
                                    ->_updateVal($prgs[$i], $i, $mods,
                                            $tmpEdit['ciclo'], 'ciclo',
                                            $template);

                }
                if (array_key_exists('anno', $tmpEdit)) {
                    // l'anno puo` essere 0 per gli esami opzionali di economia
                    if (!preg_match('/^([0-5]{1})$/', $tmpEdit['anno'])
                            || $professorRepo->find($tmpEdit['anno'])) {
                        Error::throwError(_ERROR_NOTICE,
                                array(
                                        'msg' => 'Anno invalido, nessuna modifica effettuata',
                                        'file' => __FILE__, 'line' => __LINE__,
                                        'log' => false,
                                        'template_engine' => &$template));
                        $f41_accept = false;
                    } else
                        for ($i = 0; $i < $tot; $i++)
                        //$prgs[$i]->setAnnoCorsoUniversibo($tmpEdit['anno']);
                            $this
                                    ->_updateVal($prgs[$i], $i, $mods,
                                            $tmpEdit['anno'], 'anno',
                                            $template);
                }

            }

            //esecuzione operazioni accettazione del form
            if ($f41_accept == true) {
                //				var_dump($mods);
                $failure = false;
                // TODO BEGIN TRANSACTION
                //$transaction = $this->getContainer()->get('uiversibo_legacy.transaction');
                ignore_user_abort(1);
                //$transaction->begin();

                // TODO manca log delle modifiche
                $keys = array_keys($mods);
                foreach ($keys as $i) {
                    $esito = $prgs[$i]->updatePrgAttivitaDidattica();
                    //					var_dump($prgs); die;
                    if ($esito == false) {
                        //						echo 'qui'; die;
                        $failure = true;
                        // TODO $transaction->rollback();
                        break;
                    } else
                        $this
                                ->_log($user->getId(), $channelId, $id_cdl,
                                        $id_facolta, $idSdop, $mods[$i]);
                    //aggiorno il referente della materia in caso di modifica docente
                    if (array_key_exists('doc', $mods[$i])) {
                        $doc = $professorRepo->find(
                                $mods[$i]['doc']['old']);
                        $ruoli = $doc->getRuoli();
                        if (array_key_exists($prgs[$i]->getIdCanale(), $ruoli)) {
                            //eliminiamo il vecchio referente
                            $r = $ruoli[$prgs[$i]->getIdCanale()];
                            $r->updateSetModeratore(false);
                            $r->updateSetReferente(false);
                            $r->setMyUniversiBO(false);
                            $esito = $r->updateRuolo();
                            if ($esito == false) {
                                //						echo 'qui'; die;
                                $failure = true;
                                $transaction->rollback();
                                break;
                            }

                            unset($doc);
                            unset($r);
                            unset($ruoli);

                            // aggiungiamo il nuovo referente
                            $doc = $professorRepo->find($mods[$i]['doc']['new']);
                            $ruoli = $doc->getRuoli();
                            if (array_key_exists($prgs[$i]->getIdCanale(),
                                    $ruoli)) {
                                $r = $ruoli[$prgs[$i]->getIdCanale()];
                                $r->updateSetModeratore(false);
                                $r->updateSetReferente(true);
                                $r->setMyUniversiBO(true);
                                $esito = $r->updateRuolo();
                                if ($esito == false) {
                                    //						echo 'qui'; die;
                                    $failure = true;
                                    $transaction->rollback();
                                    break;
                                }

                            } else {
                                $ruolo = new Ruolo($doc->getId(),
                                        $prgs[$i]->getIdCanale(), '', time(),
                                        false, true, true, NOTIFICA_ALL, false);
                                $ruolo->insertRuolo();
                            }
                        }

                    }
                }
                // TODO commit
                //$transaction->commit();
                ignore_user_abort(0);

                if ($failure) {
                    Error::throwError(_ERROR_NOTICE,
                            array(
                                    'msg' => 'Errore DB, nessuna modifica effettuata',
                                    'file' => __FILE__, 'line' => __LINE__,
                                    'log' => false,
                                    'template_engine' => &$template));

                    return 'default';
                }

                return 'success';
            }

        }
        //end if (array_key_exists('f41_submit', $_POST))

        /*		$template->assign('f41_canale', $f41_canale);
         $template->assign('f41_cdl', $f41_cdl);
        $template->assign('f41_fac', $f41_fac);
         */ $template->assign('f41_cur_sel', $f41_cur_sel);
        $template->assign('f41_edit_sel', $f41_edit_sel);
        $template->assign('f41_alts', $esamiAlternativi);
        $template->assign('DidatticaGestione_edit', $edit);
        $template->assign('DidatticaGestione_docenteEdit', $docenteEdit);
        $template->assign('DidatticaGestione_docs', $listaDocenti);

        $this
                ->executePlugin('ShowTopic',
                        array('reference' => 'didatticagestione'));

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
     * Recupera le attività associate ad un insegnamento, escludendo un eventuale attività
     */
    function &_getAttivitaFromCanale($channelId, $prg_exclude = null)
    {
        $router = $this->get('router');
        $prgs = PrgAttivitaDidattica::selectPrgAttivitaDidatticaCanale(
                $channelId);
        $ret = array();
        foreach ($prgs as $prg)
            if ($prg_exclude == null || $prg != $prg_exclude) {
                //	 			var_dump($prg);
                $cdl = Cdl::selectCdlCodice($prg->getCodiceCdl());
                $id = $channelId;
                $facultyId = $this->getRequest()->get('id_fac');
                $uri =  $router->generate('universibo_legacy_didattica_gestione', array('id_canale' => $channelId, 'id_cdl' => $cdl->getIdCanale(), 'id_fac' => $facultyId));
                $status = '';
                if ($prg->isSdoppiato()) {
                    $id .= '#' . $prg->getIdSdop();
                    $uri .= '&id_sdop=' . $prg->getIdSdop();
                    $status = 'sdoppiato';
                }
                $ret[] = array('id' => $id, 'spunta' => 'false',
                        'nome' => $prg->getNome(),
                        'doc' => $prg->getNomeDoc(),
                        'cdl' => $cdl->getNome() . ' - ' . $prg->getCodiceCdl(),
                        'ciclo' => $prg->getTipoCiclo(),
                        'anno' => $prg->getAnnoCorsoUniversibo(),
                        'status' => $status, 'uri' => $uri);
            }

        return $ret;
    }

    public function _log($id_utente, $channelId, $id_cdl, $id_facolta, $idSdop,
            $modified)
    {
        $desc = '';
        foreach (array('doc', 'ciclo', 'anno') as $k)
            $desc .= (array_key_exists($k, $modified)) ? $k . ' '
                            . $modified[$k]['old'] . ' -> '
                            . $modified[$k]['new'] . '; ' : '';

        $logger = $this->get('logger');

        $log_array = array('timestamp' => time(),
                'date' => date("Y-m-d", time()), 'time' => date("H:i", time()),
                'id_utente' => $id_utente,
                'ip_utente' => (isset($_SERVER)
                        && array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR']
                        : '0.0.0.0', 'messaggio' => $desc);
        $logger->info($log_array);
    }

    /**
     * @return string
     */
    public static function getEditUrl($channelId, $id_cdl = null, $id_facolta = null,
            $idSdop = null)
    {
        $router = FrontController::getContainer()->get('router');

        $data = array('id_canale' => $channelId);

        if ($id_cdl !== null) {
            $data['id_cdl'] = $id_cdl;
        }

        if ($id_facolta !== null) {
            $data['id_facolta'] = $id_facolta;
        }
        if ($idSdop !== null) {
            $data['id_sdop'] = $idSdop;
        }

        return $router->generate('universibo_legacy_didattica_gestione', $data);
    }

    /**
     * modifica prg e tiene traccia delle modifiche in $mods
     * @param type string può essere doc, ciclo, anno
     */
    public function _updateVal(&$prg, $index, &$mods, $val, $type, &$template)
    {
        switch ($type) {

        case 'doc':
            $get = 'getCodDoc';
            $set = 'setCodDoc';
            break;
        case 'ciclo':
            $get = 'getTipoCiclo';
            $set = 'setTipoCiclo';
            break;
        case 'anno':
            $get = 'getAnnoCorsoUniversibo';
            $set = 'setAnnoCorsoUniversibo';
            break;

        default:
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => 'Errore dei programmatori',
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => false, 'template_engine' => $template));
            break;

        }

        $old = $prg->$get();
        //		var_dump($old); die;
        if ($old != $val) {
            $prg->$set($val);
            $m = (array_key_exists($index, $mods)) ? $mods[$index] : array();
            $m[$type] = array('old' => $old, 'new' => $val);
            $mods[$index] = $m;
        }
    }
}
