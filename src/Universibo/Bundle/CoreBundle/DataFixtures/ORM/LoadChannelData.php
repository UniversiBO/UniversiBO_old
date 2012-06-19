<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Application\Sonata\UserBundle\Entity\Group;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadChannelData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $home = new Channel();
        $home->setName('Homepage');
        $home->setType('default');
        $home->setHits(0);
        $home->setSlug('');
        
        $manager->persist($home);
        
        $manager->flush();
    }
}
