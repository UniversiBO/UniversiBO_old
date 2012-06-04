<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowHelpTopicTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Come faccio ad iscrivermi ad UniversiBO?',
                'Navigazione nel sito: i primi passi.',
                'Voglio mettere un file on line su UniversiBO: come posso fare?',
                'Come faccio a scaricare i files da UniversiBO?',
                'Come personalizzare My UniversiBO.',
                'e come gestire il servizio di News di UniversiBO.',
                'Cercare un utente e cambiare i diritti (solo Admin)',
                'Voglio inserire una notizia su UniversiBO: come posso fare?',
                'Modificare un insegnamento e cercare un codice docente (solo admin e collaboratori)'
        );

        $this->openCommand('ShowHelpTopic');
        $this->assertSentences($sentences);
    }
}
