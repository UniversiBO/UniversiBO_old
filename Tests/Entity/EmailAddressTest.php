<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\EmailAddress;

class EmailAddresTest extends ContactTest
{
    protected function setUp()
    {
        $this->contact = new EmailAddress();
    }
}
