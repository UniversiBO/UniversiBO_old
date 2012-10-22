<?php
namespace Universibo\Bundle\LegacyBundle\Routing;

use Symfony\Component\Routing\RouterInterface;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * Channel router
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ChannelRouter
{
    const BASE = 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $routes;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->routes = array (
            self::BASE . 'Cdl' => 'universibo_legacy_cdl',
            self::BASE . 'Facolta' => 'universibo_legacy_facolta',
            self::BASE . 'Insegnamento' => 'universibo_legacy_insegnamento',
            self::BASE . 'PrgattivitaDidattica' => 'universibo_legacy_show_insegnamento',
        );
    }

    /**
     * Generates url for a Channel
     *
     * @param  Canale  $channel
     * @param  boolean $absolute
     * @return string
     */
    public function generate(Canale $channel, $absolute = false)
    {
        $route = $this->getRoute($channel);

        return $this
            ->router
            ->generate($route, array('id_canale' => $channel->getIdCanale()), $absolute)
        ;
    }

    /**
     * @param  Canale $channel
     * @return string
     */
    private function getRoute(Canale $channel)
    {
        foreach($this->routes as $class => $route)
        {
            if($channel instanceof $class) {
                return $route;
            }
        }
        
        return 'universibo_legacy_canale';
    }
}
