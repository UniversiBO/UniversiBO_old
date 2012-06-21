<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Universibo\Bundle\DidacticsBundle\Entity\Faculty;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadChannelData extends AbstractFixture implements
        OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

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
        $areaLaureati->getServices()->add($this->getReference('news-service'));
        $this->addReference('laureati-channel', $areaLaureati);
        $manager->persist($areaLaureati);

        $ingegneria = new Channel();
        $ingegneria->setName('Ingegneria');
        $ingegneria->setType('faculty');
        $ingegneria->setSlug('ingegneria');
        $ingegneria->setHits(0);
        $ingegneria->getServices()->add($this->getReference('news-service'));
        $this->addReference('ingegneria-channel', $ingegneria);
        $manager->persist($ingegneria);

        $ingegneriaFaculty = new Faculty();
        $ingegneriaFaculty->setChannel($ingegneria);
        $ingegneriaFaculty->setCode('0021');
        $ingegneriaFaculty->setUrl('http://www.ing.unibo.it/Ingegneria/default.htm');
        $manager->persist($ingegneriaFaculty);

        $manager->flush();
        
        $aclProvider = $this->container->get('security.acl.provider');
        
        foreach(array($homepage, $areaLaureati, $ingegneria) as $channel) {
            $objectIdentity = ObjectIdentity::fromDomainObject($channel);

            $acl = $aclProvider->createAcl($objectIdentity);
            $acl->insertObjectAce(new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY'), MaskBuilder::MASK_VIEW);
            $aclProvider->updateAcl($acl);
        }
    }

    public function getOrder()
    {
        return 1;
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
