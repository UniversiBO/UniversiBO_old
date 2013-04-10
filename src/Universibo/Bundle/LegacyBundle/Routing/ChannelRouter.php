<?php
namespace Universibo\Bundle\LegacyBundle\Routing;

use Symfony\Component\Routing\RouterInterface;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\MainBundle\Entity\Channel;

/**
 * Channel router
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ChannelRouter
{
    const BASE = 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\';
    const BASE2 = 'UniversiBO\\Bundle\\MainBundle\\Entity\\';

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
            self::BASE2 . 'SchoolChannel' => 'universibo_main_school',
        );
    }

    public function generate($channel, $absolute = false)
    {
        if ($channel instanceof Canale) {
            return $this->generateCanale($channel, $absolute);
        }

        if ($channel instanceof Channel) {
            return $this->generateChannel($channel, $absolute);
        }
    }

    /**
     * Generates url for a Channel
     *
     * @param  Canale  $channel
     * @param  boolean $absolute
     * @return string
     */
    private function generateCanale(Canale $channel, $absolute = false)
    {
        if ($channel->getTipoCanale() == Canale::HOME) {
            return $this->router->generate('universibo_legacy_home');
        }

        $route = $this->getRoute($channel);

        return $this
            ->router
            ->generate($route, array('id_canale' => $channel->getIdCanale()), $absolute)
        ;
    }

    private function generateChannel(Channel $channel, $absolute = false)
    {
        $route = $this->getRoute($channel);

        return $this
            ->router
            ->generate($route, array('slug' => $channel->getSlug()), $absolute)
        ;
    }

    /**
     * @param  Canale $channel
     * @return string
     */
    private function getRoute($channel)
    {
        foreach ($this->routes as $class => $route) {
            if ($channel instanceof $class) {
                return $route;
            }
        }

        return 'universibo_legacy_canale';
    }
}
