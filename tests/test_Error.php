<?php

require_once('Error'.PHP_EXTENSION);

/**
 * Examples for Error Class
 *
 * @package universibo_tests
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL {@link http://www.opensource.org/licenses/gpl-license.php}
 */

//some example callback handler functions...
function my_function($param)
{
    echo 'Errore critico!!! ',$param['msg'], '<br />
    file: ',$param['file'], '<br />
    line: ',$param['line'], '<br />
    log: ',$param['log'], '<br />';

    //die('esecuzione interrotta');
}

class handlers
{
    public function my_method($param)
    {
        echo 'Errore: ',$param['msg'], '<br />
        file: ',$param['file'], '<br />
        line: ',$param['line'], '<br />
        log: ',$param['log'], '<br />';

        //die();
        //header('Redirect: http://location/error_page.php');
    }
}

function my_function2($param)
{
    return 'Attenzione: '.$param['msg'].'<br /><br />';
}

// handler definition
Error::setHandler(_ERROR_CRITICAL,'my_function');
Error::setHandler(_ERROR_NOTICE,'my_function2');
// also a class method can be defined as handler
Error::setHandler(_ERROR_DEFAULT,array('Handlers','my_method'));

//generation and on-the-fly throwing of an _ERROR_CRITICAL
Error::throwError(_ERROR_CRITICAL,array('msg'=>'non puoi fare cos?!!!','file'=>__FILE__,'line'=>__LINE__));

//generation and on-the-fly throwing of an _ERROR_DEFAULT
Error::throwError(_ERROR_DEFAULT,array('msg'=>'questo ? un errore normale','file'=>__FILE__,'line'=>__LINE__));

//generation and deferred throwing of an _ERROR_DEFAULT
$mio_errore = new Error(_ERROR_DEFAULT, array('msg'=>'questo ? un altro messaggio di errore','file'=>__FILE__,'line'=>__LINE__));
$mio_errore->throwError();

//on-the-fly collecting of an _ERROR_NOTICE
Error::collect(_ERROR_NOTICE,array('msg'=>'raccoglimi 2','file'=>__FILE__,'line'=>__LINE__));

//generation and deferred collecting of an _ERROR_NOTICE
$mio_errore = new Error(_ERROR_NOTICE, array('msg'=>'raccoglimi 1','file'=>__FILE__,'line'=>__LINE__));
$mio_errore->collect();

//retrieving of all previously collected _ERROR_NOTICE and throwing them
while ( ($current_error = Error::retrieve(_ERROR_NOTICE)) !== false ) {
    echo $current_error->throwError();
}

