<?php
namespace Universibo\Bundle\LegacyBundle\Command\Links;

use Universibo\Bundle\LegacyBundle\Entity\Links\Link;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

abstract class ShowLinksCommon extends PluginCommand
{
    /**
     * La funzione verifica se il link è interno o meno
     * @return boolean
     */
    protected function isInternalLink(Link $link)
    {
        $router = $this->get('router');
        $uri = $router->generate('universibo_legacy_home', array(), true);

        return preg_match('/^' . str_replace('/', '\\/', $uri) . '.*$/',
                $link->getUri());
    }
}
