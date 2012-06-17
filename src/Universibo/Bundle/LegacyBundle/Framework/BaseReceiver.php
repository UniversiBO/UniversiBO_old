<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

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

abstract class BaseReceiver
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
        $pathDelimiter = PATH_SEPARATOR;


        ini_set('include_path', $this->frameworkPath.$pathDelimiter.$this->applicationPath.'/classes'.$pathDelimiter.ini_get('include_path'));
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
    }

    public function getKernel()
    {
        return $this->kernel;
    }
}
