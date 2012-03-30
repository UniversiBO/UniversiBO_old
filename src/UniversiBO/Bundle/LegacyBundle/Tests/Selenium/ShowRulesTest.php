<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowRulesTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'REGOLAMENTO PER L\'UTILIZZO DEI SERVIZI',
                'INFORMATIVA SULLA PRIVACY',
                'NORME PER L\'UTILIZZO DEL FORUM'
        );

        $this->open('/index.php?do=ShowRules');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}