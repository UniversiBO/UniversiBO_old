<?php
namespace UniversiBO\Legacy\Tests;

class ShowAccessibility extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Dichiarazione di accessibilit',
                'vai all\'homepage',
                'vai al forum',
        );

        $this->open('/index.php?do=ShowAccessibility');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}