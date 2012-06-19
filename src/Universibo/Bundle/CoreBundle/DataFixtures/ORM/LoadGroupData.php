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
        
        $students = new Group('Students');
        $students->addRole('ROLE_USER');
        $manager->persist($students);
        
        $staff = new Group('Staff');
        $staff->addRole('ROLE_USER');
        $manager->persist($students);
        
        $manager->flush();
    }
}
