<?php

namespace Universibo\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Universibo\Bundle\CoreBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new Group('Admin');
        $admin->addRole('ROLE_ADMIN');
        $manager->persist($admin);
        $this->addReference('admin-group', $admin);

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

    public function getOrder()
    {
        return 1;
    }
}
