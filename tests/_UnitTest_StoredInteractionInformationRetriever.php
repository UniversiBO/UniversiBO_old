<?php
/**
* _UnitTest_ContattoDocente.php
*
* suite di test per la classe ContattoDocente
*/


require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'InteractiveCommand/StoredInteractionInformationRetriever'.PHP_EXTENSION;
require_once 'InteractiveCommand/BaseInteractiveCommand'.PHP_EXTENSION;


/**
 * Test per la classe ContattoDocente
 *
 * @package universibo_tests
 * @author Fabrizio Pinto
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_StoredInteractionInformationRetriever extends PHPUnit_TestCase
{
    var $testValue = array(
                    'call1' => array ('param1' => 'val1',
                                        'param2' => 'arrayval1|arrayval2|arrayval3'
                                        ),
                    'call2' => array ('param1' => 'val1',
                                        'param2' => 'arrayval1|arrayval2|arrayval3'
                                        )
                );
    var $idUtente = '666';

    function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    function setUp()
    {
        $db =& FrontController::getDbConnection('main');
        $db->autoCommit(false);
        $next_id = $db->nextID('step_id_step_seq');
        $query = 'INSERT INTO step_log (id_step, id_utente, data_ultima_interazione, nome_classe, esito_positivo) VALUES '.
                    '( '.$next_id.' , '.
                    $db->quote($this->idUtente).' , '.
                    $db->quote(time()).' , '.
                    $db->quote(get_class($this)).' , '.
                    $db->quote('S').' )';
        $res =& $db->query($query);
        if (DB::isError($res)){
            $db->rollback();
            var_dump($query);
            echo DB::errorMessage($res);
            die;
        }

        foreach ($this->testValue as $callback => $params)
            foreach ($params as $key => $val)
            {
                // VERIFY ha senso come tratto gli eventuali array? o è meglio fare più inserimenti?
                $value = (is_array($val)) ? implode(VALUES_SEPARATOR, $val): $val ;
                $query = 'INSERT INTO step_parametri (id_step, callback_name, param_name, param_value) VALUES '.
                        '( '.$next_id.' , '.
                        $db->quote($callback).' , '.
                        $db->quote($key).' , '.
                        $db->quote($val).' )';
                $res =& $db->query($query);
                //var_dump($query);
                if (DB::isError($res)){
                    $db->rollback();
                    var_dump($query);
                    echo DB::errorMessage($res);
                    die;
                }
            }

    }

    // called after the test functions are executed
    function tearDown() {
        $db =& FrontController::getDbConnection('main');
        $db->rollback();
        $db->autoCommit(true);
    }

    function testgetInfoFromIdUtenteGrouped ()
    {
        $this->assertEquals($this->testValue, StoredInteractionInformationRetriever::getInfoFromIdUtente($this->idUtente, get_class($this), true));
    }

    function testgetInfoFromIdUtenteNotGrouped ()
    {

        $this->assertEquals(array_merge($this->testValue['call1'], $this->testValue['call2'] ), StoredInteractionInformationRetriever::getInfoFromIdUtente($this->idUtente, get_class($this)));
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
