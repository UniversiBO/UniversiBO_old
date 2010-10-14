<?php
/**
* _UnitTest_Cdl.php
* 
* suite di test per la classe Cdl
*/ 


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'XmlDOMFactory'.PHP_EXTENSION;


/**
 * Test per la classe Cdl
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_ConfigDOM extends PHPUnit_TestCase
{

	var $config;	

	function UserTest($name)
	{
		$this->PHPUnit_TestCase($name);
	}
	
	// called before the test functions will be executed
	function setUp()
	{
//		$config = XmlDOMFactory::getXmlDOM();
//		$config->load('config.xml');
//		$this->config =& $config;
		
	}
	
	// called after the test functions are executed
	function tearDown() {
	}
	
	
	function testRootFolder()
	{
//		$nodeList =& $this->config->getElementsByTag('rootFolder');
//		$nodeZero =& $nodeList->item(0);
//		$primoFiglio =& $nodeZero->firstChild;
//		$this->assertEquals($primoFiglio->nodeValue, '../framework/');
	}
	
}

?>