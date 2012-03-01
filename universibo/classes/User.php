<?php

require_once('Ruolo'.PHP_EXTENSION);
require_once('PrgAttivitaDidattica'.PHP_EXTENSION);

define('USER_NONE'	     	,0);
define('USER_OSPITE'     	,1);
define('USER_STUDENTE'   	,2);
define('USER_COLLABORATORE' ,4);
define('USER_TUTOR'      	,8);
define('USER_DOCENTE'    	,16);
define('USER_PERSONALE'  	,32);
define('USER_ADMIN'      	,64);
define('USER_ALL'        	,127);

define('USER_ELIMINATO','S');
define('USER_NOT_ELIMINATO','N');

// TODO: se si cambia il nick da dare agli utenti cancellati bisogna o aggiornare tutto il db, o mantenere uno storico di tali nick
// e nel metodo isUsernameValid() bisogna controllare che lo username sia diverso da tali nick
define('NICK_USER_ELIMINATO','ex-utente'); // VERIFY o meglio: "utente non più registrato" o "un tempo era utente"?


/**
 * User class
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class User {

	/**
	 * @access private
	 */
	var $id_utente = 0;

	/**
	 * @access private
	 */
	var $username = '';

	/**
	 * @access private
	 */
	var $MD5 = '';

	/**
	 * @access private
	 */
	var $email = '';

	/**
	 * @access private
	 */
	var $ultimoLogin = 0;

	/**
	 * @access private
	 */
	var $bookmark = NULL; //array()

	/**
	 * @access private
	 */
	var $ADUsername = '';

	/**
	 * @access private
	 */
	var $groups = 0;

	/**
	 * @access private
	 */
	var $notifica = 0;

	/**
	 * @access private
	 */
	var $ban = false;

	/**
	 * @access private
	 */
	var $phone = '';

	/**
	 * @access private
	 */
	var $defaultStyle = '';

	/**
	 * @access private
	 */
	var $eliminato = '';



	/**
	 *  Verifica se la sintassi dello username ? valido.
	 *  Sono permessi fino a 25 caratteri: alfanumerici, lettere accentate, spazi, punti, underscore
	 *
	 * @static
	 * @param string $username stringa dello username da verificare
	 * @return boolean
	 */
	function isUsernameValid( $username )
	{
		$username = trim($username);
		$username_pattern='/^([[:alnum:]àèéìòù \._]{1,25})$/';
		return preg_match($username_pattern , $username ) && strcasecmp($username, NICK_USER_ELIMINATO) != 0;
	}



	/**
	 *  Verifica se la sintassi della password ? valida.
	 *  Lunghezza min 5, max 30 caratteri
	 *
	 * @param string $password stringa della password da verificare
	 * @return boolean
	 */
	public static function isPasswordValid( $password )
	{
		//$password_pattern='/^([[:alnum:]]{5,30})$/';
		//preg_match($password_pattern , $password );
		$length = strlen( $password );
		return ( $length > 5 && $length < 30 );
	}


	/**
	 * Genera una password casuale
	 *
	 * @static
	 * @return string password casuale
	 */
	function generateRandomPassword($length = 8)
	{
		$chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		$max_chars = count($chars) - 1;

		$hash = md5(microtime());
		$loWord = substr($hash, -8);
		$seed = hexdec($loWord);
		$seed &= 0x7fffffff;

		mt_srand( $seed );

		$rand_str = '';
		for($i = 0; $i < $length; $i++)
		{
			$rand_str = $rand_str . $chars[mt_rand(0, $max_chars)];
		}

		return $rand_str;
	}



	/**
	 * Restituisce l'array associativo del codice dei gruppi e
	 * della corrispettiva stringa descrittiva.
	 *
	 * @static
	 * @param boolean $singolare
	 * @return array
	 */
	function groupsNames( $singolare = true )
	{
		if ( $singolare == true )
		{
			return array(
					USER_OSPITE		=> "Ospite",
					USER_STUDENTE		=> "Studente",
					USER_COLLABORATORE	=> "Collaboratore",
					USER_TUTOR			=> "Tutor",
					USER_DOCENTE		=> "Docente",
					USER_PERSONALE		=> "Personale non docente",
					USER_ADMIN			=> "Admin");
		}
		else
		{
			return array(
					USER_OSPITE     => "Ospiti",
					USER_STUDENTE   => "Studenti",
					USER_COLLABORATORE => "Collaboratori",
					USER_TUTOR      => "Tutor",
					USER_DOCENTE    => "Docenti",
					USER_PERSONALE  => "Personale non docente",
					USER_ADMIN      => "Admin");
		}
	}



	/**
	 * Crea un oggetto User
	 *
	 * In pratica non dovrebbe mai essere necessario utilizzarlo a meno che non si voglia
	 * creare un utente "custom", l'utente andrebbe sempre creato attraverso il medoto
	 * factory selectUser
	 *
	 * @see selectUser
	 * @param int $id_utente numero identificativo utente, -1 non registrato du DB, 0 utente ospite
	 * @param int $groups nuovo gruppo da impostare
	 * @param string $username username dell'utente
	 * @param string $MD5 hash MD5 della password utente
	 * @param string $email indirizzo e-mail dell'utente
	 * @param int $ultimo_login timestamp dell'utlimo login all'interno del sito
	 * @param string $AD_username username dell'active directory di ateneo dell'utente
	 * @param array() $bookmark array con elenco dei id_canale dell'utente associati ai rispettivi ruoli
	 * @return User
	 */
	function User($id_utente, $groups, $username=NULL, $MD5=NULL, $email=NULL, $notifica=NULL, $ultimo_login=NULL, $AD_username=NULL, $phone='', $defaultStyle='', $bookmark=NULL, $eliminato = USER_NOT_ELIMINATO)
	{
		$this->id_utente   = $id_utente;
		$this->groups      = $groups;
		$this->username    = trim($username);
		$this->email       = $email;
		$this->ADUsername  = $AD_username;
		$this->ultimoLogin = $ultimo_login;
		$this->MD5         = $MD5;
		$this->notifica    = $notifica;
		$this->phone	   = $phone;
		$this->defaultStyle	= $defaultStyle;
		$this->bookmark    = $bookmark;
		$this->eliminato	= $eliminato;
	}



	/**
	 * Ritorna lo username dello User
	 *
	 * @return string
	 */
	function getUsername()
	{
		return $this->username;
	}



	/**
	 * Ritorna il livello di notifica dei messaggi
	 *
	 * @return string
	 */
	function getLivelloNotifica()
	{
		return $this->notifica;
	}

	/**
	 * Imposta il livello di notifica dei messaggi
	 *
	 * @param string $notifica il livello da impostare
	 */
	function setLivelloNotifica($notifica)
	{
		$this->notifica = $notifica;
	}


	/**
	 * Ritorna l'ID dello User nel database
	 *
	 * @return int
	 */
	function getIdUser()
	{
		return $this->id_utente;
	}



	/**
	 * Ritorna la email dello User
	 *
	 * @return int
	 */
	function getEmail()
	{
		return $this->email;
	}



	/**
	 * Imposta la email dello User
	 *
	 * @param string $email nuova email da impostare
	 * @param boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
	 * @return boolean
	 */
	function updateEmail($email, $updateDB = false)
	{
		$this->email = $email;
		if ( $updateDB == true )
		{
			$db = FrontController::getDbConnection('main');

			$query = 'UPDATE utente SET email = '.$db->quote($email).' WHERE id_utente = '.$db->quote($this->getIdUser());
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			$rows = $db->affectedRows();
				
			if( $rows == 1) return true;
			elseif( $rows == 0) return false;
			else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		return true;
	}



	/**
	 * Ritorna lo OR bit a bit dei gruppi di appartenenza dello User
	 *
	 * es:  USER_STUDENTE|USER_ADMIN  =  2|64  =  66
	 *
	 * @return int
	 */
	function getGroups()
	{
		return $this->groups;
	}



	/**
	 * Imposta il gruppo di appartenenza dello User
	 *
	 * @param int $groups nuovo gruppo da impostare
	 * @param boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
	 * @return boolean
	 */
	function updateGroups($groups, $updateDB = false)
	{
		return $this->groups;
		if ( $updateDB == true )
		{
			$db = FrontController::getDbConnection('main');

			$query = 'UPDATE utente SET groups = '.$db->quote($groups).' WHERE id_utente = '.$db->quote($this->getIdUser());
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			$rows = $db->affectedRows();

			if( $rows == 1) return true;
			elseif( $rows == 0) return false;
			else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		return true;
	}



	/**
	 * Ritorna il timestamp dell'ultimo login dello User
	 *
	 * @return int
	 */
	function getUltimoLogin()
	{
		return $this->ultimoLogin;
	}



	/**
	 * Imposta il timestamp dell'ultimo login dello User
	 *
	 * @param int $ultimoLogin timestamp dell'ultimo login da impostare
	 * @param boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
	 * @return boolean
	 */
	function updateUltimoLogin($ultimoLogin, $updateDB = false)
	{
		$this->ultimoLogin = $ultimoLogin;
		if ( $updateDB == true )
		{
			$db = FrontController::getDbConnection('main');

			$query = 'UPDATE utente SET ultimo_login = '.$db->quote($ultimoLogin).' WHERE id_utente = '.$db->quote($this->getIdUser());
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			$rows = $db->affectedRows();

			if( $rows == 1) return true;
			elseif( $rows == 0) return false;
			else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		return true;
	}



	/**
	 * Ritorna un array contenente gli oggetti Ruolo associati ai canali dell'utente
	 *
	 * @return array
	 */
	function &getRuoli()
	{
		if ($this->bookmark == NULL)
		{
			$this->bookmark = array();
			$ruoli = Ruolo::selectUserRuoli($this->getIdUser());
			$num_elementi = count($ruoli);
			for ($i=0; $i<$num_elementi; $i++)
			{
				$this->bookmark[$ruoli[$i]->getIdCanale()] = $ruoli[$i];
			}
		}
		return $this->bookmark;
	}

	/**
	 * Ritorna un array contenente i nomi dei ruoli categorizzati per anno, selezionando l'eventuale canale passato
	 */
	function &getRuoliInfoGroupedByYear($id_canale = null)
	{
		$user_ruoli = & $this->getRuoli();
		$elenco_canali = array();
		$found = ($id_canale == null);
		foreach ($user_ruoli as $r)
		{
			if ($this->isAdmin() || $r->isReferente())
			{
				$elenco_canali[] = $r->getIdCanale();
				if (!$found && $r->getIdCanale() == $id_canale) $found = true;
			}
		}

		if(!$found && $this->isAdmin()) $elenco_canali[] = $id_canale;

		$elenco_canali_retrieve = array();

		foreach ($elenco_canali as $id_current_canale)
		{
			$current_canale = Canale::retrieveCanale($id_current_canale);
			$elenco_canali_retrieve[$id_current_canale] = $current_canale;
			$didatticaCanale = PrgAttivitaDidattica::factoryCanale($id_current_canale);
			//			var_dump($didatticaCanale);
			$annoCorso = (count($didatticaCanale) > 0)?
			$didatticaCanale[0]->getAnnoAccademico() : 'altro';
			$nome_current_canale = $current_canale->getTitolo();
			$f7_canale[$annoCorso][$id_current_canale] =
			array(	'nome' => $nome_current_canale,
					'spunta' => ($id_canale != null && $id_current_canale == $id_canale)? 'true' : 'false');
		}
		krsort($f7_canale);
		$tot = count($f7_canale);
		$list_keys = array_keys($f7_canale);
		for($i=0; $i<$tot; $i++)
		//			var_dump($f7_canale[$i]);
			uasort($f7_canale[$list_keys[$i]], array('User','_compareCanale'));
		return $f7_canale;


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
	 * Ritorna lo username dell'ActiveDirectory di ateneo associato all'utente corrente
	 *
	 * @return string
	 */
	function getADUsername()
	{
		return $this->ADUsername;
	}



	/**
	 * Imposta lo username dell'ActiveDirectory di ateneo associato all'utente corrente
	 *
	 * @param string $ADUsername username dell'ActiveDirectory di ateneo da impostare
	 * @param boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
	 * @return boolean
	 */
	function updateADUsername($ADUsername, $updateDB = false)
	{
		$this->ADUsername = $ADUsername;
		if ( $updateDB == true )
		{
			$db = FrontController::getDbConnection('main');

			$query = 'UPDATE utente SET ad_username = '.$db->quote($this->ADUsername).' WHERE id_utente = '.$db->quote($this->getIdUser());
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			$rows = $db->affectedRows();

			if( $rows == 1) return true;
			elseif( $rows == 0) return false;
			else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
			return false;
		}
		return true;
	}



	/**
	 * Ritorna la stringa con il numero di telefono
	 *
	 * @return string
	 */
	function getPhone()
	{
		return $this->phone;
	}



	/**
	 * Ritorna lo stile grafico predefinito
	 *
	 * @return string
	 */
	function getDefaultStyle()
	{
		return $this->defaultStyle;
	}



	/**
	 * Restituisce il nome del gruppo da usare nel blocchetto contatti
	 * (admin e collaboratori compaiono come studenti)
	 *
	 * @static
	 * @param boolean $singolare
	 * @return array
	 */
	function publicGroupsName( $singolare = true )
	{
		if ( $singolare == true )
		{
				
			return array(
					USER_OSPITE		=> "Ospite",
					USER_STUDENTE		=> "Studente",
					USER_COLLABORATORE	=> "Studente",
					USER_TUTOR			=> "Tutor",
					USER_DOCENTE		=> "Docente",
					USER_PERSONALE		=> "Personale non docente",
					USER_ADMIN			=> "Studente");
		}
		else
		{
			return array(
					USER_OSPITE        => "Ospiti",
					USER_STUDENTE      => "Studenti",
					USER_COLLABORATORE => "Studenti",
					USER_TUTOR         => "Tutor",
					USER_DOCENTE       => "Docenti",
					USER_PERSONALE     => "Personale non docente",
					USER_ADMIN         => "Studenti");
		}
	}



	/**
	 * Ritorna l'hash sicuro di una stringa
	 *
	 * @param string $string
	 * @return string
	 */
	function passwordHashFunction($string)
	{
		return md5($string);
	}



	/**
	 * Ritorna l'hash MD5 della password dell'utente
	 *
	 * @return string
	 */
	function getPasswordHash()
	{
		return $this->MD5;
	}



	/**
	 * Imposta l'hash della password dell'utente corrente
	 *
	 * @param string $hash stringa della codifica esadecimale dell'hash
	 * @param boolean $updateDB se true e l'id_utente>0 la modifica viene propagata al DB
	 * @return boolean
	 */
	function updatePasswordHash($hash, $updateDB = false)
	{
		$this->MD5 = $hash;
		if ( $updateDB == true )
		{
			$db = FrontController::getDbConnection('main');

			$query = 'UPDATE utente SET password = '.$db->quote($hash).' WHERE id_utente = '.$db->quote($this->getIdUser());
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			$rows = $db->affectedRows();

			if( $rows == 1) return true;
			elseif( $rows == 0) return false;
			else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
				
		}
		return true;
	}



	/**
	 * Imposta il numero di telefono
	 *
	 * @param boolean $phome il numero di telefono
	 * @return boolean
	 */
	function setPhone($phome)
	{
		$this->phone = $phome;
	}


	/**
	 * Imposta il nome del template di default
	 *
	 * @param boolean $defaultStyle nome del template di default
	 * @return boolean
	 */
	function setDefaultStyle($defaultStyle)
	{
		$this->defaultStyle = $defaultStyle;
	}


	/**
	 * Imposta i diritti per l'accesso ai servizi di interazione
	 *
	 * @param boolean $ban true se l'utente non ha accesso, false se l'utente ha accesso
	 * @return boolean
	 */
	function setBan($ban)
	{
		$this->ban = $ban;
	}


	/**
	 * Ritorna true se ad un utente ? impedito l'accesso ai servizi di interazione,
	 * la fase di autorizzazione deve tenere conto di quest? propriet?
	 *
	 * @return boolean
	 */
	function isBanned()
	{
		return $this->ban;
	}

	/**
	 * Ritorna true se l'utente ha voluto la cancellazione
	 *
	 * @return boolean
	 */
	function isEliminato()
	{
		return $this->eliminato == USER_ELIMINATO;
	}


	/**
	 * Imposta l'utente come eliminato. NB questa modifica non viene salvata
	 * automaticamente nel db. Bisogna invocare updateUser
	 *
	 * @return boolean
	 */
	function setEliminato($elimina = true)
	{
		return ($this->eliminato = ($elimina) ? USER_ELIMINATO : USER_NOT_ELIMINATO);
	}

	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Admin.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Admin.
	 *
	 * @static
	 * @return boolean
	 */
	function isAdmin( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( (int)$groups & (int)USER_ADMIN );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Personale.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Personale.
	 *
	 * @static
	 * @return boolean
	 */
	function isPersonale( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( (int)$groups & (int)USER_PERSONALE );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Docente.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Docente.
	 *
	 * @static
	 * @return boolean
	 */
	function isDocente( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( (int)$groups & (int)USER_DOCENTE );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Tutor.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Tutor.
	 *
	 * @static
	 * @return boolean
	 */
	function isTutor( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( $groups & USER_TUTOR );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Moderatori.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Moderatori.
	 *
	 * @static
	 * @return boolean
	 */
	function isCollaboratore( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( (int)$groups & (int)USER_COLLABORATORE );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Studenter.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Studente.
	 *
	 * @static
	 * @return boolean
	 */
	function isStudente( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		return (boolean) ( $groups & USER_STUDENTE );
	}



	/**
	 * Se chiamata senza parametri ritorna true se l'utente corrente appartiene al gruppo Ospite.
	 * Se chiamata in modo statico con il parametro opzionale ritorna true se il gruppo specificato appartiene al gruppo Ospite.
	 * Un utente non ? ospite se appartiene anche ad altri gruppi.
	 *
	 * @static
	 * @return boolean
	 */
	function isOspite( $groups = NULL )
	{
		if ( $groups == NULL ) $groups = $this->getGroups();

		if ( $groups == USER_OSPITE ) return true;
		return false;
	}



	/**
	 * Restituisce l'array dell'elenco dei nomi dei gruppi
	 * a cui appartiene una persona
	 *
	 * @static
	 * @param boolean $singolare
	 * @return array
	 */
	function getUserGroupsNames( $singolare = true )
	{
		$nomi_gruppi = User::groupsNames($singolare);
		$return = array();

		if ($this->isOspite())			$return[]=$nomi_gruppi[USER_OSPITE];
		if ($this->isStudente())		$return[]=$nomi_gruppi[USER_STUDENTE];
		if ($this->isCollaboratore())	$return[]=$nomi_gruppi[USER_COLLABORATORE];
		if ($this->isTutor())			$return[]=$nomi_gruppi[USER_TUTOR];
		if ($this->isDocente())			$return[]=$nomi_gruppi[USER_DOCENTE];
		if ($this->isPersonale())		$return[]=$nomi_gruppi[USER_PERSONALE];
		if ($this->isAdmin())			$return[]=$nomi_gruppi[USER_ADMIN];

		return $return;

	}



	/**
	 * Restituisce l'array dell'elenco dei nomi dei gruppi
	 * a cui appartiene una persona
	 *
	 * @static
	 * @param boolean $singolare
	 * @return array
	 */
	function getUserPublicGroupName( $singolare = true )
	{
		$nomi_gruppi = User::publicGroupsName($singolare);


		if ($this->isOspite())			return $nomi_gruppi[USER_OSPITE];
		if ($this->isStudente())		return $nomi_gruppi[USER_STUDENTE];
		if ($this->isCollaboratore())	return $nomi_gruppi[USER_COLLABORATORE];
		if ($this->isTutor())			return $nomi_gruppi[USER_TUTOR];
		if ($this->isDocente())			return $nomi_gruppi[USER_DOCENTE];
		if ($this->isPersonale())		return $nomi_gruppi[USER_PERSONALE];
		if ($this->isAdmin())			return $nomi_gruppi[USER_ADMIN];

	}



	/**
	 * Restituisce true se lo username specificato ? gi? registrato sul DB
	 *
	 * @static
	 * @param string $username username da ricercare
	 * @return boolean
	 */
	function usernameExists( $username )
	{
		$username = trim($username);

		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente FROM utente WHERE username = '.$db->quote($username);
		//		var_dump($query); die;
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		$rows = $res->numRows();

		if( $rows == 0) return false;
		elseif( $rows == 1) return true;
		else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
		return false;
	}


	/**
	 * Crea un oggetto utente collaboratore
	 *
	 * @static
	 * to do
	 	* @return mixed User se eseguita con successo, false se l'utente non esiste
	 */
	function &selectAllCollaboratori()
	{

		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente, groups FROM utente WHERE groups > 2 AND groups!= 8 AND groups != 16 AND groups!= 32 AND sospeso = '.$db->quote(USER_NOT_ELIMINATO);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));


		$rows = $res->numRows();

		$collaboratori = array();

		while($row = $res->fetchRow())
		{
			$collaboratori[] = new User($row[0], $row[1]);
		}

		return $collaboratori;
	}


	/**
	 * @static
	 * @param array	lista dei ruoli di cui si vogliono sapere gli appartenenti
	 * @return array array di lista di IdUser per ogni gruppo specificato
	 */
	function & getIdUsersFromDesiredGroups($arrayWithDesiredGroupsConstant)
	{
		$ret = array();
		if (count($arrayWithDesiredGroupsConstant) == 0)
			return $ret;
			
		$db = FrontController::getDbConnection('main');
		$groups = implode(', ', $arrayWithDesiredGroupsConstant);
		$query = 'SELECT id_utente, groups FROM utente WHERE groups IN '.$db->quote($groups);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			
		while ($row = $res->fetchRow())
			$ret[$row[1]][] = $row[0];

		return $ret;
	}



	/**
	 * Crea un oggetto utente dato il suo numero identificativo id_utente del database, 0 se si vuole creare un utente ospite
	 *
	 * @static
	 * @param int $id_utente numero identificativo utente
	 * @param boolean $dbcache se true esegue il pre-caching del bookmark in modo da migliorare le prestazioni
	 * @return mixed User se eseguita con successo, false se l'utente non esiste
	 */
	function &selectUser($id_utente)
	{

		if ($id_utente == 0)
		{
			$user = new User(0,USER_OSPITE);
			return $user;
		}
		elseif ($id_utente > 0)
		{
			$db = FrontController::getDbConnection('main');

			$query = 'SELECT username, password, email, ultimo_login, ad_username, groups, notifica, phone, default_style, sospeso  FROM utente WHERE id_utente = '.$db->quote($id_utente);
			$res = $db->query($query);
			if (DB::isError($res))
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

			$rows = $res->numRows();
			if( $rows > 1) Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
			if( $rows == 0) {
				$false = false; return $false;
			};

			$row = $res->fetchRow();
			$user = new User($id_utente, $row[5], $row[0], $row[1], $row[2], $row[6], $row[3], $row[4], $row[7], $row[8], NULL, $row[9]);
			return $user;
				
		}
	}



	/**
	 * Crea un oggetto utente dato il suo usernamedel database
	 *
	 * @static
	 * @param string $username nome identificativo utente
	 * @return mixed User se eseguita con successo, false se l'utente non esiste
	 */
	function &selectUserUsername($username)
	{
		$username = trim($username);

		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente, password, email, ultimo_login, ad_username, groups, notifica, phone, default_style, sospeso  FROM utente WHERE username = '.$db->quote($username);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

		$rows = $res->numRows();
		if( $rows > 1) Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
		if( $rows == 0) {
			$false=false;
			return $false;
		}

		$row = $res->fetchRow();
		$user = new User($row[0], $row[5], $username, $row[1], $row[2], $row[6], $row[3], $row[4], $row[7], $row[8], NULL, $row[9]);
		return $user;

	}



	/**
	 * Ritorna un array di oggetti utente che rispettano entrambe le stringhe di ricerca (AND)
	 * Possono essere usati _ e % come caratteri spaciali
	 *
	 * @static
	 * @param string $username nome identificativo utente
	 * @param string $username nome identificativo utente
	 * @return array di User
	 */
	function &selectUsersSearch($username = '%', $email = '%')
	{

		$username = trim($username);

		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente, password, email, ultimo_login, ad_username, groups, notifica, username, phone, default_style, sospeso  FROM utente WHERE username LIKE '.$db->quote($username) .' AND email LIKE '.$db->quote($email);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

		$users = array();

		while($row = $res->fetchRow())
		{
			$users[] = new User($row[0], $row[5], $row[7], $row[1], $row[2], $row[6], $row[3], $row[4], $row[8], $row[9], NULL, $row[10]);
		}

		return $users;

	}



	/**
	 * Inserisce su DB le informazioni riguardanti un nuovo utente
	 *
	 * @return boolean true se avvenua con successo, altrimenti Error object
	 */
	function insertUser()
	{
		$db = FrontController::getDbConnection('main');

		ignore_user_abort(1);
		$db->autoCommit(false);

		$query = 'SELECT id_utente FROM utente WHERE username = '.$db->quote($this->getUsername() );
		$res = $db->query($query);
		$rows = $res->numRows();

		if( $rows > 0)
		{
			$return = false;
		}
		else
		{
			$this->id_utente = $db->nextID('utente_id_utente');
			$utente_ban = ( $this->isBanned() ) ? 'S' : 'N';
			$utente_eliminato = ( $this->isEliminato() ) ? USER_ELIMINATO : USER_NOT_ELIMINATO;
				
			$query = 'INSERT INTO utente (id_utente, username, password, email, notifica, ultimo_login, ad_username, groups, ban, phone, sospeso, default_style) VALUES '.
					'( '.$db->quote($this->getIdUser()).' , '.
					$db->quote($this->getUsername()).' , '.
					$db->quote($this->getPasswordHash()).' , '.
					$db->quote($this->getEmail()).' , '.
					$db->quote($this->getLivelloNotifica()).' , '.
					$db->quote($this->getUltimoLogin()).' , '.
					$db->quote($this->getADUsername()).' , '.
					$db->quote($this->getGroups()).' , '.
					$db->quote($utente_ban).' , '.
					$db->quote($this->getPhone()).' , '.
					$db->quote($utente_eliminato).' , '.
					$db->quote($this->getDefaultStyle()).' )';
			$res = $db->query($query);
				
			if (DB::isError($res))
			{
				$db->rollback();
				Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
			}
			$db->commit();
				
			$return = true;
		}

		$db->autoCommit(true);
		ignore_user_abort(0);

		return $return;
	}



	/**
	 * Aggiorna il contenuto su DB riguardante le informazioni utente
	 *
	 * @return boolean true se avvenua con successo, altrimenti false e throws Error object
	 */
	function updateUser()
	{
		$db = FrontController::getDbConnection('main');
		$utente_ban = ( $this->isBanned() ) ? 'S' : 'N';
		$utente_eliminato = ( $this->isEliminato() ) ? USER_ELIMINATO : USER_NOT_ELIMINATO;

		$query = 'UPDATE utente SET username = '.$db->quote($this->getUsername()).
		', password = '.$db->quote($this->getPasswordHash()).
		', email = '.$db->quote($this->getEmail()).
		', notifica = '.$db->quote($this->getLivelloNotifica()).
		', ultimo_login = '.$db->quote($this->getUltimoLogin()).
		', ad_username = '.$db->quote($this->getADUsername()).
		', groups = '.$db->quote($this->getGroups()).
		', phone = '.$db->quote($this->getPhone()).
		', default_style = '.$db->quote($this->getDefaultStyle()).
		', sospeso = '.$db->quote($utente_eliminato).
		', ban = '.$db->quote($utente_ban).
		' WHERE id_utente = '.$db->quote($this->getIdUser());

		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		$rows = $db->affectedRows();

		if( $rows == 1) return true;
		elseif( $rows == 0) return false;
		else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
	}


	/**
	 * Restituisce true se l'utente dell'active directory ? gi? registrato sul DB
	 *
	 * @static
	 * @param string $ad_username username da ricercare
	 * @return boolean
	 */
	function activeDirectoryUsernameExists( $ad_username)
	{
		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente FROM utente WHERE ad_username = '.$db->quote($ad_username);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		$rows = $res->numRows();

		if( $rows == 0) return false;
		elseif( $rows == 1) return true;
		else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
		return false;
	}

	/**
	 * Resituisce l'id utente a partire dallo username dell'active directory
	 *
	 * @param string $ad_username username AD dell'utente
	 * @return mixed l'id utente se lo trova, altrimenti false
	 */
	function getIdFromADUsername( $ad_username )
	{
		$db = FrontController::getDbConnection('main');

		$query = 'SELECT id_utente FROM utente WHERE ad_username = '.$db->quote($ad_username);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		$rows = $res->numRows();

		if( $rows == 0) return false;
		elseif( $rows == 1)
		{
			$row = $res->fetchRow();
			return $row[0];
		}
		else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));

		return false;

	}

	/**
	 * Restituisce true se il gruppo dell'utente apparteniene ai gruppi specificati in $groups
	 * altrimenti false
	 *
	 * @param int $groups gruppi di cui si vuole verificare l'accesso
	 * @return boolean
	 */
	function isGroupAllowed($groups)
	{
		return (boolean) ((int)$this->groups & (int)$groups);
	}

	/**
	 * Restituisce il nick dello user avendo l'id
	 *
	 * @param $id_user id dello user
	 * @return il nickname
	 */

	function getUsernameFromId($id_user)
	{
		$db = FrontController::getDbConnection('main');

		$query = 'SELECT username FROM utente WHERE id_utente= '.$db->quote($id_user);
		$res = $db->query($query);
		if (DB::isError($res))
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		$rows = $res->numRows();
		if( $rows == 0)
			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non esiste un utente con questo id_user: '.$id_user,'file'=>__FILE__,'line'=>__LINE__));
		$res->fetchInto($row);
		$res->free();
		return $row[0];

	}


	/**
	 * Restituisce true se l'utente viene autenticato con successo sull'active directory di ateneo
	 *
	 * @static
	 * @param string $ad_username username utente
	 * @param string $ad_domain dominio dell'active directory
	 * @param string $ad_password password dell'utente
	 * @return boolean
	 */
	function activeDirectoryLogin($ad_username, $ad_domain, $ad_password, $adl_host, $adl_port )
	{

		@$javaADLoginSock = fsockopen($adl_host,    # the host of the server
				$adl_port,    # the port to use
				$errno,   # error number if any
				$errstr,  # error message if any
				3);   # give up after 5 secs

		if ( $javaADLoginSock == false )
		{
			Error::throwError(_ERROR_DEFAULT,array('msg'=>'Impossibile connettersi al server di autenticazione Active Directory di Ateneo, provare più tardi oppure segnalare l\'inconveniente allo staff','file'=>__FILE__,'line'=>__LINE__));
		}
		else
		{
			$xml_request = '<?xml version="1.0" encoding="UTF-8"?><ADLogIn><user username="'. mb_convert_encoding($ad_username, "UTF-8", "ISO-8859-1") .'" domain="'. mb_convert_encoding( $ad_domain , "UTF-8", "ISO-8859-1") . '" password="'. mb_convert_encoding( $ad_password , "UTF-8", "ISO-8859-1") . '" /></ADLogIn>';
			fputs ($javaADLoginSock, $xml_request."\n");

			$reply = fgets ($javaADLoginSock,4);

			fclose($javaADLoginSock);
				
			$result = substr($reply,0,2);
			if ($result == 'NO') return false;		// 'Autenticazione fallita';
			elseif ($result == 'OK') return true;	// 'Autenticazione corretta';
			else  die(); Error::throwError(_ERROR_DEFAULT,array('msg'=>'Risposta del server di autenticazione Active Directory di Ateneo non valida'.$result,'file'=>__FILE__,'line'=>__LINE__));

		}

	}

}

