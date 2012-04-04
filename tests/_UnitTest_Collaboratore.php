<?php
/**
* _UnitTest_Facolta.php
* 
* suite di test per la classe Facolta
*/ 


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'Collaboratore'.PHP_EXTENSION;


/**
 * Test per la classe Link
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_Collaboratore extends PHPUnit_TestCase
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
	
	
	function testGetCollaboratore()
	{
		$collaboratore = new Collaboratore(0, 'intro intro', '3381407176', 'obiettivi obiettivi', 'nofoto.gif', 'ruolo ruolo');
		
		$this->assertEquals(0, $collaboratore->getIdUtente());
		$this->assertEquals('intro intro', $collaboratore->getIntro());
		$this->assertEquals('3381407176', $collaboratore->getRecapito());
		$this->assertEquals('obiettivi obiettivi', $collaboratore->getObiettivi());
		$this->assertEquals('0_nofoto.gif', $collaboratore->getFotoFilename());
		$this->assertEquals('ruolo ruolo', $collaboratore->getRuolo());
	}


	function testSelectCollaboratore()
	{
		$collaboratore =& Collaboratore::selectCollaboratore(81);
		
		$this->assertEquals(81, $collaboratore->getIdUtente());
		$this->assertEquals('3381407176', $collaboratore->getRecapito());
		$this->assertEquals('81_brain.jpg', $collaboratore->getFotoFilename());
		$this->assertEquals('fondatore - progettista software', $collaboratore->getRuolo());
	}
}
