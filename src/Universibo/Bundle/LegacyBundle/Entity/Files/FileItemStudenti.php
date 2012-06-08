<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * FileItemStudenti class
 *
 * Rappresenta un singolo file degli studenti.
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

//define('COMMENTO_ELIMINATO', 'S');
//define('COMMENTO_NOT_ELIMINATO', 'N');

class FileItemStudenti extends FileItem
{
    /**
     * @var DBFileItemStudentyRepository
     */
    private static $repository;

    /**
     * Recupera un file dal database
     *
     * @static
     * @param  int      $id_file id del file
     * @return FileItem
     */
    public static function selectFileItem($id_file)
    {
        return self::getRepository()->find($id_file);
    }

    /**
     * Recupera un elenco di file dal database
     * non ritorna i files eliminati
     *
     * @deprecated
     * @param  array $id_file array elenco di id dei file
     * @return array FileItem
     */
    public static function selectFileItems($id_files)
    {
        return self::getRepository()->findMany($id_files);
    }

    /**
     * aggiunge il file al canale specificato
     *
     * @param  int     $id_canale identificativo del canale
     * @return boolean true se esito positivo
     */
    public function addCanale($id_canale)
    {
        return self::getRepository()->addToChannel($this, $id_canale);
    }

    /**
     * rimuove il file dal canale specificato
     *
     * @param int $id_canale identificativo del canale
     */
    public function removeCanale($id_canale)
    {
        return self::getRepository()->removeFromChannel($this, $id_canale);
    }

    /**
     * Questa funzione verifica, dato un certo
     * id_file se e` un file di tipo studente
     *
     * @param $id_file  id del file da verificare
     * @return $flag	true o false
     */

    public static function isFileStudenti($id_file)
    {
        return self::getRepository()->isFileStudenti($id_file);
    }

    /**
     * Questa funzione restituisce il voto associato al file studente
     *
     * @param $id_file id del file
     */
    public static function getVoto($id_file)
    {
        return self::getRepository()->getAverageRating($id_file);
    }

    /**
     * Questa funzione cancella tutti i commenti associati al file studente
     */
    public function deleteAllCommenti()
    {
        ignore_user_abort(1);
        $return = self::getRepository()->deleteAllComments($this);
        ignore_user_abort(0);

        return $return;
    }

    /**
     * Elimina il file studente
     *
     * @return boolean true se avvenua con successo, altrimenti false
     */
    public function deleteFileItem()
    {
        return self::getRepository()->delete($this);
    }

    /**
     * @return DBFileItemStudentiRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()
                    ->get(
                            'universibo_legacy.repository.files.file_item_studenti');
        }

        return self::$repository;
    }
}
