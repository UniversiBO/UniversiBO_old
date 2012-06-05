<?php
use Universibo\Bundle\LegacyBundle\Entity\Ruolo;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

if (in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '::1',
))) {
    $env = 'dev';
} else {
    $env = 'prod';
}

//list($usec, $sec) = explode(" ", microtime());
//$page_time_start = ((float) $usec + (float) $sec);

// TODO hack orrendo per caricare ruolo e le relative costanti
class_exists('Universibo\\Bundle\\LegacyBundle\\Entity\\Ruolo');

/**
 * The receiver.
 * Code to activate the framework system.
 * One application can be built by multiple receivers.
 *
 * @package framework
 * @version 1.0.0
 * @author Deepak Dutta, http://www.eocene.net,
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Receiver
{

    public $frameworkPath = '../framework';
    public $applicationPath = '../universibo';

    public $configFile = '../config.xml';
    public $receiverIdentifier = 'main';
    private $kernel;

    /**
     * Costruttore del Receiver
     *
     * @param string $identifier       indentifier of receiver
     * @param string $config_file      configuration file for this receiver (applicatio)
     * @param string $framework_path   percorso in cui si trovano i file del framework
     * @param string $application_path percorso in cui si trovano i file dell'applicazione
     */
    public function __construct($identifier, $config_file, $framework_path, $application_path, $kernel)
    {
        $this->frameworkPath = $framework_path;
        $this->applicationPath = $application_path;

        $this->configFile = $config_file;
        $this->receiverIdentifier = $identifier;
        $this->kernel = $kernel;
    }



    /**
     * Return the receiver name identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->receiverIdentifier;
    }




    /**
     * Set PHP language settings (path, gpc, error_reporting)
     */
    public function _setPhpEnvirorment()
    {

        //error reporting activation (enabled on testing system)
        error_reporting(E_ALL);

        //output buffering
        //ob_start('ob_gzhandler');

        //session initialization
        session_start();
        if (!array_key_exists('SID',$_SESSION) ) {
            $_SESSION['SID'] = SID;
        }

        if (defined('PATH_SEPARATOR')) {
            $pathDelimiter = PATH_SEPARATOR;
        } else {
            $pathDelimiter = ( substr(php_uname(), 0, 7) == "Windows") ? ';' : ':' ;
        }

        ini_set('include_path', $this->frameworkPath.$pathDelimiter.$this->applicationPath.'/classes'.$pathDelimiter.ini_get('include_path'));

        if (get_magic_quotes_gpc()) {
            function stripslashes_deep($value)
            {
                return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
            }

            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
        }

        if ( get_magic_quotes_runtime() == 1 ) {
            set_magic_quotes_runtime(0);
        }
    }


    /**
     * Main code for framework activation, includes Error definitions
     * and instantiates FrontController
     */
    public function main()
    {
        $this->_setPhpEnvirorment();

        $fc= new FrontController($this);

        $fc->setConfig( $this->configFile );

        $fc->executeCommand();
        $fc->getDbConnection('main')->disconnect();
    }

    public function getKernel()
    {
        return $this->kernel;
    }
}

$receiver = new Receiver('main', '../config.xml', '../framework', '../universibo', new AppKernel($env, $env !== 'prod'));
$receiver->main();

//list($usec, $sec) = explode(" ", microtime());
//$page_time_end = ((float) $usec + (float) $sec);

//printf("%01.5f", $page_time_end - $page_time_start);
