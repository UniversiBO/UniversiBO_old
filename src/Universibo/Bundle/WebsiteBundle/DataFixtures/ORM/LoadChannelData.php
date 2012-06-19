<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Universibo\Bundle\DidacticsBundle\Entity\Faculty;

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
        
        $areaLaureati = new Channel();
        $areaLaureati->setName('Area Laureati');
        $areaLaureati->setType('default');
        $areaLaureati->setSlug('area-laureati');
        $areaLaureati->setHits(0);
        $manager->persist($areaLaureati);
        
        $ingegneria = new Channel();
        $ingegneria->setName('Ingegneria');
        $ingegneria->setType('faculty');
        $ingegneria->setSlug('ingegneria');
        $ingegneria->setHits(0);
        $manager->persist($ingegneria);
        
        $ingegneriaFaculty = new Faculty();
        $ingegneriaFaculty->setChannel($ingegneria);
        $ingegneriaFaculty->setCode('0021');
        $ingegneriaFaculty->setUrl('http://www.ing.unibo.it/Ingegneria/default.htm');
        $manager->persist($ingegneriaFaculty);
        
        $manager->flush();
    }
}
