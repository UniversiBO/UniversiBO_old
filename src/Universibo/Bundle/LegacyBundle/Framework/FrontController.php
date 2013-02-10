<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

define('MAIL_KEEPALIVE_NO', 0);
define('MAIL_KEEPALIVE_ALIVE', 1);
define('MAIL_KEEPALIVE_CLOSE', 2);

use DOMDocument;
use MySmarty;
use Smarty;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\LegacyBundle\App\ErrorHandlers;

/**
 * It is a front controller.
 * Instantiate it using a config file and run executeCommand method
 * to handle any action command.
 * It calls the execute method of an appropriate command controller
 * defined in the config file.
 *
 * @package framework
 * @version 1.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @author Roberto Floris
 * @author Davide Bellettini
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class FrontController
{
    /**
     * @access private
     */
    private $rootUrl = '';

    /**
     * @access private
     */
    private $defaultCommand;

    /**
     * @access private
     */
    private $commandClass;

    /**
     * @access private
     */
    private $receivers;

    /**
     * @access private
     */
    private $commandTemplate;

    /**
     * @access private
     */
    private $appSettings;

    /**
     * @access private
     */
    private $plugins;

    /**
     * @access private
     */
    private $templateEngine;

    /**
     * @var Container
     */
    private static $container;

    private $do;
    /**
     * Constuctor of front controller object based upon $configFile
     *
     * @param string $configFile filename of FrontController configuration file
     */
    public function __construct($receiver, $do = null)
    {
        self::$container = $receiver->getContainer();

        //		include_once('XmlDoc'.PHP_EXTENSION);

        $this->receiverIdentifier = $receiver->getIdentifier();

        $this->do = $do;
    }

    /**
     * Executes an action.
     *
     * A class SomeAction should exists and someAction should be
     * associated with this class in the config file.  Class SomeAction
     * is the command controller that can serve one or more commands.
     *
     * @access public
     */
    public function executeCommand()
    {
        //$command_request=$this->getCommandRequest();
        $command_class=$this->getCommandClass();
        $command_class = 'Universibo\\Bundle\\LegacyBundle\\Command\\'.$command_class;

        /**
         * @todo mettere controllo sull'avvenuta inclusione, altrimenti errore critico
         */
        $command = new $command_class;

        $command->setContainer(self::getContainer());
        $command->initCommand($this);
        $response = $command->execute();
        $command->shutdownCommand();

        if ($response instanceof Response) {
            return $response;
        }

        if ($response == NULL) $response='default';
        if (array_key_exists($response, $this->commandTemplate)) {
            $template = $this->commandTemplate[$response];

            $templateEngine = $this->getTemplateEngine();
            if (!$templateEngine->template_exists($template)) {
                echo $template;
                Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non e` presente il file relativo al template specificato: "'.$template.'"','file'=>__FILE__,'line'=>__LINE__));
            }

            return $templateEngine->fetch($template);
        }

    }



    /**
     * Executes a plugin action.
     *
     * A class SomePlugin should exists and someAction should be
     * associated with this class in the config file.
     *
     * @param string      $name         identifier name for this plugin
     * @param BaseCommand $base_command the caller BaseCommand
     * @param mixed       $param        a parameter handled by PluginCommand
     * @access public
     */
    public function executePlugin($name, &$base_command, $param=NULL)
    {
        $classValues = $this->getPluginClass($name);

        if ($classValues == null) {
            Error::throwError(_ERROR_DEFAULT,array('msg'=>'Non e` stato definito il plugin richiesto: '.$name ,'file'=>__FILE__,'line'=>__LINE__));

            return;
        }

        $plugin = new $classValues['bundleClass']($base_command);
        $plugin->setContainer(self::getContainer());

        return $plugin->execute($param);
    }

    /**
     * @author Pinto
     * @access public
     * @return array list of available plugin for current requested command
     */
    public function getAvailablePlugins ()
    {
        $list = array();
        foreach ($this->plugins as $pc) {
            //			$explodedPc = explode(".",$pc);
            //			$class_name = $explodedPc[count($explodedPc)-1];
            //			$list[]		= $class_name;
            $list[] = $this->_parsePluginInfo($pc);
        }

        return $list;
    }

    /**
     * Permette di redirigere la richiesa su un'altra pagina
     *
     * @param string $destination
     */
    public static function redirectUri($destination)
    {
        header('Location: '.$destination);
        exit();
    }

    /**
     * Returns the command class name and path (without .)
     *
     * @return string
     * @access public
     */
    public function getCommandClass()
    {
        return str_replace('.', '\\', $this->commandClass);
    }

    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * Returns the plugin command class associated in config file
     *
     * @param  string $name PluginCommand name associated in config file
     * @return mixed  array with PluginCommand class path/file if $name exists for the current command, null otherwise
     * @access public
     */
    public function getPluginClass($name)
    {
           if (!array_key_exists($name, $this->plugins) ) {
               return null;
           }

           return $this->_parsePluginInfo($this->plugins[$name]);
    }

    /**
     * @author Pinto
     * @access private
     * @return array plugin values
     */
    public function _parsePluginInfo ($plugin)
    {
           $pc = $plugin['class'];
           $explodedPc = explode(".",$pc);
           $file_namepath = implode("/",$explodedPc);
           $class_name = $explodedPc[count($explodedPc)-1];

           $ret = array('nameWithPath' => $file_namepath,'className' => $class_name);
           $ret['restrictedTo'] = (isset($plugin['restrictedTo']))? $plugin['restrictedTo'] : '';
           $ret['condition'] = (isset($plugin['condition']))? $plugin['condition'] : '';
           $ret['restrictedTo'] = str_replace(' ', '', $ret['restrictedTo']);
           $ret['bundleClass'] = 'Universibo\\Bundle\\LegacyBundle\\Command\\' . str_replace('.', '\\', $pc);

           $ret['restrictedTo'] = ($ret['restrictedTo'] != '') ? explode(',', $ret['restrictedTo']) : array();

           return $ret;
    }
    /**
     * Returns the current receiver identifier
     *
     * @return string
     * @access public
     */
    public function getReceiverId()
    {
           return $this->receiverIdentifier;
    }

    /**
     * Returns the receiver URL of the given receiver identifier
     * All allowed identifiers must be listed in config file
     *
     * @param  string  $receiverId receiver identifier
     * @param  boolean $relative   if true path is relative to rootUrl
     * @return string  ...mi ero scordato il commento non ricordo cosa ritorna!!!! argh!!!
     * @todo ...mi ero scordato il commento non ricordo cosa ritorna!!!! argh!!!
     * @access public
     */
    public function getReceiverUrl($receiverId, $relative=true)
    {
           if ( !array_key_exists($receiverId, $this->receivers) )
               Error::throwError(_ERROR_CRITICAL,array('msg'=>'Identificativo del receiver inesistente o non permesso','file'=>__FILE__,'line'=>__LINE__));

           if ($relative == true) {
               return $this->receivers[$receiverId];
           } else {
               return $this->rootUrl.$this->receivers[$receiverId];
           }
    }

    /**
     * Returns the config path url
     *
     * @return string
     * @access public
     */
    public function getRootPath()
    {
           return $this->rootUrl;
    }

    /**
     * Gets the current command string request
     * Returns default command string if not set
     * and  sets $_GET['do'] with default value
     *
     * @return string
     * @access public
     */
    public function getCommandRequest()
    {
        if (is_null($this->do)) {
            $this->do = $this->defaultCommand;
        }

        if (!array_key_exists('do',$_GET) || is_null($_GET['do'])) {
            $_GET['do'] = $this->do;
        }

         if ($this->do === '') {
               throw new NotFoundHttpException('Empty command name');
           }

           return $this->do;
    }

    /**
     * Set up the FrontController with the given $configFile
     *
     * @param string $configFile filename of FrontController configuration file
     * @access public
     */
    public function setConfig($configFile)
    {
        $this->_setErrorHandler();

        $config = new DOMDocument();
        $config->load($configFile);

        $this->_appSettings($config);
        $this->_setCommandClass($config);
    }

    /**
     * Imposta lo style del template da visualizzare...
     */
    public function setStyle($style)
    {
        $_SESSION['template_name'] = $style;
    }

    /**
     * Restituisce lo style del template da visualizzare...
     */
    public function getStyle()
    {
        return 'unibo';
    }

    /**
     * Defines error categories, sets the error handlers
     */
    private function _setErrorHandler()
    {
        $handlers = new ErrorHandlers();
        $handlers->register();
    }

    /**
     * Sets the framework application own settings
     *
     * @access private
     */
    private function _appSettings(\DOMDocument $config)
    {
        $this->appSettings = array();
        //		$appSettingNodes = &$config->getElementsByTagName("appSettings");
        //		var_dump($appSettingNodes);

        $figli =$config->documentElement->childNodes;
        //var_dump($figli);
        for ( $i = 0; $i < $figli->length; $i++ )
            if (($figlio = $figli->item($i)) != null) {
                if ($figlio->nodeType == XML_ELEMENT_NODE && $figlio->tagName == 'appSettings') {
                    $appSettingNode =& $figlio;
                    break;
                }
            }

            if($appSettingNode == NULL) return;

            $figliAppSettingNode = $appSettingNode->childNodes;
            for ($i=0; $i < $figliAppSettingNode->length; $i++) {
                $aSetting = $figliAppSettingNode->item($i);
                //			echo $i.' '.$aSetting->nodeName.'<br>';
                //				var_dump($aSetting);
                if ($aSetting->nodeType == XML_ELEMENT_NODE) {
                    $this->appSettings[$aSetting->tagName] = ($aSetting->hasChildNodes() == true) ? $aSetting->firstChild->nodeValue : '';
                }
            }
    }

    /**
        * Sets the framework current request command class
        *
        * @access private
        */
    public function _setCommandClass(\DOMDocument $config)
    {
        $commandString=$this->getCommandRequest();
        // @bug: qui il ->childNodes mi restituisce i figli di appsettings invce che dei figli di root
        $figliRoot = $config->documentElement->childNodes;
        //		var_dump($this);
        //		$listaNodiCommands =& $config->getElementsByTagName("commands");
        $cinfonode = null;
        for ($i = 0; $i < $figliRoot->length; $i++) {
            $iesimoFiglio = $figliRoot->item($i);
            //			var_dump($iesimoFiglio);
            if ($iesimoFiglio != null)
                if ($iesimoFiglio->nodeType == XML_ELEMENT_NODE && $iesimoFiglio->tagName == 'commands') {
                    $cinfonode =& $iesimoFiglio;
                    break;
                }

        }

        //	for ( $i = 0; $i < $listaNodiCommands->length; $i++ )
        //		{
        //			$iesimoFiglio =& $listaNodiCommands->item($i);
        //			var_dump($iesimoFiglio);
        //			if ($iesimoFiglio != null)
        //				if ($iesimoFiglio->nodeType == XML_ELEMENT_NODE && $iesimoFiglio->parentNode->nodeName == 'config' )
        //				{
        //					$cinfonode =& $iesimoFiglio;
        //					break;
        //				}
        //
        //		}

        //		var_dump($cinfonode);
        if($cinfonode == NULL)
            Error::throwError(_ERROR_CRITICAL,array('msg'=>'Elemento commands non trovato nel file di config','file'=>__FILE__,'line'=>__LINE__));
        // @TODO qui migliorerebbe molto o xpath o cache
        $figli = &$cinfonode->childNodes;
        //print_r($figli);
        for ($i=0; $i < $figli->length; $i++) {
            $child = $figli->item($i);
            if ($child->nodeType == XML_ELEMENT_NODE && $child->tagName == $commandString) {
                $commandNode = &$child;
                break;
            }
        }
        if (!isset($commandNode) ||is_null($commandNode)) {
            throw new NotFoundHttpException('Command not in configuration');
        }

                    $this->commandClass = $commandNode->getAttribute('class');
                    //		var_dump($commandNode->attributes[0]->value);
                    //reads allowed response for this BaseCommand
                    $this->commandTemplate=array();
                    $responses = $commandNode->getElementsByTagName('response');

                    for ($i=0; $i < $responses->length; $i++) {
                        $response = $responses->item($i);
                        if ($response->getAttribute('type') == 'template') {
                            $this->commandTemplate[$response->getAttribute('name')] = $response->firstChild->nodeValue;
                        }
                    }

                    $plugins = $commandNode->getElementsByTagName('pluginCommand');

                    for ($i=0; $i < $plugins->length; $i++) {
                        $plugin = $plugins->item($i);
                        //			$this->plugins[$plugin->getAttribute('name')] = $plugin->getAttribute('class');
                        $this->plugins[$plugin->getAttribute('name')] = array ('class' => $plugin->getAttribute('class'), 'restrictedTo' => $plugin->getAttribute('restrictedTo'), 'condition' => $plugin->getAttribute('condition'));
                    }

                    if(!isset($this->commandClass))
                        Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non e` definito l\'attributo class relativo al comando specificato nel file di config','file'=>__FILE__,'line'=>__LINE__));

                    if(empty($this->commandClass))
                        Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non e` specificata la classe relativa al comando spacificato nel file di config','file'=>__FILE__,'line'=>__LINE__));
    }

    /**
     * Returns values of elements added into the <appSettings> XML tag in config file
        *
        * @param string $identifier Setting name identifier of XML element tag
        * @return string text content of XML tag
        * @access public
        */
    public function getAppSetting( $identifier )
    {
        return $this->appSettings[$identifier];
    }

    /**
     * Factory method that creates a Pear::DB Connection object
     * If called with optional $dsn parameter sets the connection information
     * Implements singleton pattern for each connection
     *
     * @param  string $identifier Connection "name" identifier in config file
     * @param  string $dsn        optional sets the $identifier dsn connection
     * @return mixed  true, PearDB
     * @access public
     */
    public static function getDbConnection($identifier)
    {
        return self::$container->get('universibo_legacy.db.connection.'.$identifier);
    }

    /**
     * Factory method that creates a Smarty object
     * Implements singleton pattern, returns always the same object istance
     *
     * @return Smarty
     * @access public
     */
    public function getTemplateEngine()
    {
        static $templateEngine = NULL;
        //var_dump($templateEngine);

        //if ( defined('TEMPLATE_SINGLETON') ) {
        if ($templateEngine != NULL) {
            return $templateEngine ;
        } else {
            //define('TEMPLATE_SINGLETON','on');

            $templateEngine = new MySmarty();
            //fine mia aggiunta

            $kernel = $this->getContainer()->get('kernel');
            $root = $kernel->getRootDir();

            $templateEngine->template_dir  = realpath(__DIR__.'/../Resources/views/');
            $templateEngine->compile_dir   = $root . '/cache/smarty/compile';
            $templateEngine->config_dir    = realpath(__DIR__.'/../Resources/views-config/');
            $templateEngine->cache_dir     = $root . '/cache/smarty/cache';
            $templateEngine->compile_check = true;
            $templateEngine->debugging     = 'prod' !== $kernel->getEnvironment();

            return $templateEngine;
        }
    }

    public function getTemplateEngineSettings()
    {
        return $this->templateEngine;
    }

    /**
     * @return Swift_Mailer
     */
    public function getMailer()
    {
        return $this->getContainer()->get('mailer');
    }

    /**
     * Factory method that creates a Kronos object based on the config language info
     *
     * @return Krono object
     * @access public
     */
    public function getKrono()
    {
        return self::$container->get('universibo_legacy.krono');
    }
}
