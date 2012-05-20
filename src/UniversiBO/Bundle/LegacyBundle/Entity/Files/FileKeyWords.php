<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Files;

use \DB;
use \Error;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
/**
 *
 * FileItem class
 *
 * Rappresenta un singolo file.
 *
 * @package universibo
 * @subpackage Files
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class FileKeyWords{

//Todo: mancano le get&set dell'id_file e id_utente

    /**
     * @private
     */
    var $id_file = 0;

    /**
     * Recupera le parole chiave
     *
     * @return array di string
     */
    function selectFileKeyWords($id_file)
    {
        $db = & FrontController::getDbConnection('main');

        $query = 'SELECT keyword FROM file_keywords WHERE id_file='.$db->quote($id_file);
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $elenco_keywords = array ();

        while ($res->fetchInto($row)) {
            $elenco_keywords[] = $row[0];
        }

        $res->free();

        return $elenco_keywords;
    }


    /**
     * Imposta le parole chiave
     *
     * @param int id del file
     * @param array di string
     */
    function updateFileKeyWords($id_file, $elenco_keywords)
    {
        $old_elenco_keywords = FileKeyWords::selectFileKeyWords($id_file);

        $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $db->autoCommit(false);

        foreach ($elenco_keywords as $value){
            if (!in_array($value, $old_elenco_keywords))
                FileKeyWords::addKeyWord($id_file, $value);
        }

        foreach ($old_elenco_keywords as $value){
            if (!in_array($value,$elenco_keywords))
                FileKeyWords::removeKeyWord($id_file, $value);
        }

        $db->commit();

        $db->autoCommit(true);
        ignore_user_abort(0);
    }

    /**
     * Aggiunge una parola chiave
     *
     * @param int id del file
     * @param string
     */
    function addKeyWord($id_file, $keyword)
    {
        $db = FrontController::getDbConnection('main');
        $query = 'INSERT INTO file_keywords(id_file, keyword) VALUES ('.$db->quote($id_file).' , '.$db->quote($keyword) .');';
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res).$query, 'file' => __FILE__, 'line' => __LINE__));
    }

    /**
     * Aggiunge una parola chiave
     *
     * @param int id del file
     * @param string
     */
    function removeKeyWord($id_file, $keyword)
    {
        $db = & FrontController::getDbConnection('main');
        $query = 'DELETE FROM file_keywords WHERE id_file = '.$db->quote($id_file).' AND keyword = '.$db->quote($keyword);
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
    }

}
