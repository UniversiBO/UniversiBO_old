<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $user;
    
    protected function setUp()
    {
        $this->user = new User();
    }
    
    public function testShibUsernameAccessors()
    {
        $shibUsername = 'test'.rand(1,20).'@example.com';
        
        $this->assertSame($this->user, $this->user->setShibUsername($shibUsername));
        $this->assertEquals($shibUsername, $this->user->getShibUsername());
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
    
}
