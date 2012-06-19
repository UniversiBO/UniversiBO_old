<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadChannelData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $homepage = new Channel();
        
        $homepage->setType('homepage');
        $homepage->setName('Homepage');
        $homepage->setSlug('');
        $homepage->setHits(0);
        
        $manager->persist($homepage);
        
        $manager->flush();
    }
}
