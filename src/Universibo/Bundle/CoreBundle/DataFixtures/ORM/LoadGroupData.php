<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\Group;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadGroupData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new Group('Admin');
        $admin->addRole('ROLE_ADMIN');
        $manager->persist($admin);
        
        $collab = new Group('Collaborators');
        $collab->addRole('ROLE_USER');
        $manager->persist($collab);
        
        $professors = new Group('Professors');
        $professors->addRole('ROLE_USER');
        $manager->persist($professors);
        
        $staff = new Group('Staff');
        $staff->addRole('ROLE_USER');
        $manager->persist($staff);
        
        $students = new Group('Students');
        $students->addRole('ROLE_USER');
        $manager->persist($students);
        
        $manager->flush();
    }
}
