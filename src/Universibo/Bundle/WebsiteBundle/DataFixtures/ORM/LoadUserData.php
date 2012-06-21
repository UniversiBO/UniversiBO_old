<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Universibo\Bundle\CoreBundle\Entity\User;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Universibo\Bundle\DidacticsBundle\Entity\Faculty;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * User Fixture
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface,
        ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * (non-PHPdoc)
     * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('password', $user->getSalt()));
        
        $adminGroup = $this->getReference('admin-group');
        $this->addReference('admin-user', $user);
        
        $user->setEnabled(true);
        $user->addGroup($adminGroup);
        $user->setEmail('example@example.com');
        
        $manager->persist($user);
        
        $manager->flush();
    }

    /**
     * (non-PHPdoc)
     * @see Doctrine\Common\DataFixtures.OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 2;
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\DependencyInjection.ContainerAwareInterface::setContainer()
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
