<?php
// src/Acme/MainBundle/Menu/MenuBuilder.php

namespace Universibo\Bundle\WebsiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\ForumBundle\Routing\ForumRouter;
use Universibo\Bundle\LegacyBundle\Auth\UniversiBOAcl;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\DBCanale2Repository;
use Universibo\Bundle\LegacyBundle\Routing\ChannelRouter;

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
     * ACL
     *
     * @var UniversiBOAcl
     */
    private $acl;

    /**
     * Channel router
     *
     * @var ChannelRouter
     */
    private $channelRouter;

    /**
     * Channel repository
     *
     * @var DBCanale2Repository
     */
    private $channelRepo;

    /**
     * Constructor
     *
     * @param FactoryInterface         $factory
     * @param SecurityContextInterface $securityContext
     * @param ForumRouter              $forumRouter
     * @param UniversiBOAcl            $acl
     * @param ChannelRouter            $channelRouter
     * @param DBCanale2Repository      $channelRepo
     */
    public function __construct(FactoryInterface $factory,
            SecurityContextInterface $securityContext, ForumRouter $forumRouter,
            UniversiBOAcl $acl, ChannelRouter $channelRouter,
            DBCanale2Repository $channelRepo)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->forumRouter = $forumRouter;
        $this->acl = $acl;
        $this->channelRouter = $channelRouter;
        $this->channelRepo = $channelRepo;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu['Home']->setAttribute('dropdown', true);
        $this->addChannelChildren($menu['Home'], Canale::FACOLTA);
        $this->addChannelChildren($menu['Home'], Canale::CDEFAULT);

        if ($this->securityContext->isGranted('ROLE_MODERATOR')) {
            $menu->addChild('Dashboard', array('route' => 'universibo_dashboard_home'));
        }

        $menu->addChild('Forum', array('uri' => $this->forumRouter->getIndexUri()));

        return $menu;
    }

    /**
     * Adds channel menu items
     * @todo incomplete
     * @param MenuItem $item
     * @param integer  $channelType
     */
    private function addChannelChildren(MenuItem $menuItem, $channelType)
    {
        $scontext = $this->securityContext;
        $token = $scontext->getToken();

        if ($token !== null) {
            $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ?
                    $token->getUser() : null;
        } else {
            $user = null;
        }

        $acl = $this->acl;
        $router = $this->channelRouter;
        $channelRepo = $this->channelRepo;

        $allowed = array();
        foreach ($channelRepo->findManyByType($channelType) as $item) {
            if ($acl->canRead($user, $item)) {
                $allowed[] = array('name' => $item->getNome(), 'uri' => $router->generate($item));
            }
        }

        usort($allowed, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        foreach ($allowed as $channel) {
            $menuItem->addChild($channel['name'], array('uri' => $channel['uri']));
        }
    }
}
