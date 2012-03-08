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

class User extends UniversiBO\Legacy\App\User {

}
