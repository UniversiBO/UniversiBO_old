<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;
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

class FileKeyWords
{
    /**
     * @var DBFileItemRepository
     */
    private static $repository;

    /**
     * Recupera le parole chiave
     *
     * @return array di string
     */
    public static function selectFileKeyWords($id_file)
    {
        return self::getRepository()->getKeyworkds($id_file);
    }

    /**
     * Imposta le parole chiave
     *
     * @param int id del file
     * @param array di string
     */
    public function updateFileKeyWords($id_file, $elenco_keywords)
    {
        return self::getRepository()->updateKeywords($id_file, $elenco_keywords);
    }

    /**
     * Aggiunge una parola chiave
     *
     * @param int id del file
     * @param string
     */
    public function addKeyWord($id_file, $keyword)
    {
        return self::getRepository()->addKeyword($id_file, $keyword);
    }

    /**
     * Aggiunge una parola chiave
     *
     * @param int id del file
     * @param string
     */
    public function removeKeyWord($id_file, $keyword)
    {
        return self::getRepository()->removeKeyword($id_file, $keyword);
    }

    /**
     * @return DBFileItemRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.files.file_item');
        }

        return self::$repository;
    }
}
