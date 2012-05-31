<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptTest extends UniversiboCommand
{
    public function execute()
    {
        $canale = Canale::retrieveCanale(274);
        echo $titolo = $canale->getTitolo(),"\n";
        $tutti = array();
        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_canale FROM canale';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        while ($res->fetchInto($row)) {
            $canale2 = Canale::retrieveCanale($row[0]);
            $titolo2 = $canale2->getTitolo($row[0]);
            //$tutti[] = array ('dist' => levenshtein($titolo, $titolo2), 'titolo' => $titolo2);
            $tutti[] = array ('dist' => similar_text($titolo, $titolo2), 'titolo' => $titolo2);
        }

        usort($tutti, array('ScriptTest','_order'));

        foreach ($tutti as $value) {
            echo $value['dist'],': ',$value['titolo'],"\n";
        }
        //var_dump($tutti);

        die();

        echo php_uname();

        if (substr(php_uname(), 0, 7) == "Windows") {
            die ("Sorry, this script doesn't run on Windows.\n");
        }

        $string = 'we?$%\'rwe2432_we.rw35
        234_34++.ZIP';

        echo ereg_replace('([^a-zA-Z0-9_\.])','_',$string), "\n";

    }

    public function _order($a, $b)
    {

        if ($a['dist'] == $b['dist'])

            return 0;
        return ($a['dist'] < $b['dist']) ? -1 : 1;

    }

}
