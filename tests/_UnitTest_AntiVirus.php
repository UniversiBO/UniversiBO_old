<?php
/**
* _UnitTest_Antivirus.php
*
* suite di test per l'antivirus (con clamav)
*/


require_once 'PHPUnit'.PHP_EXTENSION;


/**
 * Test per per l'antivirus (con clamav)
 *
 * @package universibo_tests
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2005
 */

class _UnitTest_AntiVirus extends PHPUnit_TestCase
{

    public $cmd = '/usr/local/bin/clamscan';
    public $opts = '--quiet';

    public $fileInfetto = '/home/brain/www/file-universibo/eicar.zip';
    public $fileNonInfetto = '/home/brain/www/file-universibo/229_Elettrica_St_Jacob_9-7.pdf';

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

    public function testInfetto()
    {
        $av = new Clamav($this->cmd, $this->opts);

        $this->assertTrue($av->checkFile($this->fileInfetto) === true);
    }

    public function testNonInfetto()
    {
        $av = new Clamav($this->cmd, $this->opts);

        $this->assertTrue($av->checkFile($this->fileNonInfetto) === false);
    }

}
