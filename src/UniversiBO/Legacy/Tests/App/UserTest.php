<?php
namespace UniversiBO\Legacy\Tests\App;

use UniversiBO\Legacy\App\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $_user;

    protected function setUp()
    {
        $this->_user = new User(0, User::ADMIN);
    }

    public function testIdGetter()
    {
        self::assertEquals(0, $this->_user->getIdUser());
    }
}