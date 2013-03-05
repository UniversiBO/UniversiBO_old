<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class NewsAddTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testInsertNews()
    {
        $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/news/add/2');

        $this->type('name=f7_titolo', 'News title');
        $this->type('name=f7_testo', 'News text');
        $this->clickAndWait('name=f7_submit');

        $this->assertSentence('inserita con successo.');
    }
}
