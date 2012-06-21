<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Universibo\Bundle\DidacticsBundle\Entity\Faculty;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadChannelData extends AbstractFixture implements OrderedFixtureInterface
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
        $this->addReference('laureati-channel', $areaLaureati);
        $manager->persist($areaLaureati);

        $ingegneria = new Channel();
        $ingegneria->setName('Ingegneria');
        $ingegneria->setType('faculty');
        $ingegneria->setSlug('ingegneria');
        $ingegneria->setHits(0);
        $this->addReference('ingegneria-channel', $ingegneria);
        $manager->persist($ingegneria);

        $ingegneriaFaculty = new Faculty();
        $ingegneriaFaculty->setChannel($ingegneria);
        $ingegneriaFaculty->setCode('0021');
        $ingegneriaFaculty->setUrl('http://www.ing.unibo.it/Ingegneria/default.htm');
        $manager->persist($ingegneriaFaculty);

        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}
