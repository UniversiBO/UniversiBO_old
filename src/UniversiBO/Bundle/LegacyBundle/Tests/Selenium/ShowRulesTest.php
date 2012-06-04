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

        $this->openCommand('ShowRules');
        $this->assertSentences($sentences);
    }
}
