<?php
namespace UniversiBO\Legacy\Tests;

class ChangePasswordTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testChangePassword()
    {
        $this->login($username='brain', $oldPassword='padrino');
        $this->changePassword($username, $oldPassword, $newPassword='madrina');
        $this->logout();
         
        $this->login($username, $newPassword);
        $this->changePassword($username, $newPassword, $oldPassword);
        $this->logout();

        $this->login($username, $oldPassword);
    }

    private function changePassword($username, $oldPassword, $newPassword)
    {
        $this->open('/index.php?do=ChangePassword');

        $this->type('name=f6_old_password', $oldPassword);
        $this->type('name=f6_new_password1', $newPassword);
        $this->type('name=f6_new_password2', $newPassword);
        $this->clickAndWait('name=f6_submit');
    }
}