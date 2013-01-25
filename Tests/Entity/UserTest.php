<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\User;

class UserTest extends EntityTest
{
    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        $this->user = new User();
    }

    public function testEncoderNameAccessors()
    {
        $this->autoTestAccessor($this->user, 'encoderName', 'md5', true);
    }

    public function testLegacyGroupsAccessors()
    {
        $groups = 64;

        $this->assertSame($this->user, $this->user->setLegacyGroups($groups));
        $this->assertEquals($groups, $this->user->getLegacyGroups());
    }

    public function testPhoneAccessors()
    {
        $phone = '+393401234567';

        $this->assertSame($this->user, $this->user->setPhone($phone));
        $this->assertEquals($phone, $this->user->getPhone());
    }

    public function testNotificationAccessors()
    {
        $notifications = 1;

        $this->assertSame($this->user, $this->user->setNotifications($notifications));
        $this->assertEquals($notifications, $this->user->getNotifications());
    }
    
    public function testForumIdAccessors()
    {
        $forumId = 4431;

        $this->assertSame($this->user, $this->user->setForumId($forumId));
        $this->assertEquals($forumId, $this->user->getForumId());
    }

    public function testUsernameLockedAccessors()
    {
        $locked = !$this->user->isUsernameLocked();

        $this->assertSame($this->user, $this->user->setUsernameLocked($locked));
        $this->assertEquals($locked, $this->user->isUsernameLocked());
    }

    public function testUsernameLockedDefaultValue()
    {
        $this->assertTrue($this->user->isUsernameLocked());
    }

    /**
     * @dataProvider provider
     * @param type $legacyGroups
     * @param type $role
     */
    public function testGroupsConversion($legacyGroups, $role)
    {
        $this->user->setLegacyGroups($legacyGroups);

        $this->assertTrue($this->user->hasRole($role), $role .' should be present');
    }

    /**
     * @dataProvider provider
     * @param type $legacyGroups
     * @param type $role
     */
    public function testGroupsConversion2($legacyGroups, $role)
    {
        $this->user->addRole($role);
        $this->assertEquals($legacyGroups, $this->user->getLegacyGroups());
        $this->user->removeRole($role);
        $this->assertEquals(0, $this->user->getLegacyGroups());
    }

    public function provider()
    {
        return array (
            array(2 , 'ROLE_STUDENT'),
            array(4 , 'ROLE_MODERATOR'),
            array(8 , 'ROLE_TUTOR'),
            array(16, 'ROLE_PROFESSOR'),
            array(32, 'ROLE_STAFF'),
            array(64, 'ROLE_ADMIN'),
        );
    }
}
