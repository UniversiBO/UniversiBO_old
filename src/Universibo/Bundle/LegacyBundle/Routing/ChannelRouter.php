<?php
namespace Universibo\Bundle\LegacyBundle\Routing;
use Symfony\Component\Routing\RouterInterface;

use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * Channel router
 *
 */
class ChannelRouter
{
    const BASE = 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\';

    /**
     * @var RouterInterface
     */
    private $router;

    private $routes;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->routes = array('default' => 'channel_show',
                self::BASE . 'Facolta' => 'faculty_show');
    }

    public function generate(Canale $channel, $absolute = false)
    {
        $route = $this->getRoute($channel);

        return $this->router
                ->generate($route, array('id' => $channel->getIdCanale()),
                        $absolute);
    }

    private function getRoute(Canale $channel)
    {
        return array_key_exists($class = get_class($channel), $this->routes) ? $this
                        ->routes[$class] : $this->routes['default'];
    }

}
