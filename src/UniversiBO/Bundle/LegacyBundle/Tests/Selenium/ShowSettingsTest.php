<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

use UniversiBO\Bundle\LegacyBundle\Tests\TestConstants;

class ShowSettingsTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    public function testNotLogged()
    {
        $this->deleteAllVisibleCookies();
        $this->openCommand('ShowSettings');
        
        $this->assertSentences(array('Error!', 'Non hai i diritti per accedere alla pagina la sessione potrebbe essere terminata'));
    }

    public function testAdmin()
    {
        $sentences = array (
                'Informazioni forum',
                'Profilo',
                'Modifica MyUniversiBO',
                'Posta di ateneo',
                'Docenti da contattare',
                'DB Postgresql locale',
        );
        
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('ShowSettings');
        
        $this->assertSentences($sentences);
    }
}
