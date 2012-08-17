<?php
/**
* _UnitTest_Facolta.php
*
* suite di test per la classe Facolta
*/

require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'Facolta'.PHP_EXTENSION;

/**
 * Test per la classe Facolta
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_Facolta extends PHPUnit_TestCase
{

    public $facolta;

    public function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    public function setUp()
    {
        $this->facolta = Facolta::selectFacoltaCodice('0021');
    }

    // called after the test functions are executed
    public function tearDown() {}

    public function testSetGetCodice()
    {
        $cod_new = '0022';
        $this->facolta->setCodiceFacolta($cod_new);
        $this->assertEquals($cod_new, $this->facolta->getCodiceFacolta());
    }

    public function testSetGetNome()
    {
        $nome_facolta = 'INGEGNIERIAHAH';
        $this->facolta->setNome($nome_facolta);
        $this->assertEquals($nome_facolta, $this->facolta->getNome());
        $this->assertEquals("FACOLTA' DI \n".$nome_facolta, $this->facolta->getTitolo());
    }

    public function testSetGetUri()
    {
        $new_value = 'http://www.ing.example.com';
        $this->facolta->setUri($new_value);
        $this->assertEquals($new_value, $this->facolta->getUri());
    }

    public function testRetrieveAndUpdate()
    {
        $db =& FrontController::getDbConnection('main');
        $db->autoCommit(false);

        $facolta =& Facolta::selectFacoltaCanale($this->facolta->getIdCanale());

        $new_link = 'http://www.ing.example.com';
        $facolta->setUri($new_link);
        $nome_facolta = 'INGEGNIERIAHAH';
        $facolta->setNome($nome_facolta);
        $cod_new = '0022';
        $facolta->setCodiceFacolta($cod_new);

        $facolta->updateFacolta();

        $facolta2 =& Facolta::selectFacoltaCanale($this->facolta->getIdCanale());

        $this->assertEquals($new_link, $facolta2->getUri());
        $this->assertEquals($nome_facolta, $facolta2->getNome());
        $this->assertEquals($cod_new, $facolta2->getCodiceFacolta());

        $db->rollback();
        $db->autoCommit(true);
    }

    public function testFacoltaElenco()
    {
        $elenco =& Facolta::selectFacoltaElenco();

        foreach ($elenco as $facolta) {
            $value = $facolta->getNome();
            if (isset($value_old))
                $this->assertTrue( strcmp($value_old, $value) < 0 );
            $value_old = $value;
        }

    }

}
