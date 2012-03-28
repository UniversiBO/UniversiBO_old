<?php  

use UniversiBO\Legacy\Framework\FrontController;

require_once('Files/FileKeyWords'.PHP_EXTENSION);
require_once('Files/FileItem'.PHP_EXTENSION);
require_once('Commenti/CommentoItem'.PHP_EXTENSION);


/**
 * FileItemStudenti class
 *
 * Rappresenta un singolo file degli studenti.
 *
 * @package universibo
 * @subpackage Files
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */
 
//define('COMMENTO_ELIMINATO', 'S');
//define('COMMENTO_NOT_ELIMINATO', 'N');

class FileItemStudenti extends FileItem {
	
	/**
	 * Recupera un file dal database
	 *
	 * @static
	 * @param int $id_file  id del file
	 * @return FileItem 
	 */
	function  selectFileItem($id_file) {
		$id_files = array ($id_file);
		$files = & FileItemStudenti :: selectFileItems($id_files);
		if ($files === false)
			return false;
		return $files[0];
	}

	/**
	 * Recupera un elenco di file dal database
	 * non ritorna i files eliminati
	 *
	 * @static
	 * @param array $id_file array elenco di id dei file
	 * @return array FileItem 
	 */
	function  selectFileItems($id_files) {

		$db = & FrontController::getDbConnection('main');

		if (count($id_files) == 0)	{$return = array(); return $return; }

		//esegue $db->quote() su ogni elemento dell'array
		//array_walk($id_notizie, array($db, 'quote'));
		if (count($id_files) == 1)
			$values = $id_files[0];
		else
			$values = implode(',', $id_files);

//		$query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
//						 A.descrizione, data_inserimento, data_modifica, dimensione, download,
//						 nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
//						 username, C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
//						 FROM file A, utente B, file_categoria C, file_tipo D 
//						 WHERE A.id_utente = B.id_utente AND A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
		$query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
						 A.descrizione, data_inserimento, data_modifica, dimensione, download,
						 nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
						 C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
						 FROM file A, file_categoria C, file_tipo D 
						 WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
		$res = & $db->query($query);

		//echo $query;
		
		if (DB :: isError($res))
			Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

		$rows = $res->numRows();

		if ($rows == 0)
			return false;
		$files_list = array ();

		while ($res->fetchInto($row)) {
			$username = User::getUsernameFromId($row[3]);
			$files_list[] = new FileItemStudenti($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $username , $row[15], $row[16], $row[17], $row[18]);
		}

		$res->free();

		return $files_list;
	}
	
	
	/**
	 * aggiunge il file al canale specificato
	 *
	 * @param int $id_canale   identificativo del canale
	 * @return boolean  true se esito positivo 
	 */
	function addCanale($id_canale) {
		$return = true;

		if (!Canale :: canaleExists($id_canale)) {
			return false;
			//Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il canale selezionato non esiste','file'=>__FILE__,'line'=>__LINE__));
		}

		$db = & FrontController :: getDbConnection('main');

		$query = 'INSERT INTO file_studente_canale (id_file, id_canale) VALUES ('.$db->quote($this->getIdFile()).','.$db->quote($id_canale).')';
		//? da testare il funzionamento di =
		$res = $db->query($query);
		if (DB :: isError($res)) {
			return false;
			//	$db->rollback();
			//	Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}

		$this->elencoIdCanale[] = $id_canale;

		return true;

	}
	
	
	/**
	 * rimuove il file dal canale specificato
	 *
	 * @param int $id_canale   identificativo del canale
	 */
	function removeCanale($id_canale) {

		$db = & FrontController :: getDbConnection('main');

		$query = 'DELETE FROM file_studente_canale WHERE id_canale='.$db->quote($id_canale).' AND id_file='.$db->quote($this->getIdFile());
		//? da testare il funzionamento di =
		$res = & $db->query($query);

		if (DB :: isError($res))
			Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

		// rimuove l'id del canale dall'elenco completo
//		var_dump($this->elencoIdCanali);
//		die();
//		$this->elencoIdCanali = array_diff($this->elencoIdCanali, array ($id_canale));

		/**
		 * @TODO settare eliminata = 'S' quando il file viene tolto dall'ultimo canale
		 */
	}
	
	/**
	 * Seleziona l' id_canale per i quali il file é inerente 
	 * 
	 * @return array	elenco degli id_canale
	 */
	
	function  getIdCanali() {
		if ($this->elencoIdCanali != null)
			return $this->elencoIdCanali;

		$id_file = $this->getIdFile();
		
		$db = & FrontController :: getDbConnection('main');

		$query = 'SELECT id_canale FROM file_studente_canale WHERE id_file='.$db->quote($id_file);
		$res = & $db->query($query);

		if (DB :: isError($res))
			Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

	
		$res->fetchInto($row);

		$return = array($row[0]);
		return $return;

	}
	
	/**
	 * Questa funzione verifica, dato un certo
	 * id_file se é un file di tipo studente 
	 *
	 * @param $id_file  id del file da verificare
	 * @return $flag	true o false
	 */
	
	function  isFileStudenti($id_file)
	{
		$flag = true;
		
		$db = & FrontController :: getDbConnection('main');

		$query = 'SELECT count(id_file) FROM file_studente_canale WHERE id_file='.$db->quote($id_file).' GROUP BY id_file';
		$res = & $db->query($query);

		if (DB :: isError($res))
			Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
		$res->fetchInto($ris);	
		if($ris[0]==0) $flag=false;
		
		return $flag;
	}
	
	/**
	 * Questa funzione restituisce il voto associato al file studente
	 *
	 * @param $id_file id del file
	 */
	 function  getVoto($id_file)
	 {
	 	
		$db = & FrontController :: getDbConnection('main');

		$query = 'SELECT avg(voto) FROM file_studente_commenti WHERE id_file='.$db->quote($id_file).' AND eliminato = '.$db->quote(COMMENTO_NOT_ELIMINATO).' GROUP BY id_file';
		$res = & $db->query($query);

		if (DB :: isError($res))
			Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
		$res->fetchInto($ris);	
			
		return $ris[0];
	 }
	 
	 /**
	  * Questa funzione cancella tutti i commenti associati al file studente
	  */
	  
	  function deleteAllCommenti()
	  {
	  	$db = FrontController::getDbConnection('main');
		ignore_user_abort(1);
		$return = true;
        $query = 'UPDATE file_studente_commenti SET eliminato = '.$db->quote(COMMENTO_ELIMINATO).'WHERE id_file='.$db->quote($this->id_file);
		$res = $db->query($query);
		if (DB :: isError($res))
			{				
				$db->rollback();
				Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
				$return = false;
			}
			ignore_user_abort(0);
		return $return;
	  }
	  
	/**
	 * Elimina il file studente 
	 *
	 * @return	 boolean true se avvenua con successo, altrimenti false
	 */
	function deleteFileItem() 
	{
		
		$db = & FrontController::getDbConnection('main');
		$query = 'UPDATE file SET eliminato  = '.$db->quote(FILE_ELIMINATO).' WHERE id_file = '.$db->quote($this->getIdFile());
		//echo $query;								 
		$res = $db->query($query);
		//var_dump($query);
		if (DB :: isError($res)) {
			$db->rollback();
			Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
		}
		return false;
	}
	
}
