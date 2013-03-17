<?php
// src/Acme/MainBundle/Menu/MenuBuilder.php

namespace Universibo\Bundle\WebsiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\ForumBundle\Routing\ForumRouter;

class MenuBuilder
{
    /**
     * Menu factory
     *
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Security context
     * @var SecurityContextInterface
     */

    private $securityContext;

    /**
     * Forum router
     *
     * @var ForumRouter
     */
    private $forumRouter;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, ForumRouter $forumRouter)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->forumRouter = $forumRouter;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu['Home']->setAttribute('dropdown', true);

        $menu->addChild('Forum', array('uri' => $this->forumRouter->getIndexUri()));

        return $menu;
    }
}
