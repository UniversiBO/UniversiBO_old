<?php
/*
* StringTest.php
* 
* suite di test per la classe String
*/ 


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'User'.PHP_EXTENSION;


/**
 * Esempio d'uso di PHPUnit
 * Test per la classe String
 *
 * @package universibo_tests
 * @author Fabrizio Pinto
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_User extends PHPUnit_TestCase
{
// contains the object handle of the string class
var $utente;

// valori del test..
var $username = 'pippo';
var $id_utente = 55;
var $MD5 = '' ;
var $email = '';
var $ultimoLogin = 0;
var $bookmark = array();
var $ADUsername = '';
var $groups = 127;

var $staticgroups = 64;
var $singolare = true;



// constructor of the test suite
function UserTest($name) {
$this->PHPUnit_TestCase($name);
}

// called before the test functions will be executed
// this function is defined in PHPUnit_TestCase and overwritten
// here
function setUp() {
// create a new instance of User.
$this->utente = new User($this->id_utente, $this->groups, $this->username=NULL, $this->MD5=NULL, $this->email=NULL, $this->ultimo_login=NULL, $this->AD_username=NULL, $this->bookmark=NULL);
}

// called after the test functions are executed
// this function is defined in PHPUnit_TestCase and overwritten
// here
function tearDown() {
// delete your instance
unset($this->utente); 
}


// test of the getUsername function
function testGetUsername() {
$result = $this->utente->getUsername();
$expected = $this->username;
$this->assertTrue($result == $expected);
}

// test of the getPasswordHash function
function testGetPasswordHash() {
$result = $this->utente->getPasswordHash();
$expected = $this->MD5;
$this->assertTrue($result == $expected);
}

// test of the getMail function
function testGetEmail() {
$result = $this->utente->getEmail();
$expected = $this->email;
$this->assertTrue($result == $expected);
}

// test of the getUltimoLogin function
function testGetUltimoLogin() {
$result = $this->utente->getUltimoLogin();
$expected = $this->ultimoLogin;
$this->assertTrue($result == $expected);
}

// test of the getIdUser function
function testGetIdUser() {
$result = $this->utente->getIdUser();
$expected = $this->id_utente;
$this->assertTrue($result == $expected);
}

// test of the getGroups function
function testGetGroups() {
$result = $this->utente->getGroups();
$expected = $this->groups;
$this->assertTrue($result == $expected);
}

// test of the getADUsername function
function testGetADUsername() {
$result = $this->utente->getADUsername();
$expected = $this->ADUsername;
$this->assertTrue($result == $expected);
}

// test of the isUsernameValid function
function testIsUsernameValid() {
$result = $this->utente->isUsernameValid($this->utente->getUsername());
$expected = $this->utente->username;
$this->assertTrue($result == $expected);
}

// test of the isAdmin function
function testIsAdmin() {
$result = $this->utente->isAdmin();
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isAdmin function - static use
function testIsAdminStatic() {
$result = $this->utente->isAdmin($this->staticgroups);
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isPersonale function
function testIsPersonale() {
$result = $this->utente->isPersonale();
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isPersonale function - static use
function testIsPersonaleStatic() {
$result = $this->utente->isPersonale($this->staticgroups);
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isTutor function
function testIsTutor() {
$result = $this->utente->isTutor();
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isTutor function - static use
function testIsTutorStatic() {
$result = $this->utente->isTutor($this->staticgroups);
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isModeratore function
function testIsCollaboratore() {
$result = $this->utente->isCollaboratore();
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isModeratore function - static use
function testIsCollaboratoreStatic() {
$result = $this->utente->isCollaboratore($this->staticgroups);
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isStudente function
function testIsStudente() {
$result = $this->utente->isStudente();
$expected = true;
$this->assertTrue($result == $expected);
}

// test of the isStudente function - static use
function testIsStudenteStatic() {
$result = $this->utente->isStudente($this->staticgroups);
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isOspite function
function testIsOspite() {
$result = $this->utente->isOspite();
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isOspite function - static use
function testIsOspiteStatic() {
$result = $this->utente->isOspite($this->staticgroups);
$expected = false;
$this->assertTrue($result == $expected);
}

// test of the isPasswordValid function
function testIsPasswordValid() {
$MD5 = md5('pippo') ;
$result = $this->utente->isPasswordValid($this->utente->getPasswordHash());
$expected = $this->utente->MD5;
$this->assertTrue($result == $expected);
}

}

?>