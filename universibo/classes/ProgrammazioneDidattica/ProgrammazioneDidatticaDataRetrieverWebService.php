<?php

require_once('WebService_WsDidatticaServerImplService_Didattica'.PHP_EXTENSION);
require_once ('ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetriever'.PHP_EXTENSION);

/**
 * ProgrammazioneDidatticaDataRetrieverWebService
 *
 * Preleva le informazioni della programmazione didattica da un webservice.
 * Ha il compito di fare da wrapper alla stub del WebService
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
class ProgrammazioneDidatticaDataRetrieverWebService extends ProgrammazioneDidatticaDataRetriever
{
	
	var $web_service;
	
	public function __construct()
	{
		$this->web_service = new WebService_WsDidatticaServerImplService_Didattica("https://localhost:8443/axis/services/Didattica");
		
		// basic authentication
		$this->web_service->setOpt('curl', CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		$this->web_service->setOpt('curl', CURLOPT_USERPWD, "my_role:my_password");
		
		// verifica certificato
		$this->web_service->setOpt('curl', CURLOPT_SSL_VERIFYPEER, 0);

		//$this->web_service->setOpt('curl', CURLOPT_CAINFO, "/filename_certificato_per_la_verifica");
		/*
		If you specify a CAINFO, note that the file must be in PEM format! (If not, it won't work).
		Using Openssl you can use:
		openssl x509 -in <cert> -inform d -outform PEM -out cert.pem
		To create a pem formatted certificate from a binary certificate (the one you get if you download the ca somewhere).
		*/
		
	}
	
	
	
	function getFacoltaList(){
		return $this->web_service->getFacoltaList(); 
	}
	
	function getFacolta($codFac){
		return $this->web_service->getFacolta($codFac); 
	}
	
	function getCorsoListFacolta($codFac){
		return $this->web_service->getCorsoListFacolta($codFac); 
	}
	
	function getCorso($codCorso){
		return $this->web_service->getCorso($codCorso); 
	}
	
	function getMateria($codMateria){
		return $this->web_service->getMateria($codMateria); 
	}
	
	function getDocente($codDoc){
		return $this->web_service->getDocente($codDoc); 
	}
	
	function getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico){
		return $this->web_service->getAttivitaDidatticaPadreCorso($codCorso, $annoAccademico); 
	}
	
	function getAttivitaDidatticaCorso($codCorso, $annoAccademico){
		return $this->web_service->getAttivitaDidatticaCorso($codCorso, $annoAccademico); 
	}
	
	function getSdoppiamentiAttivitaDidattica($attivitaPadre){
		return $this->web_service->getSdoppiamentiAttivitaDidattica($attivitaPadre); 
	}

}
