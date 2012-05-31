<?php
/**
* _UnitTest_Facolta.php
*
* suite di test per la classe Facolta
*/

require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetrieverFactory'.PHP_EXTENSION;

/**
 * Test per la classe Link
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_ProgrammazioneDidatticaDataRetrieverWebService extends PHPUnit_TestCase
{

    public $data_retriever;

    public function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    public function setUp()
    {
//		$db =& FrontController::getDbConnection('main');
//		$db->autoCommit(false);
        $this->data_retriever = ProgrammazioneDidatticaDataRetrieverFactory::getProgrammazioneDidatticaDataRetriever("web_service");
    }

    // called after the test functions are executed
    public function tearDown()
    {
//		$db =& FrontController::getDbConnection('main');
//		$db->rollback();
//		$db->autoCommit(true);
    }

    public function testGetFactory()
    {
        $data_retriever = ProgrammazioneDidatticaDataRetrieverFactory::getProgrammazioneDidatticaDataRetriever("web_service");
        $this->assertTrue(true);
    }

    public function testGetFacoltaList()
    {
        $numero_facolta = count($this->data_retriever->getFacoltaList());
        $this->assertEquals(68, $numero_facolta);
    }

    public function testFacoltaGetFacolta()
    {
        $facolta  = $this->data_retriever->getFacolta("0021");
//		var_dump($facolta);
//		var_dump($facolta->descFac);
//		var_dump($facolta['descFac']);
        $this->assertEquals($facolta->descFac , "INGEGNERIA");
        $this->assertEquals($facolta->codFac, "0021" );
        $this->assertEquals($facolta->codDocPreside, "000000" );  /** @todo */
    }

    public function testGetCorsoListDatoCodiceFacolta()
    {
        $numero_corsi = count($this->data_retriever->getCorsoListFacolta("0021"));
        $this->assertEquals(187, $numero_corsi);
    }

    public function testGetCorso()
    {
        $corso = $this->data_retriever->getCorso("0051");
        $this->assertEquals($corso->codCorso, "0051" );
        $this->assertEquals($corso->descCorso, "INGEGNERIA INFORMATICA" );
        $this->assertEquals($corso->codDocPresidente, "000000" );   /** @todo */
        $this->assertEquals($corso->tipoCorso, "00" );              /** @todo */
    }

    public function testGetMateria()
    {
        $materia = $this->data_retriever->getMateria("00015");
        $this->assertEquals($materia->codMateria, "00015");
        $this->assertEquals($materia->descMateria, "ANALISI MATEMATICA I");
    }

    public function testGetDocenteDatoCodDoc()
    {
        $docente = $this->data_retriever->getDocente("015549");
        $this->assertEquals($docente->codDoc, "015549");
        $this->assertEquals($docente->nomeDoc, "MACRI' DIEGO MARIA");
        $this->assertEquals($docente->emailDoc, "fake@example.com");
    }

    public function testGetAttivitaDidatticaNonSdoppiataPadreDatoCodCorso()
    {
        $annoAccademico = 2004;
        $arrayAttivita = $this->data_retriever->getAttivitaDidatticaPadreCorso("0046", $annoAccademico);
        $numeroAttivita = count($arrayAttivita);
        $this->assertEquals($numeroAttivita, 9);
    }

    public function testGetSdoppiamentiDataAttivitaDidatticaPadre()
    {
        $attivitaDidatticaPadre = new stdClass;
        $attivitaDidatticaPadre->annoAccademico = 2004;
        $attivitaDidatticaPadre->annoCorso = '4';
        $attivitaDidatticaPadre->annoCorsoIns = '3';
        $attivitaDidatticaPadre->annoCorsoUniversibo = '3';
        $attivitaDidatticaPadre->codAte = '010';
        $attivitaDidatticaPadre->codCorso = '0234';
        $attivitaDidatticaPadre->codDoc = '021421';
        $attivitaDidatticaPadre->codInd = '000';
        $attivitaDidatticaPadre->codMateria = '03221';
        $attivitaDidatticaPadre->codMateriaIns = '02122';
        $attivitaDidatticaPadre->codModulo = '0';
        $attivitaDidatticaPadre->codOri = '000';
        $attivitaDidatticaPadre->codRil = 'A-Z';
        $attivitaDidatticaPadre->flagTitolareModulo = 'S';
        $attivitaDidatticaPadre->sdoppiato = false;
        $attivitaDidatticaPadre->tipoCiclo = '2';

        $arrayAttivita = $this->data_retriever->getSdoppiamentiAttivitaDidattica($attivitaDidatticaPadre);
        $numeroAttivita = count($arrayAttivita);
        $this->assertEquals($numeroAttivita, 1);
    }

    public function testGetAttivitaDidatticaDatoCodCorso()
    {
        $annoAccademico = 2004;
        $arrayAttivita = $this->data_retriever->getAttivitaDidatticaCorso("0046", $annoAccademico);
        $numeroAttivita = count($arrayAttivita);
        $this->assertEquals($numeroAttivita, 1);
    }

}

