<?php

require_once('User'.PHP_EXTENSION);

/**
 * Docente class, modella le informazioni relative ai docenti
 * A dir la verità non so perchè estende User @see Collaboratore
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Docente extends User {
	
	/**
	 * @access private
	 */
	var $id_utente;
	
	/**
	 * @access private
	 */
	var $codDoc;	
	
	/**
	 * @access private
	 */
	var $nomeDoc;
	
	/**
	 * @access private
	 */
	var $userCache = null;
	
	/**
	 * @access private
	 */
	var $rubricaCache = null;
	
	public function __construct($id_utente, $cod_doc, $nome_doc, $rubrica = null )
	{
		$this->id_utente	= $id_utente;
		$this->codDoc		= $cod_doc;
		$this->nomeDoc		= $nome_doc;
		$this->rubricaCache = $rubrica;
	}
	
	function getIdUtente()
	{
		return $this->id_utente;
	}

	function setIdUtente($id_utente)
	{
		$this->id_utente = $id_utente;
	}

	function getCodDoc()
	{
		return $this->codDoc;
	}

	function getNomeDoc()
	{
		return $this->nomeDoc;
	}
 
	function getHomepageDocente()
	{
		return 'http://www.unibo.it/Portale/Strumenti+del+Portale/Rubrica/paginaWebDocente.htm?mat='.$this->getCodDoc();
	}
 	
	
	/**
	 * Ritorna Preleva tutti i collaboratori dal database
	 *
	 * @static
	 * @param int $id_utente numero identificativo utente
	 * @return array Collaboratori
	 */
	function getUser()
	{
		if ($this->userCache == NULL)
		{
			$this->userCache = User::selectUser($this->getIdUtente()); 
		}
		return $this->userCache;
	}
 	
 	/**
	 * Ritorna le info del docente prese dalla rubrica
	 * 
	 * @return array 
	 */
	function getInfoRubrica()
	{
		if ($this->rubricaCache == NULL)
		{
			$this->rubricaCache = $this->_getDocenteInfo(); 
		}
		return $this->rubricaCache;
	}
	
	/**
	 * @access private
	 */
	function _getDocenteInfo()
	{
		$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT nome, cognome, prefissonome, sesso, email, descrizionestruttura FROM rub_docente WHERE cod_doc = '.$db->quote($this->getCodDoc());
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		if( $rows == 0) return false;

		$row = $res->fetchRow();
		
//		// in PHP5
//		$rubrica = array_combine(array(nome, cognome, prefissonome, sesso, email, descrizionestruttura),$row);
		$rubrica = $this->_unisciArray(array('nome', 'cognome', 'prefissonome', 'sesso', 'email', 'descrizionestruttura'),$row);
		
		$res->free();
		
		return $rubrica;
	
	}
	
	function _unisciArray($key, $values)
	{
		$tot = count($key);
		$newArray = array();
		for ($i=0; $i < $tot; $i++)
		{
			$indice 			= $key[$i];
			$newArray[$indice] 	= $values[$i];
		}
//		var_dump($key);
		return $newArray; 
	}
	
	/**
	 * Ritorna un collaboratori dato l'id_utente del database
	 *
	 * @static
	 * @param int $id numero identificativo utente
	 * @return array Collaboratori
	 */
	function selectDocente($id, $isCodiceDocente = false)
	{
		
		$db = FrontController::getDbConnection('main');
	
		$cond = ($isCodiceDocente) ? 'cod_doc = ' : 'id_utente = ';
		
		$query = 'SELECT id_utente,	cod_doc, nome_doc FROM docente WHERE '.$cond.$db->quote($id);
//		var_dump($query); die;
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();
		if( $rows == 0) {$ret = false; return $ret;}

		$row = $res->fetchRow();
		$docente = new Docente($row[0], $row[1], $row[2]);
		
		return $docente;
	
	}
	
	function selectDocenteFromCod($codDoc)
	{
		$docente = Docente::selectDocente($codDoc, true);
		return $docente;
	}
	
	
}
