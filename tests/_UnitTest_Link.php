<?php
/**
* _UnitTest_Facolta.php
*
* suite di test per la classe Facolta
*/

require_once 'PHPUnit'.PHP_EXTENSION;
require_once 'Links/Link'.PHP_EXTENSION;

/**
 * Test per la classe Link
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class _UnitTest_Link extends PHPUnit_TestCase
{

    public function UserTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    // called before the test functions will be executed
    public function setUp()
    {
        $db =& FrontController::getDbConnection('main');
        $db->autoCommit(false);
    }

    // called after the test functions are executed
    public function tearDown()
    {
        $db =& FrontController::getDbConnection('main');
        $db->rollback();
        $db->autoCommit(true);
    }

    public function testGetLink()
    {
        $link = new Link(0, 45, 23, 'http://example.com', 'Link di esempio', 'Descrizione blab bla bla');

        $this->assertEquals(0, $link->getIdLink());
        $this->assertEquals(45, $link->getIdCanale());
        $this->assertEquals(23, $link->getIdUtente());
        $this->assertEquals('http://example.com', $link->getUri());
        $this->assertEquals('Link di esempio', $link->getLabel());
        $this->assertEquals('Descrizione blab bla bla', $link->getDescription());
    }

    public function testInsertSelect()
    {
        $link = new Link(0, 45, 23, 'http://example.com', 'Link di esempio', 'Descrizione blab bla bla');

        $link->insertLink();

        $nuovo_id = $link->getIdLink();

        $nuovo_link =& Link::selectLink($nuovo_id);

        $this->assertEquals($nuovo_link->getIdCanale(), $link->getIdCanale());
        $this->assertEquals($nuovo_link->getIdUtente(), $link->getIdUtente());
        $this->assertEquals($nuovo_link->getUri(), $link->getUri());
        $this->assertEquals($nuovo_link->getLabel(), $link->getLabel());
        $this->assertEquals($nuovo_link->getDescription(), $link->getDescription());

    }

    public function testUpdateSelect()
    {
        $link = new Link(0, 45, 23, 'http://example.com', 'Link di esempio', 'Descrizione blab bla bla');

        $link->insertLink();

        $link->setIdCanale(76);
        $link->setIdUtente(12);
        $link->setUri('http://example2.com');
        $link->setLabel('string');
        $link->setDescription('string2');
        $link->updateLink();

        $nuovo_id = $link->getIdLink();

        $nuovo_link =& Link::selectLink($nuovo_id);

        $this->assertEquals($nuovo_link->getIdCanale(), 76);
        $this->assertEquals($nuovo_link->getIdUtente(), 12);
        $this->assertEquals($nuovo_link->getUri(), 'http://example2.com');
        $this->assertEquals($nuovo_link->getLabel(), 'string');
        $this->assertEquals($nuovo_link->getDescription(), 'string2');

    }

    public function testDeleteSelect()
    {
        $link = new Link(0, 45, 23, 'http://example.com', 'Link di esempio', 'Descrizione blab bla bla');

        $link->insertLink();

        $nuovo_id = $link->getIdLink();

        $link->deleteLink();

        $nuovo_link = Link::selectLink($nuovo_id);

        $this->assertEquals($nuovo_link, false);

    }

    public function testSelectCanaleLinks()
    {
        $link = new Link(0, 45, 23, 'http://example.com', 'Link di esempio', 'Descrizione blab bla bla');

        $link->insertLink();
        $link->insertLink();
        $link->insertLink();

        $elenco_link = Link::selectCanaleLinks(45);
        $this->assertEquals(count($elenco_link), 3);

        $nuovo_link = $elenco_link[0];
        $this->assertEquals($nuovo_link->getIdCanale(), $link->getIdCanale());
        $this->assertEquals($nuovo_link->getIdUtente(), $link->getIdUtente());
        $this->assertEquals($nuovo_link->getUri(), $link->getUri());
        $this->assertEquals($nuovo_link->getLabel(), $link->getLabel());
        $this->assertEquals($nuovo_link->getDescription(), $link->getDescription());
    }

}
