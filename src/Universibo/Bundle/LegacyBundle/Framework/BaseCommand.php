<?php
namespace Universibo\Bundle\LegacyBundle\Framework;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * BaseCommand is the abstract super class of all command classes.
 *
 * @package framework
 * @version 1.1.0
 * @author  Ilias Bartolini <brain79@virgilio.it>
 * @license {@link http://www.opensource.org/licenses/gpl-license.php}
 */
abstract class BaseCommand extends ContainerAware
{
    /**
     * @var FrontController
     */
    protected $frontController;

    /**
     * Initializes the base command link to front controller
     *
     * This method must be called from son classes
     * parent::initCommand();
     *
     * @param FrontController $frontController
     */
    public function initCommand(FrontController $frontController )
    {
        $this->frontController = $frontController;
    }

    /**
     * Abstract method must be overridden from sons-classes
     *
     * @return string template identifier if command uses template engine
     */
    abstract public function execute();

    /**
     * Shutdown the command
     *
     * This method must be overridden from Commands that need shutdown
     */
    public function shutdownCommand()
    {
    }

    /**
     * Return front controller
     *
     * @return FrontController
     */
    public function getFrontController()
    {
        return $this->frontController;
    }

    /**
     * Executes plugin
     *
     * @param  string $name  identifier name for this plugin
     * @param  mixed  $param a parameter handled by PluginCommand
     * @return mixed  return value of plugin
     */
    public function executePlugin($name, $param)
    {
        $fc = $this->getFrontController();

        return $fc->executePlugin($name,$this, $param);
    }

    protected function getContainer()
    {
        return $this->container;
    }
}
