<?php

require_once('Canale'.PHP_EXTENSION);

define('CDL_NUOVO_ORDINAMENTO'   ,1);
define('CDL_SPECIALISTICA'       ,2);
define('CDL_VECCHIO_ORDINAMENTO' ,3);


/**
 * Cdl class.
 *
 * Modella una facolt?.
 * Fornisce metodi statici che permettono l'accesso 
 * ottimizzato alle istanze di Facolt?
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Cdl extends Canale{
	
	/**
	 * @private
	 */
	var $cdlCodice = '';
	/**
	 * @private
	 */
	var $cdlNome = '';
	/**
	 * @private
	 */
	var $cdlCategoria = 0;
	/**
	 * @private
	 */
	var $cdlCodiceFacoltaPadre = '';
	/**
	 * @private
	 */
	var $cdlForumCatId = '';
	/**
	 * @private
	 */
	var $cdlCodDoc = '';

	
	
	/**
	 * Crea un oggetto Cdl 
	 *
	 * @param int $id_canale 		identificativo del canae su database
	 * @param int $permessi 		privilegi di accesso gruppi {@see User}
	 * @param int $ultima_modifica 	timestamp 
	 * @param int $tipo_canale 	 	vedi definizione dei tipi sopra
	 * @param string  $immagine		uri dell'immagine relativo alla cartella del template
	 * @param string $nome			nome del canale
	 * @param int $visite			numero visite effettuate sul canale
	 * @param boolean $news_attivo	se true il servizio notizie ? attivo
	 * @param boolean $files_attivo	se true il servizio false ? attivo
	 * @param boolean $forum_attivo	se true il servizio forum ? attivo
	 * @param int $forum_forum_id	se forum_attivo ? true indica l'identificativo del forum su database
	 * @param int $forum_group_id	se forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
	 * @param boolean $links_attivo se true il servizio links ? attivo
	 * @param string $cod_cdl		codice identificativo d'ateneo del corso di laurea a 4 cifre 
	 * @param string $nome_cdl		descrizione del nome del cdl
	 * @param int $categoria_cdl	categoria del tipo do cdl
	 * @param string $cod_facolta	codice identificativo d'ateneo della facolt? a cui appartiene il corso di laurea
	 * @param string $cod_doc		codice identificativo del docente
	 * @param string $forum_cat_id	identificativo categoria del forum
	 * @return Facolta
	 */
	function Cdl($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
				 $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo,
				 $cod_cdl, $nome_cdl, $categoria_cdl, $cod_facolta_padre, $cod_doc, $forum_cat_id)
	{

		$this->Canale($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
				 $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo);
		
		$this->cdlCodice	= $cod_cdl;
		$this->cdlNome		= $nome_cdl;
		$this->cdlCategoria	= $categoria_cdl;
		$this->cdlCodiceFacoltaPadre	= $cod_facolta_padre;
		$this->cdlForumCatId	= $forum_cat_id;
		$this->cdlCodDoc	= $cod_doc;
	}



	/**
	 * Restituisce il nome/descrizione del corso di laurea
	 *
	 * @return string
	 */
	function getNome()
	{
		return $this->cdlNome;
	}
	
	
	/**
	 * Imposta il nome/descrizione del corso di laurea
	 *
	 * @param string $nomeCdl nuovo nome CdL
	 */
	function setNome($nomeCdl)
	{
		$this->cdlNome = $nomeCdl;
	}



	/**
	 * Restituisce il titolo/nome completo del cdl
	 *
	 * @return string
	 */
	function getTitolo()
	{
		return "CORSO DI LAUREA DI \n".$this->getNome();
	}

	/**
	 * Restituisce la categoria del cdl
	 * 
	 * define('CDL_NUOVO_ORDINAMENTO'   ,1);
	 * define('CDL_SPECIALISTICA'       ,2);
	 * define('CDL_VECCHIO_ORDINAMENTO' ,3);
	 *
	 * @return int
	 */
	function getCategoriaCdl()
	{
		return $this->cdlCategoria;
	}

	
	/**
	 * Imposta la categoria del cdl
	 * 
	 * define('CDL_NUOVO_ORDINAMENTO'   ,1);
	 * define('CDL_SPECIALISTICA'       ,2);
	 * define('CDL_VECCHIO_ORDINAMENTO' ,3);
	 *
	 * @param int 
	 */
	function setCategoriaCdl($categoria)
	{
		$this->cdlCategoria = $categoria;
	}

	/**
	 * Trasforma i codici di tipo corso del dataretriever (forse)
	 * a categoria cdl usato in questa classe.
	 */
	function translateCategoriaCdl($categoria)
	{
		$translationTable = 
		array(	'LT' => CDL_NUOVO_ORDINAMENTO,
				'LS' => CDL_SPECIALISTICA,
				'L'  => CDL_VECCHIO_ORDINAMENTO);
		
		if (array_key_exists($categoria, $translationTable))
			$result = $translationTable[$categoria];
		else
			$result = CDL_NUOVO_ORDINAMENTO; //se non so cosa metterci di default butto questo
		
		return $result;
	}
	
	
	
	/**
	 * Ritorna la stringa descrittiva del titolo/nome breve del canale per il MyUniversiBO
	 *
	 * @return string
	 */
	function getNomeMyUniversiBO()
	{
		return $this->getNome().' - '.$this->getCodiceCdl();
	}

	/**
	 * Restituisce il codice della facoltà a cui afferisce il cdl
	 *
	 * @return string
	 */
	function getCodiceFacoltaPadre()
	{
		return $this->cdlCodiceFacoltaPadre;
	}


	/**
	 * Imposta il codice della facoltà a cui afferisce il cdl
	 * @todo bisogna estendere a più facoltà perchè la relazione è n-n e non 1-n
	 *
	 * @return string
	 */
	function setCodiceFacoltaPadre($codFac)
	{
		$this->cdlCodiceFacoltaPadre = $codFac;
	}
	
	
	/**
	 * Restituisce il codice di ateneo a 4 cifre del cdl
	 * es: ingegneria informatica -> '0048'
	 *
	 * @return string
	 */
	function getCodiceCdl()
	{
		return $this->cdlCodice;
	}

	/**
	 * Impsta il codice di ateneo a 4 cifre del cdl
	 * es: ingegneria informatica -> '0048'
	 *
	 * @return string
	 */
	function setCodiceCdl($cod_new)
	{
		$this->cdlCodice = $cod_new;
	}

	/**
	 * Crea un oggetto Cdl dato il suo numero identificativo id_canale
	 * Ridefinisce il factory method della classe padre per restituire un oggetto
	 * del tipo Cdl
	 *
	 * @static
	 * @param int $id_canale numero identificativo del canale
	 * @return mixed Facolta se eseguita con successo, false se il canale non esiste
	 */
	function &factoryCanale($id_canale)
	{
		$return = Cdl::selectCdlCanale($id_canale);
		return $return;
	}
	

	/**
	 * Restituisce l'uri/link che mostra un canale
	 *
	 * @return string uri/link che mostra un canale
	 */
	function showMe()
	{
		return 'index.php?do=ShowCdl&id_canale='.$this->id_canale;
	}
	
	
	/**
	 * Seleziona da database e restituisce l'elenco di tutti gli oggetti Cdl corso di laurea 
	 * 
	 * @static
	 * @param boolean $canaliAttivi se restituire solo i Cdl gi? associati ad un canale o tutti
	 * @return mixed array di Cdl se eseguita con successo, false in caso di errore
	 */
	function & selectCdlAll()
	{
	
		$db =& FrontController::getDbConnection('main');
	
		$query = 'SELECT cod_corso FROM classi_corso WHERE 1 = 1';
		
		$res = $db->query($query);
		if (DB::isError($res))
		{
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
			return false;
		}
		
		$elencoCdl = array();
		
		while($res->fetchInto($row))
		{
			//echo $row[0];
			if ( ($elencoCdl[] =& Cdl::selectCdlCodice($row[0]) ) === false )
				return false;
		}
		
		return $elencoCdl;

	}

	/**
	 * Seleziona da database e restituisce l'oggetto corso di laurea 
	 * corrispondente al codice id_canale 
	 * 
	 * @static
	 * @param int $id_canale identificativo su DB del canale corrispondente al corso di laurea
	 * @return mixed Cdl se eseguita con successo, false se il canale non esiste
	 */
	function &selectCdlCanale($id_canale)
	{

		$db =& FrontController::getDbConnection('main');
	
		$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
					 a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale AND a.id_canale = '.$db->quote($id_canale);

		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
//		var_dump($res);
		$rows = $res->numRows();
		
		if( $rows == 0) return false;

		$res->fetchInto($row);
		$cdl =& new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
				$row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S', $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
		
		return $cdl;

	}
	
	

	/**
	 * Seleziona da database e restituisce l'oggetto Cdl 
	 * corrispondente al codice $cod_cdl 
	 * 
	 * @static
	 * @param string $cod_cdl stringa a 4 cifre del codice d'ateneo del corso di laurea
	 * @return Facolta
	 */
	function &selectCdlCodice($cod_cdl)
	{

		$db =& FrontController::getDbConnection('main');
	
		
		// LA PRIMA QUERY E' QUELLA CHE VA BENE, MA BISOGNA ALTRIMENTI SISTEMARE IL DB 
			//E VERIFICARE CHE METTENDO DIRITTI = 0 IL CANALE NON VENGA VISUALIZZATO
		//$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,
		//			 a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale AND b.cod_corso = '.$db->quote($cod_cdl);
					 
		$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
                                         a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM  classi_corso b LEFT OUTER JOIN canale a ON a.id_canale = b.id_canale WHERE b.cod_corso = '.$db->quote($cod_cdl);			 
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		if( $rows == 0) return false;

		$res->fetchInto($row);
		$cdl =& new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
				$row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S', $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
		return $cdl;

	}
	

	
	/**
	 * Seleziona da database e restituisce un'array contenente l'elenco 
	 * in ordine alfabetico di tutti i cdl appartenenti alla facolt? data 
	 * 
	 * @static
	 * @param string $cod_facolta stringa a 4 cifre del codice d'ateneo della facolt?
	 * @return array(Cdl)
	 */
	function &selectCdlElencoFacolta($cod_facolta)
	{

		$db =& FrontController::getDbConnection('main');
	
		$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
					 a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale
					 AND b.cod_fac = '.$db->quote($cod_facolta).' ORDER BY 17 , 15 ';

		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		if( $rows == 0) { $ret = array(); return $ret;}
		$elenco = array();
		while (	$res->fetchInto($row) )
		{
			$cdl =& new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
				$row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
				$row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);

			$elenco[] =& $cdl;
		}
		
		return $elenco;
	}
	
	/** 
	 * Restituisce l'uri del command che visulizza il canale
	 *	
	 * @return string URI del command 
	 */
	 function getShowUri()
	 {
	 	return 'index.php?do=ShowCdl&id_canale='.$this->getIdCanale();
	 }
	
	/** 
	 * Restituisce il codice docente del presidente del CDL
	 *	
	 * @return string URI del command 
	 */
	function getCodDocente()
	{
		return $this->cdlCodDoc;
	}
	
	
	/** 
	 * Imposta il codice docente del presidente del CDL
	 *	
	 * @return string URI del command 
	 */
	function setCodDocente($codDoc)
	{
		$this->cdlCodDoc = $codDoc;
	}
	
	
	/** 
	 * Ritorna l'id categoria del forum
	 *	
	 * @return int $cat_id 
	 */
	function getForumCatId()
	{
		return $this->cdlForumCatId;
	}
	
	
	/** 
	 * Imposta l'id categoria del forum
	 *	
	 * @param int $cat_id 
	 */
	function setForumCatId($cat_id)
	{
		$this->cdlForumCatId = $cat_id;
	}
	
	
	function updateCdl()
	{
		
		$db =& FrontController::getDbConnection('main');
		
 		$query = 'UPDATE classi_corso SET cat_id = '.$db->quote($this->getForumCatId()).
					', cod_corso = '.$db->quote($this->getCodiceCdl()).
					', desc_corso = '.$db->quote($this->getNome()).
					', cod_fac = '.$db->quote($this->getCodiceFacoltaPadre()).
					', categoria = '.$db->quote($this->getCategoriaCdl()).
					', cod_doc =' .$db->quote($this->getCodDocente()).
				' WHERE id_canale = '.$db->quote($this->getIdCanale());
		
		$res = $db->query($query);
//		$rows =  $db->affectedRows();
		if (DB::isError($res))
			Error::throwError(_ERROR_DEFAULT,array('msg'=>$query,'file'=>__FILE__,'line'=>__LINE__)); 
		
		$this->updateCanale();
	}


	function insertCdl()
	{
		
		$db =& FrontController::getDbConnection('main');
		
		if ($this->insertCanale() != true)
		{ 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore inserimento Canale','file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		
		$query = 'INSERT INTO classi_corso (cod_corso, desc_corso, categoria, cod_doc, cod_fac, id_canale) VALUES ('.
					$db->quote($this->getCodiceCdl()).' , '.
					$db->quote($this->getNome()).' , '.
					$db->quote($this->getCategoriaCdl()).' , '.
					$db->quote($this->getCodDocente()).' , '.
					$db->quote($this->getCodiceFacoltaPadre()).' , '.
					$db->quote($this->getIdCanale()).' )';
		$res = $db->query($query);
		if (DB::isError($res))
		{ 
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		
		return true;
	}
}