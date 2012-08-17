<?php
/**
* _UnitTest_PrgAttivitaDidattica.php
*
* suite di test per la classe PrgAttivitaDidattica
*/

require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'PrgAttivitaDidattica'.PHP_EXTENSION;

/**
 * Test per la classe PrgAttivitaDidattica
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_PrgAttivitaDidattica extends PHPUnit_TestCase
{

    public $pad;

    public function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    public function setUp()
    {
        $db =& FrontController::getDbConnection('main');
        $db->autoCommit(false);
        $elenco_pad =& PrgAttivitaDidattica::selectPrgAttivitaDidatticaCanale(435);
        $this->pad =& $elenco_pad[0];
    }

    // called after the test functions are executed
    public function tearDown()
    {
        $db =& FrontController::getDbConnection('main');
        $db->rollback();
        $db->autoCommit(true);
    }

    public function testSetGet()
    {
        $value1 = 2005;
        $this->pad->setAnnoAccademico($value1);
        $this->assertEquals($value1, $this->pad->getAnnoAccademico());

        $value2 = 0049;
        $this->pad->setCodiceCdl($value2);
        $this->assertEquals($value2, $this->pad->getCodiceCdl());

        $value3 = 000;
        $this->pad->setCodInd($value3);
        $this->assertEquals($value3, $this->pad->getCodInd());

        $value4 = 000;
        $this->pad->setCodOri($value4);
        $this->assertEquals($value4, $this->pad->getCodOri($value4));

        $value5 = 35049;
        $this->pad->setCodMateria($value5);
        $this->assertEquals($value5, $this->pad->getCodMateria());

        $value6 = 'STUDI DI FABBRICAZIONE';
        $this->pad->setNomeMateria($value6);
        $this->assertEquals($value6,$this->pad->getNomeMateria());

        $value7 = 3;
        $this->pad->setAnnoCorso($value7);
        $this->assertEquals($value7, $this->pad->getAnnoCorso());

        $value8 = 35049;
        $this->pad->setCodMateriaIns($value8);
        $this->assertEquals($value8, $this->pad->getCodMateriaIns());

        $value9 = 'STUDI DI FABBRICAZIONE';
        $this->pad->setNomeMateriaIns($value9);
        $this->assertEquals($value9, $this->pad->getNomeMateriaIns());

        $value10= 2;
        $this->pad->setAnnoCorsoIns($value10);
        $this->assertEquals($value10, $this->pad->getAnnoCorsoIns());

        $value11= 'A-T';
        $this->pad->setCodRil($value11);
        $this->assertEquals($value11, $this->pad->getCodRil());

        $value12= 2;
        $this->pad->setCodModulo($value12);
        $this->assertEquals($value12, $this->pad->getCodModulo());

        $value13= '013160';
        $this->pad->setCodDoc($value13);
        $this->assertEquals($value13, $this->pad->getCodDoc());

        $value14= 'ESORCICCIO';
        $this->pad->setNomeDoc($value14);
        $this->assertEquals($value14, $this->pad->getNomeDoc());

        $value15= true;
        $this->pad->setTitolareModulo($value15);
        $this->assertEquals($value15, $this->pad->isTitolareModulo());

        $value16= 2;
        $this->pad->setTipoCiclo($value16);
        $this->assertEquals($value16, $this->pad->getTipoCiclo());

        $value17= '010';
        $this->pad->setCodAte($value17);
        $this->assertEquals($value17, $this->pad->getCodAte());

        $value18= 3;
        $this->pad->setAnnoCorsoUniversibo($value18);
        $this->assertEquals($value18, $this->pad->getAnnoCorsoUniversibo());

//		$value20= '0021';
//		$this->pad->setCodiceFacolta($value20);
//		$this->assertEquals($value20, $this->pad->getCodiceFacolta());

    }

    public function testRetrieveKey()
    {

        $new_pad =& PrgAttivitaDidattica::selectPrgAttivitaDidattica($this->pad->getAnnoAccademico(), $this->pad->getCodiceCdl(), $this->pad->getCodInd(),
                    $this->pad->getCodOri(), $this->pad->getCodMateria(), $this->pad->getCodMateriaIns(), $this->pad->getAnnoCorso(),
                    $this->pad->getAnnoCorsoIns(), $this->pad->getCodRil(), $this->pad->getCodAte());

        $this->assertTrue(count($new_pad) > 0);

        $pad2 =& $new_pad[0];
        $this->assertTrue($pad2->isSdoppiato() == false);

//		$value1 = 2005;
//		$pad2->setAnnoAccademico($value1);
//
//		$value2 = 0049;
//		$pad2->getCodiceCdl();
//
//		$value3 = 000;
//		$pad2->getCodInd();
//
//		$value4 = 000;
//		$pad2->getCodOri();
//
//		$value5 = 35049;
//		$pad2->getCodMateria();
//
//		$value6 = 'STUDI DI FABBRICAZIONE';
//		$pad2->getNomeMateria();
//
//		$value7 = 3;
//		$pad2->getAnnoCorso();
//
//		$value8 = 35049;
//		$pad2->getCodMateriaIns();
//
//		$value9 = 'STUDI DI FABBRICAZIONE';
//		$pad2->getNomeMateriaIns();
//
//		$value10= 2;
//		$pad2->getAnnoCorsoIns();
//
//		$value11= 'A-T';
//		$pad2->getCodRil();
//
//		$value12= 2;
//		$pad2->getCodModulo();
//
//		$value13= '013160';
//		$pad2->getCodDoc();
//
//		$value14= 'ESORCICCIO';
//		$pad2->getNomeDoc();
//
//		$value15= true;
//		$pad2->isTitolareModulo();
//
//		$value16= 2;
//		$pad2->getTipoCiclo();
//
//		$value17= '010';
//		$pad2->getCodAte();
//
//		$value18= 3;
//		$pad2->getAnnoCorsoUniversibo();
//
//		$value20= '0021';
//		$pad2->getCodiceFacolta();
//
//		$pad2->updatePrgAttivitaDidattica();

//		$pad2 =& PrgAttivitaDidattica::selectPrgAttivitaDidatticaCanale($this->cdl->getIdCanale());
//
//		$this->assertTrue($value1, $this->pad2->getAnnoAccademico());
//		$this->assertTrue($value2, $this->pad2->setCodiceCdl($value2));
//		$this->assertTrue($value3, $this->pad2->setCodInd($value3));
//		$this->assertTrue($value4, $this->pad2->setCodOri($value4));
//		$this->assertTrue($value5, $this->pad2->setCodMateria($value5));
//		$this->assertTrue($value6, $this->pad2->setNomeMateria($value6));
//		$this->assertTrue($value7, $this->pad2->setAnnoCorso($value7));
//		$this->assertTrue($value8, $this->pad2->setCodMateriaIns($value8));
//		$this->assertTrue($value9, $this->pad2->setNomeMateriaIns($value9));
//		$this->assertTrue($value10, $this->pad2->setAnnoCorsoIns($value10));
//		$this->assertTrue($value11, $this->pad2->setCodRil($value11));
//		$this->assertTrue($value12, $this->pad2->setCodModulo($value12));
//		$this->assertTrue($value13, $this->pad2->setCodDoc($value13));
//		$this->assertTrue($value14, $this->pad2->setNomeDoc($value14));
//		$this->assertTrue($value15, $this->pad2->setTitolareModulo($value15));
//		$this->assertTrue($value16, $this->pad2->setTipoCiclo($value16));
//		$this->assertTrue($value17, $this->pad2->setCodAte($value17));
//		$this->assertTrue($value18, $this->pad2->setAnnoCorsoUniversibo($value18));
//		$this->assertTrue($value20, $this->pad2->setCodiceFacolta($value20));
//
    }

    public function testPrgAttivitaDidatticaElenco()
    {
//		$cod_facolta = '0021';
//		$elenco =& PrgAttivitaDidattica::selectPrgAttivitaDidatticaElencoFacolta($cod_facolta);
//
//		foreach ( $elenco as $cdl)
//		{
//			$value = $cdl->getCategoriaPrgAttivitaDidattica();
//			if (isset($value_old))
//				$this->assertTrue( $value >= $value_old );
//			$value_old = $value;
//		}

    }

}
