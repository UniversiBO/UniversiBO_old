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
use Universibo\Bundle\LegacyBundle\Entity\DBRuoloRepository;
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
     * Role repository
     *
     * @var DBRuoloRepository
     */
    private $roleRepo;

    /**
     * Constructor
     *
     * @param FactoryInterface         $factory
     * @param SecurityContextInterface $securityContext
     * @param ForumRouter              $forumRouter
     * @param UniversiBOAcl            $acl
     * @param ChannelRouter            $channelRouter
     * @param DBCanale2Repository      $channelRepo
     * @param DBRuoloRepository        $roleRepo
     */
    public function __construct(FactoryInterface $factory,
            SecurityContextInterface $securityContext, ForumRouter $forumRouter,
            UniversiBOAcl $acl, ChannelRouter $channelRouter,
            DBCanale2Repository $channelRepo, DBRuoloRepository $roleRepo)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->forumRouter = $forumRouter;
        $this->acl = $acl;
        $this->channelRouter = $channelRouter;
        $this->channelRepo = $channelRepo;
        $this->roleRepo = $roleRepo;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $home = $menu->addChild('Canali');
        $home->setAttribute('dropdown', true);
        $this->addChannelChildren($home, 'Facoltà', Canale::FACOLTA)->setAttribute('divider_append', true);
        $this->addChannelChildren($home, 'Servizi', Canale::CDEFAULT)->setAttribute('divider_append', true);

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $userId = $this->securityContext->getToken()->getUser()->getId();
            $this->addMyUniversiboChildren($menu, $userId);

            if ($this->securityContext->isGranted('ROLE_MODERATOR')) {
                $menu->addChild('navbar.dashboard', array('route' => 'universibo_dashboard_home'));
            }
        }

        $menu->addChild('Forum', array('uri' => $this->forumRouter->getIndexUri()));
        $this->addAboutChildren($home);

        return $menu;
    }

    /**
     * Adds channel menu items
     * @todo incomplete
     * @param MenuItem $item
     * @param integer  $channelType
     */
    private function addChannelChildren(MenuItem $menu, $label, $channelType)
    {
        $menuItem = $menu->addChild($label);
        $scontext = $this->securityContext;
        $token = $scontext->getToken();

        if ($token !== null) {
            $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ?
                    $token->getUser() : null;
        } else {
            $user = null;
        }

        $allowed = array();
        foreach ($this->channelRepo->findManyByType($channelType) as $item) {
            if ($this->acl->canRead($user, $item)) {
                $allowed[] = array(
                    'name' => $item->getNome(),
                    'uri'  => $this->channelRouter->generate($item)
                );
            }
        }

        usort($allowed, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        foreach ($allowed as $channel) {
            $menuItem->addChild($channel['name'], array('uri' => $channel['uri']));
        }

        return $menuItem;
    }

    private function addMyUniversiboChildren(MenuItem $menu, $userId)
    {
        $myUniversibo = $menu->addChild('MyUniversiBO');
        $myUniversibo->setAttribute('dropdown', true);

        $last = null;
        foreach ($this->roleRepo->findByIdUtente($userId) as $role) {
            if ($role->isMyUniversibo()) {
                $channel = $this->channelRepo->find($role->getIdCanale());

                $last = $myUniversibo->addChild($role->getNome() ?: $channel->getTitolo(), array(
                    'uri' => $this->channelRouter->generate($channel)
                ));
            }
        }

        if (null !== $last) {
            $last->setAttribute('divider_append', true);
        }

        $myUniversibo->addChild('Modifica', array(
            'route' => 'universibo_legacy_user',
            'routeParameters' => array(
                'id_utente' => $userId
            )
        ));
    }

    private function addAboutChildren(MenuItem $menu)
    {
        $menuItem = $menu->addChild('navbar.about');
        $menuItem->addChild('navbar.rules', array('route' => 'universibo_website_rules'));
        $menuItem->addChild('navbar.manifesto', array('route' => 'universibo_legacy_manifesto'));
        $menuItem->addChild('navbar.credits', array('route' => 'universibo_legacy_credits'));
    }
}
