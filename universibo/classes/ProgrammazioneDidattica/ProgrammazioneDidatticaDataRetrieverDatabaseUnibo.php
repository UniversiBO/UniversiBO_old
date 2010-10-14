<?php

require_once ('ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetriever'.PHP_EXTENSION);

/**
 * ProgrammazioneDidatticaDataRetrieverDatabaseUnibo
 *
 * Preleva le informazioni della programmazione didattica da un database.
 * l'identificativo della connessione usata è programmazione_didattica
 * lo scehma del database utilizzato per l'inserimento dei dati è quello 
 * presente in dbms/tipo_db/input_schema_programmazione_didattica.sql
 *
 * @package universibo
 * @version 2.1.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */


class ProgrammazioneDidatticaDataRetrieverDatabaseUnibo extends ProgrammazioneDidatticaDataRetriever
{
	
	function getFacoltaList(){
		/**
		 * @todo
		 */
	}
	
	function getFacolta($codFac){
		/**
		 * @todo
		 */
	}
	
	function getCorsoListFacolta($codFac){
		/**
		 * @todo
		 */
	}
	
	function getCorso($codCorso){
		/**
		 * @todo
		 */
	}
	
	function getMateria($codMateria){
		/**
		 * @todo
		 */
	}
	
	function getDocente($codDoc){
		/**
		 * @todo
		 */
	}
	
	function getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico){
		/**
		 * @todo
		 */
	}
	
	function getAttivitaDidatticaCorso($codCorso, $annoAccademico){
		/**
		 * @todo
		 */
	}
	
	function getSdoppiamentiAttivitaDidattica($attivitaPadre){
		/**
		 * @todo
		 */
	}

}

?>
