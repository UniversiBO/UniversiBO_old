<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\PhoneNumber;

class PhoneNumberTest extends ContactTest
{
    protected function setUp()
    {
        $this->contact = new PhoneNumber();
    }
}
