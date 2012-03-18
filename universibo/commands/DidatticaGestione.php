<?php    

require_once ('Facolta'.PHP_EXTENSION);
require_once ('Cdl'.PHP_EXTENSION);
require_once ('Insegnamento'.PHP_EXTENSION);
require_once ('Docente'.PHP_EXTENSION);
require_once ('User'.PHP_EXTENSION);

require_once ('PrgAttivitaDidattica'.PHP_EXTENSION);
require_once ('UniversiboCommand'.PHP_EXTENSION);

/**
 * -DidatticaGestione: per le correzioni didattiche
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author evaimitico
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class DidatticaGestione extends UniversiboCommand{

	function execute() {
		
		$frontcontroller = & $this->getFrontController();
		$template = & $frontcontroller->getTemplateEngine();

		$krono = & $frontcontroller->getKrono();
		$user = & $this->getSessionUser();
		$user_ruoli = & $user->getRuoli();

		if (!$user->isAdmin())		// TODO far sì che specifici utenti siano autorizzati (da file di conf)
		{
			Error :: throwError (_ERROR_DEFAULT, array ('msg' => "Non hai i diritti necessari per accedere a questa pagina\n la sessione potrebbe essere terminata", 'file' => __FILE__, 'line' => __LINE__));
		}		

		$template->assign('common_canaleURI', array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '' );
		$template->assign('common_langCanaleNome', 'indietro');
		$template->assign('DidatticaGestione_baseUrl', 'index.php?do=DidatticaGestione');

		$id_canale = '';	
		$id_facolta = '';
		$id_cdl = '';
		$id_sdop = '';
		
		$f41_cur_sel = ''; 
		$edit = 'false';
		$docenteEdit = false;
		
		$esamiAlternativi = '';

		// controllo se è stato scelta un'attività sdoppiata
		if (array_key_exists('id_sdop', $_GET) && preg_match('/^([0-9]{1,9})$/', $_GET['id_sdop']))
		{
			$prg_sdop = PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata((int) $_GET['id_sdop']);
			if ($prg_sdop !== false)
			{
				$id_sdop = $_GET['id_sdop'];
				$edit = 'true';
				$cdl = & Cdl::selectCdlCodice($prg_sdop->getCodiceCdl());
				$fac = & Facolta::selectFacoltaCodice($cdl->getCodiceFacoltaPadre());
				$f41_cur_sel['insegnamento'] = $prg_sdop->getNome();
				$f41_cur_sel['docente'] = $prg_sdop->getNomeDoc();
				$f41_cur_sel['codice docente'] = $prg_sdop->getCodDoc();
				$f41_cur_sel['ciclo'] = $prg_sdop->getTipoCiclo();
				$f41_cur_sel['anno'] = $prg_sdop->getAnnoCorsoUniversibo();
				$f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - ' .$prg_sdop->getCodiceCdl();
				$f41_cur_sel['facoltà'] = $fac->getTitolo();
				$f41_cur_sel['status'] = 'sdoppiato';
					
				$f41_edit_sel['ciclo'] = $prg_sdop->getTipoCiclo();
				$f41_edit_sel['anno'] = $prg_sdop->getAnnoCorsoUniversibo();
				$f41_edit_sel['codice docente'] = $prg_sdop->getCodDoc();
				
				unset($cdl);
				unset($fac);
			}
		}
		
		// controllo canale scelto
//		if (array_key_exists('id_canale', $_GET))
		if (array_key_exists('id_canale', $_GET) && preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
		{
//			if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
//				Error :: throwError (_ERROR_DEFAULT, array ('msg' => 'L\'id del canale richiesto non è valido', 'file' => __FILE__, 'line' => __LINE__));

			if ( Canale::getTipoCanaleFromId($_GET['id_canale']) == CANALE_INSEGNAMENTO)
			{
				$canale = & Canale::retrieveCanale(intval($_GET['id_canale']));
				$id_canale = $canale->getIdCanale();
				if ($edit == 'false')
				{
					$f41_cur_sel['insegnamento'] = $canale->getTitolo();
					$listaPrgs =  $canale->getElencoAttivitaPadre();
					$prg =  $listaPrgs[0];
					$cdl = & Cdl::selectCdlCodice($prg->getCodiceCdl());
					$fac = & Facolta::selectFacoltaCodice($cdl->getCodiceFacoltaPadre());
					$f41_cur_sel['docente'] = $prg->getNomeDoc();
					$f41_cur_sel['codice docente'] = $prg->getCodDoc();
					$f41_cur_sel['ciclo'] = $prg->getTipoCiclo();
					$f41_cur_sel['anno'] = $prg->getAnnoCorsoUniversibo();
					$f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - ' . $prg->getCodiceCdl();
					$f41_cur_sel['facoltà'] = $fac->getTitolo();
					
					$f41_edit_sel['ciclo'] = $prg->getTipoCiclo();
					$f41_edit_sel['anno'] = $prg->getAnnoCorsoUniversibo();
					$f41_edit_sel['codice docente'] = $prg->getCodDoc();
					$edit = 'true';
					
					$esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale($id_canale,$prg);
				}
				else
					$esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale($id_canale,$prg_sdop);
//				$esamiAlternativi = DidatticaGestione::_getAttivitaFromCanale($id_canale);
				if (count($esamiAlternativi) == 0) $esamiAlternativi = '';
				// la modifica del docente è permessa solo quando è insegnamento padre e  non è attivo il forum dell'insegnamento
				if(!array_key_exists('id_sdop', $_GET) && 
						($canale->getForumForumId() == null ||$canale->getForumForumId() == 0)
					)
					$docenteEdit = true;	
				else
					unset($f41_edit_sel['codice docente']);
				
			}
		}
		

		// controllo facoltà scelta
//		if (array_key_exists('id_fac', $_GET))
		if (array_key_exists('id_fac', $_GET) && preg_match('/^([0-9]{1,9})$/', $_GET['id_fac']))
		{
//			if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_fac']))
//				Error :: throwError (_ERROR_DEFAULT, array ('msg' => 'L\'id della facoltà richiesta non è valido', 'file' => __FILE__, 'line' => __LINE__));

				
			if ( Canale::getTipoCanaleFromId($_GET['id_fac']) == CANALE_FACOLTA)
			{
				$fac = & Canale::retrieveCanale(intval($_GET['id_fac']));
				$id_facolta = $fac->getIdCanale();
				$f41_cur_sel['facoltà'] = $fac->getTitolo();
			}
		}
						
		// controllo cdl						
//		if (array_key_exists('id_cdl', $_GET))
		if (array_key_exists('id_cdl', $_GET) && preg_match('/^([0-9]{1,9})$/', $_GET['id_cdl']))
		{
//			if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_cdl']))
//				Error :: throwError (_ERROR_DEFAULT, array ('msg' => 'L\'id del canale richiesto non è valido', 'file' => __FILE__, 'line' => __LINE__));

			
			if ( Canale::getTipoCanaleFromId($_GET['id_cdl']) == CANALE_CDL)
			{
				$cdl = & Canale::retrieveCanale(intval($_GET['id_cdl']));
				// controllo coerenza tra facoltà, cdl e insegnamento
				if($id_facolta != '')
					if($cdl->getCodiceFacoltaPadre() == $fac->getCodiceFacolta())
						if($id_canale == '' || in_array($cdl->getCodiceCdl(),$canale->getElencoCodiciCdl()))
						{
							$id_cdl = $cdl->getIdCanale();
							$f41_cur_sel['cdl'] = $cdl->getTitolo() . ' - ' .$cdl->getCodiceCdl();
						}
					else
					{
						$id_facolta = '';
						unset($f41_cur_sel['facoltà']);
					}
			}
		}	
			
		$f41_accept = false;
		$listaDocenti = '';
		
		//submit della ricerca docente
		if (array_key_exists('f41_search', $_POST)  )
		{
			
			if (!array_key_exists('f41_username', $_POST) || !array_key_exists('f41_email', $_POST) )
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'La ricerca docente effettuata non è valida', 'file' => __FILE__, 'line' => __LINE__));
			
			$f41_accept = true;
				 
			if ($_POST['f41_username'] == '' && $_POST['f41_email'] == '')
			{
				Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Specificare almeno uno dei due criteri di ricerca docente', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
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
			
			if ($f41_accept)
			{
				$users_search = User::selectUsersSearch($f41_username, $f41_email);
				$listaDocenti = array();
				
				foreach($users_search as $v)
					if ($v->isDocente())
					{
						$doc = Docente::selectDocente($v->getIdUser());
						if($doc != false)
							$listaDocenti[]=array('nome' => $doc->getNomeDoc(), 'codice' => $doc->getCodDoc());
					}
				if(count($listaDocenti) == 0) $listaDocenti = '';
			}
			
		}
		
		$f41_accept = false;
		// submit della modifica delle attività
		if (array_key_exists('f41_submit', $_POST) && $id_canale != '' ) 
		{
			$f41_accept = true;
//			var_dump($_POST); die;
			if (!array_key_exists('f41_edit_sel', $_POST) || !is_array($_POST['f41_edit_sel']) ||count($_POST['f41_edit_sel']) == 0)
			{
				Error :: throwError (_ERROR_NOTICE, array ('msg' => 'Nessun parametro specificato, nessuna modifica effettuata', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
				$f41_accept = false;
			}
			else 
			{
				$prgs = array();
				$tmpEdit = $_POST['f41_edit_sel'];
				
				if($id_sdop != '') $prgs[] = & PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata((int) $id_sdop);
				else 
					$prgs[] = & $prg;
//				var_dump($prgs); die;	
				if(array_key_exists('f41_alts',$_POST))
					foreach ($_POST['f41_alts'] as $key => $value)
					{
						if(strstr($key,'#') != false)
						{
							list($id_channel,$id_sdoppiamento) = explode('#', $key);
//							var_dump($key); var_dump($id_sdoppiamento); die;
							$prgs[] = & PrgAttivitaDidattica::selectPrgAttivitaDidatticaSdoppiata((int) $id_sdoppiamento);
						}
						else
						{
							$channel = Canale::retrieveCanale($key);
							$atts = $channel->getElencoAttivitaPadre();
							$prgs[] = & $atts[0];
 						}
						
					}
				$tot = count($prgs);
				$mods = array();
				if (array_key_exists('codice docente', $tmpEdit))
				{
					if (!preg_match('/^([0-9]{1,9})$/', $tmpEdit['codice docente']) || Docente::selectDocenteFromCod(intval($tmpEdit['codice docente'])))
					{
						Error :: throwError (_ERROR_NOTICE, array ('msg' => 'Codice docente invalido, nessuna modifica effettuata', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
						$f41_accept = false;
					}
					else
						for($i = 0; $i < $tot; $i++)
							//$prgs[$i]->setCodDoc($tmpEdit['codice docente']);
							$this->_updateVal($prgs[$i],$i, $mods, $tmpEdit['codice docente'], 'doc', $template);
					
				}			
				if (array_key_exists('ciclo', $tmpEdit))
				{
					if (!ereg('^([0-4,E]{1})$', $tmpEdit['ciclo']))
					{
						Error :: throwError (_ERROR_NOTICE, array ('msg' => 'Ciclo invalido, nessuna modifica effettuata', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
						$f41_accept = false;
					}
					else
						for($i = 0; $i < $tot; $i++)
							//$prgs[$i]->setTipoCiclo($tmpEdit['ciclo']);
							$this->_updateVal($prgs[$i],$i, $mods, $tmpEdit['ciclo'], 'ciclo', $template);
								
				}
				if (array_key_exists('anno', $tmpEdit))
				{
					// l'anno può essere 0 per gli esami opzionali di economia
					if (!ereg('^([0-5]{1})$', $tmpEdit['anno']) || Docente::selectDocenteFromCod($tmpEdit['anno']))
					{
						Error :: throwError (_ERROR_NOTICE, array ('msg' => 'Anno invalido, nessuna modifica effettuata', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
						$f41_accept = false;
					}
					else
						for($i = 0; $i < $tot; $i++)
							//$prgs[$i]->setAnnoCorsoUniversibo($tmpEdit['anno']);
							$this->_updateVal($prgs[$i],$i, $mods, $tmpEdit['anno'], 'anno', $template);
				}
				
			}
					
			//esecuzione operazioni accettazione del form
			if ($f41_accept == true) 
			{
//				var_dump($mods);
				$failure = false;
				$db = FrontController::getDbConnection('main');
				ignore_user_abort(1);
        		$db->autoCommit(false);
				
				
				// TODO manca log delle modifiche
				$keys = array_keys($mods);
				foreach($keys as $i)
				{
					$esito = $prgs[$i]->updatePrgAttivitaDidattica();
//					var_dump($prgs); die;
					if ($esito == false) 
					{	
//						echo 'qui'; die;
						$failure = true;
						$db->rollback();
						break;
					}
					else
						$this->_log($user->getIdUser(),$id_canale, $id_cdl, $id_facolta, $id_sdop,$mods[$i]);
					//aggiorno il referente della materia in caso di modifica docente
					if(array_key_exists('doc', $mods[$i]))
					{
						$doc = Docente::selectDocenteFromCod($mods[$i]['doc']['old']);
						$ruoli = $doc->getRuoli();
						if (array_key_exists($prgs[$i]->getIdCanale(),$ruoli))
						{
							//eliminiamo il vecchio referente
							$r = $ruoli[$prgs[$i]->getIdCanale()];
							$r->updateSetModeratore(false);
							$r->updateSetReferente(false);
							$r->setMyUniversiBO(false);
							$esito = $r->updateRuolo();
							if ($esito == false) 
							{	
		//						echo 'qui'; die;
								$failure = true;
								$db->rollback();
								break;
							}
							
							unset($doc);
							unset($r);
							unset($ruoli);
							
							// aggiungiamo il nuovo referente
							$doc = Docente::selectDocenteFromCod($mods[$i]['doc']['new']);
							$ruoli = $doc->getRuoli();
							if (array_key_exists($prgs[$i]->getIdCanale(),$ruoli))
							{
								$r = $ruoli[$prgs[$i]->getIdCanale()];
								$r->updateSetModeratore(false);
								$r->updateSetReferente(true);
								$r->setMyUniversiBO(true);
								$esito = $r->updateRuolo();
								if ($esito == false) 
								{	
			//						echo 'qui'; die;
									$failure = true;
									$db->rollback();
									break;
								}
								
							}
							else
							{
								$ruolo = new Ruolo($doc->getIdUser(), $prgs[$i]->getIdCanale(), '' , time(), false, true, true, NOTIFICA_ALL, false);
								$ruolo->insertRuolo();				
							}
						}
							
						
					}
				}
				$db->commit();
				
        		$db->autoCommit(true);
				ignore_user_abort(0);
				
				if ($failure)
				{
					Error :: throwError (_ERROR_NOTICE, array ('msg' => 'Errore DB, nessuna modifica effettuata', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
					return 'default';
				}
				
				return 'success';
			}

		} 
		//end if (array_key_exists('f41_submit', $_POST))

		
/*		$template->assign('f41_canale', $f41_canale);
		$template->assign('f41_cdl', $f41_cdl);
		$template->assign('f41_fac', $f41_fac);
*/		$template->assign('f41_cur_sel', $f41_cur_sel);
		$template->assign('f41_edit_sel', $f41_edit_sel);
		$template->assign('f41_alts', $esamiAlternativi);
		$template->assign('DidatticaGestione_edit', $edit);
		$template->assign('DidatticaGestione_docenteEdit', $docenteEdit);
		$template->assign('DidatticaGestione_docs', $listaDocenti);
		
		$this->executePlugin('ShowTopic', array('reference' => 'didatticagestione'));
		 
		return 'default';

	}
	
	
	/**
	 * Ordina la struttura dei canali
	 * 
	 * @static
	 * @private
	 */
	function _compareCanale($a, $b)
	{
		$nomea = strtolower($a['nome']);
		$nomeb = strtolower($b['nome']);
		return strnatcasecmp($nomea, $nomeb);
	}
	
	
	/**
	 * Recupera le attività associate ad un insegnamento, escludendo un eventuale attività
	 */
	 function  & _getAttivitaFromCanale($id_canale, $prg_exclude = null)
	 {
	 	$prgs = PrgAttivitaDidattica::selectPrgAttivitaDidatticaCanale($id_canale);
	 	$ret = array();
	 	foreach ($prgs as $prg)
	 		if ($prg_exclude == null || $prg != $prg_exclude)
	 		{
//	 			var_dump($prg);
	 			$cdl = & Cdl::selectCdlCodice($prg->getCodiceCdl());
	 			$id = $id_canale;
	 			$uri = 'index.php?do=DidatticaGestione&id_canale='.$id_canale.'&id_cdl='.$cdl->getIdCanale().'&id_fac='.$_GET['id_fac'];
	 			$status = '';
	 			if($prg->isSdoppiato())
	 			{
	 				$id .= '#' . $prg->getIdSdop();
	 				$uri .= '&id_sdop='.$prg->getIdSdop();
	 				$status = 'sdoppiato';
	 			}
	 			$ret[] = array(
	 						'id'		=> $id,
	 						'spunta'	=> 'false',
	 						'nome'		=> $prg->getNome(),
	 						'doc'		=> $prg->getNomeDoc(),
	 						'cdl'		=> $cdl->getNome() .' - '. $prg->getCodiceCdl(),
	 						'ciclo'		=> $prg->getTipoCiclo(),
	 						'anno'		=> $prg->getAnnoCorsoUniversibo(),
	 						'status'	=> $status,
	 						'uri'		=> $uri
	 					);
	 		}
	 	return $ret;	
	 }
	 
	 function _log($id_utente,$id_canale, $id_cdl, $id_facolta, $id_sdop, $modified)
	 {
	 	$log_definition = array(0 => 'timestamp', 1 => 'date', 2 => 'time', 3 => 'id_utente', 4 => 'ip_utente', 5 =>  'messaggio' );
	 	$desc = '';
	 	foreach(array('doc','ciclo','anno') as $k)
			$desc .= (array_key_exists($k,$modified))? $k.' '.$modified[$k]['old'].' -> '.$modified[$k]['new'].'; ' :'';
		$log = new LogHandler('modificaDidattica','../universibo/log-universibo/',$log_definition); 
		
		$log_array = array( 'timestamp'  => time(),
							'date'  => date("Y-m-d",time()),
							'time'  => date("H:i",time()),
							'id_utente' => $id_utente,
							'ip_utente' => (isset($_SERVER) && array_key_exists('REMOTE_ADDR',$_SERVER))? $_SERVER['REMOTE_ADDR']: '0.0.0.0',
							'messaggio'  => $desc);
		$log->addLogEntry($log_array);
	 }

	 
	/**
	 * @static
	 * @return string
	 */
	function getEditUrl($id_canale, $id_cdl = null, $id_facolta = null, $id_sdop = null)
	{
		$ret = 'index.php?do=DidatticaGestione&id_canale='.$id_canale;
		if ($id_cdl != null) $ret .= '&id_cdl='.$id_cdl;
		if ($id_facolta != null) $ret .= '&id_fac='.$id_facolta;
		if ($id_sdop != null) $ret .= '&id_sdop='.$id_sdop;
		return $ret;
	}
	
	/**
	 * modifica prg e tiene traccia delle modifiche in $mods
	 * @param type string  può essere doc, ciclo, anno
	 */
	function _updateVal(& $prg,$index, & $mods, $val, $type, & $template)
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
				Error :: throwError (_ERROR_CRITICAL, array ('msg' => 'Errore dei programmatori', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' =>  $template));
				break;
			
			}
				
		$old = $prg->$get();
//		var_dump($old); die;
		if ($old != $val)
		{
			$prg->$set($val);
			$m = (array_key_exists($index,$mods)) ? $mods[$index] : array();
			$m[$type] = array('old' => $old, 'new' => $val);
			$mods[$index] = $m;
		}
	}
	
}

?>