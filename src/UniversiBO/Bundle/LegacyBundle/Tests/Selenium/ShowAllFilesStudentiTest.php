<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowAllFilesStudentiTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @dataProvider provider
     */
    public function testOrder($order, $orderSentence)
    {
        $this->login('brain');
        $this->openCommand('ShowAllFilesStudenti', '&order='.$order);
        $this->assertSentences(array(
                'Tutti i Files Studenti presenti su UniversiBO',
                'ordinati per '.$orderSentence
        ));
    }

    public function provider()
    {
        return array (
                array(0, 'nome'),
                array(1, 'data di inserimento'),
                array(2, 'voto medio'),
        );
    }
}
