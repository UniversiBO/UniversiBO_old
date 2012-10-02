<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class FileEditTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotLogged()
    {
        $sentences = array (
                'Error!',
                'Non hai i diritti per modificare il file La sessione potrebbe essere scaduta'
        );

        $this->logout();
        $this->openPrefix('/file/15051/edit');
        $this->assertSentences($sentences);
    }

    public function testSimple()
    {
        $sentences = array (
                'robots SEO',
        );

        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/file/15051/edit');

        $this->assertSentences($sentences);

        $this->type('id=f13_abstract', $text = 'robots SEO, '.md5(rand(1,10)));
        $this->clickAndWait('name=f13_submit');

        $this->assertSentence('modificato con successo');

        $this->clickAndWait('link=Torna ai dettagli del file');

        $this->assertSentence($text);
    }
}
