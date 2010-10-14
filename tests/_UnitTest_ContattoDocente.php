<?php
/**
* _UnitTest_ContattoDocente.php
* 
* suite di test per la classe ContattoDocente
*/ 


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'ContattoDocente'.PHP_EXTENSION;


/**
 * Test per la classe ContattoDocente
 *
 * @package universibo_tests
 * @author Fabrizio Pinto
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_ContattoDocente extends PHPUnit_TestCase
{

	function UserTest($name)
	{
		$this->PHPUnit_TestCase($name);
	}
	
	// called before the test functions will be executed
	function setUp()
	{
		$db =& FrontController::getDbConnection('main');
		$db->autoCommit(false);
	}
	
	// called after the test functions are executed
	function tearDown() {
		$db =& FrontController::getDbConnection('main');
		$db->rollback();
		$db->autoCommit(true);
	}
	
	function testContattoDocente()
	{
		$contatto = new ContattoDocente(0, 2, 2, 2, 'report');
		
		$this->assertEquals(0, $contatto->getCodDoc());
		$this->assertEquals(2, $contatto->getStato());
		$this->assertEquals(2, $contatto->getIdUtenteAssegnato());
		$this->assertEquals(2, $contatto->getUltimaModifica());
		$this->assertEquals('report', $contatto->getReport());
	}
	
	function testInserisciContattoDocente()
	{
//		//come faccio a verificare l'inserisci dato che in esso c'è il commit?
//		$contatto = new ContattoDocente(0, 2, 2, 2, 'report');
//		
//		$esito 	= $contatto->insertContattoDocente();
//		$this->assertTrue($esito);
	}
	
	function testGetContattoDocente()
	{
		
	}
	
}
?>
