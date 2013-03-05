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
        $this->logout();
        $this->openPrefix('/file/1/edit');
        $this->assertLoginRequired();
    }

    public function testSimple()
    {
        $sentences = array (
                'robots SEO',
        );

        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/file/1/edit');

        $this->assertSentences($sentences);

        $this->type('id=f13_abstract', $text = 'robots SEO, '.md5(rand(1,10)));
        $this->type('id=f13_parole_chiave', implode(PHP_EOL, $keywords = array('robots', 'SEO', md5(rand(1,10)))));
        $this->clickAndWait('name=f13_submit');
        $this->assertSentence('modificato con successo');

        $this->clickAndWait('link=Torna ai dettagli del file');

        $this->assertSentences(array_merge(array($text), $keywords));

        $this->openPrefix('/file/1/edit');
        $this->type('id=f13_parole_chiave', $keywords = 'robots');
        $this->clickAndWait('name=f13_submit');
        $this->clickAndWait('link=Torna ai dettagli del file');

        $this->assertSentences(array($text, $keywords));
    }

    public function testAddPassword()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/file/1/edit');

        if (!$this->isChecked($checkbox = 'name=f13_password_enable')) {
            $this->click($checkbox);
        }

        $password = 'HelloWorld!';
        $this->type('name=f13_password', $password);
        $this->type('name=f13_password_confirm', $password);

        $this->clickAndWait('name=f13_submit');
        $this->assertSentence('modificato con successo');
    }
}
