<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ChangePasswordTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testChangePassword()
    {
        $this->login($username='brain', $oldPassword=TestConstants::DUMMY_PASSWORD);
        $this->changePassword($username, $oldPassword, $newPassword=TestConstants::DUMMY_PASSWORD2);
        $this->logout();

        $this->login($username, $newPassword);
        $this->changePassword($username, $newPassword, $oldPassword);
        $this->logout();

        $this->login($username, $oldPassword);
    }

    private function changePassword($username, $oldPassword, $newPassword)
    {
        $this->openCommand('ChangePassword');

        $this->type('name=f6_old_password', $oldPassword);
        $this->type('name=f6_new_password1', $newPassword);
        $this->type('name=f6_new_password2', $newPassword);
        $this->clickAndWait('name=f6_submit');
    }
}
