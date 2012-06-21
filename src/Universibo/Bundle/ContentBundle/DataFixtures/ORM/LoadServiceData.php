<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Universibo\Bundle\CoreBundle\Entity\ChannelService;

use Universibo\Bundle\ContentBundle\Entity\News;

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
class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * (non-PHPdoc)
     * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        $service = new ChannelService();
        $service->setName('files');
        $manager->persist($service);
        $this->addReference('files-service', $service);
        
        $service = new ChannelService();
        $service->setName('news');
        $manager->persist($service);
        $this->addReference('news-service', $service);
        
        $service = new ChannelService();
        $service->setName('links');
        $manager->persist($service);
        $this->addReference('links-service', $service);
        
        $manager->flush();
    }

    /**
     * (non-PHPdoc)
     * @see Doctrine\Common\DataFixtures.OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 1;
    }
}
