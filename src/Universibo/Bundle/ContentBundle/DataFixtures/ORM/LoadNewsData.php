<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

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
        $news = new News();
        $news->setTitle('News title');
        $news->setContent('News content www.google.it');
        $news->getChannels()->add($this->getReference('laureati-channel'));
        $news->getChannels()->add($this->getReference('ingegneria-channel'));
        $news->setUser($this->getReference('admin-user'));
        $news->setDeleted(false);
        
        $manager->persist($news);
        
        $manager->flush();
    }

    /**
     * (non-PHPdoc)
     * @see Doctrine\Common\DataFixtures.OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 3;
    }
}
