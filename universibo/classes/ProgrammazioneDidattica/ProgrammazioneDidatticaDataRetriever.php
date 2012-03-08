<?php

/**
 * ProgrammazioneDidatticaDataRetriever
 *
 * Classe interfaccia per descrivere un oggetto che permetta
 * di recuperare i dati della programmazione didattica
 *
 * @package universibo
 * @version 2.1.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */


class ProgrammazioneDidatticaDataRetriever
{
	
	/**
	 * @abstract
	 * @return Facolta[]
	 */
	function getFacoltaList(){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codFac
	 * @return Facolta
	 */
	function getFacolta($codFac){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codFac
	 * @return Corso[]
	 */
	function getCorsoListFacolta($codFac){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codCorso
	 * @return Corso 
	 */
	function getCorso($codCorso){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codMateria
	 * @return  Materia
	 */
	function getMateria($codMateria){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codDoc
	 * @return  Docente 
	 */
	function getDocente($codDoc){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codCorso
	 * @param int $annoAccademico
	 * @return AttivitaDidattica[] 
	 */
	function getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param string $codCorso
	 * @param int $annoAccademico
	 * @return AttivitaDidattica[] 
	 */
	function getAttivitaDidatticaCorso($codCorso, $annoAccademico){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}
	
	/**
	 * @abstract
	 * @param AttivitaDidattica $attivitaPadre
	 * @return AttivitaDidattica[] 
	 */
	function getSdoppiamentiAttivitaDidattica($attivitaPadre){
		Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il metodo execute del command deve essere ridefinito','file'=>__FILE__,'line'=>__LINE__) );
	}

}
