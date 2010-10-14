<?php

/**
 * ProgrammazioneDidatticaDataRetrieverFactory.
 *
 * Implementa il pattern Fatory per ritornare un'istanza di una classe che
 * implementa ProgrammazioneDidatticaDataRetriever.
 * Due possibili implementazioni sono per esempio l'implementazione tramite
 * Web Service o tramite accesso a Database 
 * 
 *
 * @package universibo
 * @version 2.1.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class ProgrammazioneDidatticaDataRetrieverFactory
{
	/**
	 * @param string $type tipo di DataRetriever da utilizzare: "web_service", "database_unibo"
	 *
	 * @return ProgrammazioneDidatticaDataRetriever 
	 */
	function &getProgrammazioneDidatticaDataRetriever($type)
	{
		switch ($type){
			case "web_service" :
				require_once("ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetrieverWebService".PHP_EXTENSION);
				return new ProgrammazioneDidatticaDataRetrieverWebService();
			case "database_unibo" :
				require_once("ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetrieverDatabaseUnibo".PHP_EXTENSION);
				return new ProgrammazioneDidatticaDataRetrieverDatabaseUnibo();
		}

	}	

}

?>
