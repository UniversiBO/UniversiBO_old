<?php
/*
 * StringTest.php
*
* suite di test per la classe String
*/

require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'User'.PHP_EXTENSION;
require_once('CL/CLInterpreter'.PHP_EXTENSION);

class MyReceiver extends Receiver
{
    public function getFc()
    {
        //$this->_setPhpEnvirorment();

        require_once('FrontController'.PHP_EXTENSION);
        $fc= new FrontController($this);

        $fc->setConfig( $this->configFile );

        return $fc;
    }

}

/**
 * Esempio d'uso di PHPUnit
 * Test per la classe String
 *
 * @package universibo_tests
 * @author Fabrizio Pinto
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_CLInterpreter extends PHPUnit_TestCase
{
    // contains the object handle of the string class
    public $utente;

    // valori del test..
    public $username = 'pippo';
    public $id_utente = 55;
    public $MD5 = '' ;
    public $email = '';
    public $ultimoLogin = 0;
    public $bookmark = array();
    public $ADUsername = '';
    public $groups = 127;

    public $staticgroups = 64;
    public $singolare = true;
    public $cl;


    // constructor of the test suite
    public function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    public function setUp()
    {
        // create a new instance of User.
        $this->utente = new User($this->id_utente, $this->groups, $this->username=NULL, $this->MD5=NULL, $this->email=NULL, $this->ultimo_login=NULL, $this->AD_username=NULL, $this->bookmark=NULL);
        $receiver = new MyReceiver('main', '../config.xml', '../framework', '../universibo');
        //var_dump($receiver->getFc()); die;
        CLInterpreter::init($receiver->getFc(), $this->utente);
    }

    // called after the test functions are executed
    // this function is defined in PHPUnit_TestCase and overwritten
    // here
    public function tearDown()
    {
        // delete your instance
        unset($this->utente);
    }


    // test of the getUsername function
    public function testEvento()
    {
        $this->assertTrue(CLInterpreter::execMe('EVENT nome DEF #SQL[select * from canale where id_canale=1];'));
    }

    public function testDefOp()
    {
        $this->assertTrue(CLInterpreter::execMe('OP(echo,100,1) IN a; OUT tot; DEF #PHP[echo "\n". $a; return;];'));
        $this->assertTrue(CLInterpreter::execMe('OP(tempo,10,1) IN a; OUT tot;  DEF #PHP[ echo time(); return true;];'));
        $this->assertTrue(CLInterpreter::execMe('OP(inspect,700,0) IN o; OUT tot; DEF #PHP[ var_dump($o); die;];'));
        $this->assertTrue(CLInterpreter::execMe('OP(canale,800,0) IN id; OUT canale{id, nome, lastMod}; DEF #SQL[select id_canale, nome_canale, ultima_modifica from canale where id_canale=$id];'));
    }


    public function testEvent()
    {
        $this->assertTrue(CLInterpreter::execMe('STARTS WHEN @fc->getRootPath() > 0;'));
    }

    public function testOmogenousBinaryOps()
    {
        $this->assertTrue(CLInterpreter::execMe('STARTS WHEN 5 > 4 > 3 > 1;
                STARTS WHEN 5 > 4 > 3 > 1 < 2;
                STARTS WHEN 5 + 4 + 3 + 2 + 1 = 15;
                STARTS WHEN 5 * 4 * 3;'));
    }

    public function testEterogenousBinaryOps()
    {
        $this->assertTrue(CLInterpreter::execMe('STARTS WHEN 2 + 3 * 4 ^ 2 * 5 * 1 + 1;
                STARTS WHEN 2 + 3 * 4 ^ (1 + 1)* 5 * 1 + 1;
                STARTS WHEN 2 + 3 * ( ^ (4,1 + 1))* 5 * 1 + 1;'));
    }

    public function testOperatori()
    {
        $this->assertTrue(CLInterpreter::execMe('
                OP(array,800,0) IN args*; OUT array; DEF #PHP[return $args;];
                STARTS WHEN 1201106045 = (canale(1 OUT lastMod;));
                STARTS WHEN (1201106045 = (canale(1 OUT lastMod;)))
                = (
                (array(1201106045)) = (canale(1 OUT canale{lastMod};))
        );'));
    }
}
