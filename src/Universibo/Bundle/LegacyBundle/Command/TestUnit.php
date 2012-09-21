<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
/**
 * UnitTest command class
 *
 * E' integrata ed utilizza il framework per avere accesso alle funzionalit?
 * del framework stesso necessarie al corretto funzionamento della maggiorparte delle
 * entità da testare che sono ad esso accoppiate.
 *
 * @package universibo
 * @subpackage tests
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class TestUnit extends UniversiboCommand
{
    public function execute()
    {
        if (defined('PATH_SEPARATOR')) {
            $pathDelimiter = PATH_SEPARATOR;
        } else {
            $pathDelimiter = (substr(php_uname(), 0, 7) == "Windows") ? ';'
                    : ':';
        }
        ini_set('include_path',
                '../tests' . $pathDelimiter . ini_get('include_path'));

        $test_name = NULL;
        if (array_key_exists('test_name', $_GET)) {
            $test_name = $_GET['test_name'];
        }

        if (!($dir_handle = opendir('../tests')))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => 'Path directory test non valido',
                            'file' => __FILE__, 'line' => __LINE__));

        //		echo "<html><body>";
        //	    while ( false !== ($file = readdir($dir_handle)) )
        //	    {
        //	    	echo 'let\'s go!'; var_dump($file); die;
        //	        if ( ('_UnitTest_' == substr($file, 0, 10)) && (substr($file, -4)==PHP_EXTENSION) &&
        //	        		($test_name == NULL || $test_name == substr(substr($file, 10), 0, -4) ) )
        //	        {
        //	        	echo '<a href="/?do=TestUnit&amp;test_name='.substr(substr($file, 10), 0, -4).'">'.$file,' - ',substr($file, 10, -4),'</a>';
        //				include ($file);
        //				$suite  = new PHPUnit_TestSuite(substr($file, 0, -4));
        //				$result = PHPUnit::run($suite);
        //				//echo $result -> toHTML();
        //				echo $result -> toHtmlTable();
        //				echo '<br /><br />';
        //	        }
        //	    }
        //
        //	    closedir($dir_handle);
        //		echo "</body></html>";

        require_once 'PHPUnit/GUI/SetupDecorator'. PHP_EXTENSION;
        require_once 'PHPUnit/GUI/HTML'. PHP_EXTENSION;
        $gui = new PHPUnit_GUI_SetupDecorator(new PHPUnit_GUI_HTML());
        $gui->getSuitesFromDir('../tests', '.*_UnitTest_.*');
        $gui->show();

    }
}
