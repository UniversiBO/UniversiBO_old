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
                'Collabora',
                'Come fare per collaborare?',
        );

        $this->open('/index.php?do=ShowContribute');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
    
    public function testSendQuestionario()
    {
        $this->open('/index.php?do=ShowContribute');
        
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
    }
}