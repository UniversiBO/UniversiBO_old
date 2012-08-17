<?php
/*
* StringTest.php
*
* suite di test per la classe String
*/

/*
* classe PHPUnit
*/
require_once 'PHPUnit.php';

/*
* classe da testare
*/
require_once 'StringEsempioUsoPhpUnit.php';

/**
 * Esempio d'uso di PHPUnit
 * Test per la classe String
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_StringEsempioUsoPhpUnit extends PHPUnit_TestCase
{
// contains the object handle of the string class
var $abc;

// constructor of the test suite
function StringTest($name)
{
$this->PHPUnit_TestCase($name);
}

// called before the test functions will be executed
// this function is defined in PHPUnit_TestCase and overwritten
// here
function setUp()
{
// create a new instance of String with the
// string 'abc'
$this->abc = new String("abc");
}

// called after the test functions are executed
// this function is defined in PHPUnit_TestCase and overwritten
// here
function tearDown()
{
// delete your instance
unset($this->abc);
}

// test the toString function
function testToString()
{
$result = $this->abc->toString('contains %s');
$expected = 'contains abc';
$this->assertTrue($result == $expected);
}

// test the copy function
function testCopy()
{
$abc2 = $this->abc->copy();
$this->assertEquals($abc2, $this->abc);
}

// test the add function
function testAdd()
{
$abc2 = new String('123');
$this->abc->add($abc2);
$result = $this->abc->toString("%s");
$expected = "abc123";
$this->assertTrue($result == $expected);
}
}


$suite  = new PHPUnit_TestSuite('StringTest');
$result = PHPUnit::run($suite);
//echo $result -> toHTML();
echo $result -> toHtmlTable();
