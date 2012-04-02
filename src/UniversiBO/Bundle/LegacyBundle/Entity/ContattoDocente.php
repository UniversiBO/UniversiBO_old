<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

define('CHIUSO'		,0);
define('APERTO'		,1);
define('KILLED'		,2);
define('CRITIC'		,3);
define('INATTIVO'   ,4);

/**
 * ContattoDocente rappresenta l'insieme di informazioni collegate al 
 * contatto di un docente
 *
 * @package universibo
 * @subpackage class
 * @version 2.2.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
 class ContattoDocente {
 	
 	/**
	 * @access private
	 */
 	var $cod_doc =	null;
 	
 	/**
	 * @access private
	 */
 	var $stato;
 	
 	/**
	 * @access private
	 */
 	var $id_utente_assegnato;
 	
 	/**
	 * @access private
	 */
 	var $ultima_modifica;
 	
 	/**
	 * @access private
	 */
 	var $report;
 	
 	var $legend = array (
 			CHIUSO => 'chiuso - non ci sono compiti da eseguire',
 			APERTO => 'aperto - ci sono compiti da eseguire',
 			KILLED => 'killed - non ne vuole sapere di universibo',
 			CRITIC => 'critic - � un pezzo grosso, non � da contattare',
 			INATTIVO => 'inattivo - non ha corsi attivi nell\'A.A. corrente',
 			);
 	
 	public function __construct($coddoc, $state, $id, $mod, $report) 
 	{
		$this->cod_doc 	= $coddoc;
		$this->stato	= $state;
		$this->id_utente_assegnato	= $id;
		$this->ultima_modifica		= $mod;
		$this->report	= $report;		
	}
	
	/**
	 * resituisce il contattoDocente corrispondente al Docente
	 * 
	 *	@static
	 *  @param int coddoc è il codice del docente di cui si vuole avere informazioni
	 *  @return mixed resituisce ContattoDocente se esiste il contatto, false altrimenti
	 */
	function getContattoDocente ($coddoc)
	{
		
		$db = FrontController::getDbConnection('main');

		$query = 'SELECT stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti WHERE cod_doc = '.$db->quote($coddoc);
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		if( $rows == 0) {$return = false; return $return;}

		$row = $res->fetchRow();
		$contattoDocente = new ContattoDocente($coddoc, $row[0], $row[1], $row[2], $row[3]);
		
		return $contattoDocente;
	}
	
	/**
	 * resituisce il contattoDocente corrispondente al Docente
	 * 
	 *	@static
	 *   @return mixed resituisce array di ContattoDocente se esistono, false altrimenti
	 */
	function getAllContattoDocente()
	{
		$db = FrontController::getDbConnection('main');

		$query = 'SELECT cod_doc, stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti';
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		if( $rows == 0) return false;

		$elenco = array();
		while ($row = $res->fetchRow())
			$elenco[] = new ContattoDocente($row[0], $row[1], $row[2], $row[3], $row[4]);
		
		return $elenco;
	}
	
	function getStato()
	{
		return $this->stato;
	}
	
	function getStatoDesc()
	{
		return $this->legend[$this->stato];
	}
	function getReport()
	{
		return $this->report;
	}
	
	function setReport($rep)
	{
		$this->report = $rep;
	}
	
	function appendReport($rep)
	{
		$data =  getdate();
		$this->report = "----------".$data['mday'].'-'.$data['mon'].'-'.$data['year']
			.' '.$data['hours'].':'.$data['minutes'].'-----------'."\n".
			$rep."\n".$this->report."\n\n";
	}
	
	function getUltimaModifica()
	{
		return $this->ultima_modifica;
	}

	function getLegend()
	{
		return $this->legend;
	}
	
	/**
	 * @param int s	stato del contatto
	 * @param int id id utente di chi effettua le modifiche
	 */
	function setStato($s, $id = null)
	{
		$this->stato = $s;
		
		if ($id != null)
		{	
			$text = User::getUsernameFromId($id).': modifica dello stato assegnato in '."\n".$this->stato.': '.$this->legend[$s];
			$this->appendReport($text);
		}
	}

	function getCodDoc()
	{
		return $this->cod_doc;
	}

	function getIdUtenteAssegnato()
	{
		return $this->id_utente_assegnato;
	}
	
	/**
	 * @param int idUtenteMaster id di chi esegue la modifica della assegnamento
	 * @param int newIdUtente nuovo collaboratore assegnato
	 * 
	 */
	function assegna($newIdUtente, $idUtenteMaster)
	{
		$text = User::getUsernameFromId($idUtenteMaster).': assegnato docente a '.User::getUsernameFromId($newIdUtente);	
		$this->appendReport($text);
		$this->id_utente_assegnato = $newIdUtente;
		
	}
	
	function updateContattoDocente()
	{
		$db = FrontController::getDbConnection('main');
		
        ignore_user_abort(1);
        $db->autoCommit(false);
		$query = 'UPDATE docente_contatti SET stato = '.$db->quote($this->getStato())
					.' , id_utente_assegnato = '.$db->quote($this->getIdUtenteAssegnato())
					.' , ultima_modifica = '.$db->quote($this->getUltimaModifica()) 
					.' , report = '.$db->quote($this->getReport())
					.' WHERE cod_doc = '.$db->quote($this->getCodDoc());
		//echo $query;								 
		$res = $db->query($query);	
		//var_dump($query);
		if (DB::isError($res)){
			$db->rollback();
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
		
		$this->_checkState();
		
		$db->commit();
		$db->autoCommit(true);
		ignore_user_abort(0);
		
		$this->ultima_modifica = time();
		
		return true;
	}
	
	function insertContattoDocente()
	{
		$cod = $this->getCodDoc();
//		echo $cod;		die;
		$db = FrontController::getDbConnection('main');
		
        ignore_user_abort(1);
        $db->autoCommit(false);
        
        $query = 'SELECT * FROM docente_contatti WHERE cod_doc = '.$db->quote($cod);
//        echo $query;		die;	
        $res = $db->query($query);
		//var_dump($query);
		if (DB::isError($res)){
			$db->rollback();
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
        
        $rows = $res->numRows();
		if( $rows > 0) return false;
		$res->free();
		
        $query = 'INSERT INTO docente_contatti (cod_doc,stato,id_utente_assegnato,ultima_modifica,report) VALUES ' .
				'( ' .$db->quote($this->getCodDoc())
				.' , ' .$db->quote($this->getStato())
				.' , '.$db->quote($this->getIdUtenteAssegnato())
				.' , '.$db->quote($this->getUltimaModifica()) 
				.' , '.$db->quote($this->getReport())
				.' )';
//		echo $query;		die;						 
		$res = $db->query($query);
		//var_dump($query);
		if (DB::isError($res)){
			$db->rollback();
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
		
		$this->_checkState();
						
		$db->commit();
		$db->autoCommit(true);
		ignore_user_abort(0);
		
		return true;
	}
	
	/**
	 * @access private
	 */
	function _checkState()
	{	
		$db = FrontController::getDbConnection('main');
		
		if ($this->stato != APERTO && $this->stato != null)
		{
			$time	= time();
			$query	= 'UPDATE docente SET '
						.' docente_contattato = '.$db->quote($time)
						.' , id_mod = '.$db->quote($this->getIdUtenteAssegnato()) 
						.' WHERE cod_doc = '.$db->quote($this->getCodDoc());
			//echo $query;								 
			$res = $db->query($query);	
			//var_dump($query);
			if (DB::isError($res)){
				$db->rollback();
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			}
		}
	}
 }
