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
}
