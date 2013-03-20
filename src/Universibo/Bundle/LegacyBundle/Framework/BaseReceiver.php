<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

/**
 * The receiver.
 * Code to activate the framework system.
 * One application can be built by multiple receivers.
 *
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
    private $container;
    private $do;

    /**
     * Costruttore del Receiver
     *
     * @param string $identifier       indentifier of receiver
     * @param string $config_file      configuration file for this receiver (applicatio)
     * @param string $framework_path   percorso in cui si trovano i file del framework
     * @param string $application_path percorso in cui si trovano i file dell'applicazione
     */
    public function __construct($identifier, $config_file, $framework_path, $application_path, $container, $do = null)
    {
        $this->frameworkPath = $framework_path;
        $this->applicationPath = $application_path;

        $this->configFile = $config_file;
        $this->receiverIdentifier = $identifier;
        $this->container = $container;
        $this->do = $do;
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
     * Main code for framework activation, includes Error definitions
     * and instantiates FrontController
     */
    public function main()
    {
        $fc= new FrontController($this, $this->do);

        $fc->setConfig( $this->configFile );

        $result = $fc->executeCommand();
        $fc->getDbConnection('main')->disconnect();

        return $result;
    }

    public function getContainer()
    {
        return $this->container;
    }
}
