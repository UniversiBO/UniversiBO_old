<?php
/**
* _UnitTest_ContattoDocente.php
*
* suite di test per la classe ContattoDocente
*/


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'InteractiveCommand/StepList'.PHP_EXTENSION;


/**
 * Test per la classe ContattoDocente
 *
 * @package universibo_tests
 * @author Fabrizio Pinto
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_StepList extends PHPUnit_TestCase
{
    var $StepList;
    var $call1 = '1';
    var $call2 = '2';

    function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    function setUp()
    {
        $db =& FrontController::getDbConnection('main');
        //$db->autoCommit(false);
        $this->StepList = new StepList(array($this->call1, $this->call2));
    }

    // called after the test functions are executed
    function tearDown() {
        //$db =& FrontController::getDbConnection('main');
        //$db->rollback();
        //$db->autoCommit(true);
    }

    function testIsComplete ()
    {
        for($i=0 ; $i < $this->StepList->getLength(); $i++)
        {
            $item =& $this->StepList->getStep($i);
            if ($item != null)
            {
                $item->completeStep();
            }
        }
        $this->StepList->currentStep = $this->StepList->getLength() - 1;
        $this->StepList->lastGoodStep = $this->StepList->getLength() - 1;
//		var_dump($this);
        $this->assertEquals(true,$this->StepList->isComplete());
    }
//
//	function testContattoDocente()
//	{
//		$contatto = new ContattoDocente(0, 2, 2, 2, 'report');
//
//		$this->assertEquals(0, $contatto->getCodDoc());
//		$this->assertEquals(2, $contatto->getStato());
//		$this->assertEquals(2, $contatto->getIdUtenteAssegnato());
//		$this->assertEquals(2, $contatto->getUltimaModifica());
//		$this->assertEquals('report', $contatto->getReport());
//	}
//
//	function testInserisciContattoDocente()
//	{
////		//come faccio a verificare l'inserisci dato che in esso c'è il commit?
////		$contatto = new ContattoDocente(0, 2, 2, 2, 'report');
////
////		$esito 	= $contatto->insertContattoDocente();
////		$this->assertTrue($esito);
//	}
//
//	function testGetContattoDocente()
//	{
//
//	}
//
}
?>
