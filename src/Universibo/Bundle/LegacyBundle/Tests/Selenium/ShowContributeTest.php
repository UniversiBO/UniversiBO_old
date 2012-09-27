<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowContributeTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Collabora',
                'Come fare per collaborare?',
        );

        $this->openPrefix('/collabora');
        $this->assertSentences($sentences);
    }

    public function testSendQuestionario()
    {
        $this->openPrefix('/collabora');

        $this->type('name=f3_nome', 'Nome');
        $this->type('name=f3_cognome', 'Cognome');
        $this->type('name=f3_mail', 'email@example.com');
        $this->type('name=f3_tel', '051603708');
        $this->type('name=f3_cdl', 'Ingegneria Informatica');

        $this->click('id=f3_tempo_0');
        $this->click('id=f3_internet_0');
        $this->click('id=f3_prog');
        $this->type('name=f3_altro', 'Lorem ipsum');

        $this->click('name=f3_privacy');
        $this->clickAndWait('name=f3_submit');

        if ($this->isTextPresent('Error!')) {
            self::assertTrue($this->isTextPresent('stato impossibile inviare la notifica ai coordinatori'), 'Data should be saved');
        } else {
            self::assertTrue($this->isTextPresent('Grazie per aver compilato il questionario'), 'Thanks should be displayed');
        }
    }
}
