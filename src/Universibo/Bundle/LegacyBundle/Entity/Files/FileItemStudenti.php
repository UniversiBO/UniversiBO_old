<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
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
    public static function  selectFileItem($id_file)
    {
        $id_files = array ($id_file);
        $files = FileItemStudenti::selectFileItems($id_files);
        if ($files === false)

            return false;
        return $files[0];
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
        $db = FrontController :: getDbConnection('main');

        $query = 'DELETE FROM file_studente_canale WHERE id_canale='.$db->quote($id_canale).' AND id_file='.$db->quote($this->getIdFile());
        //? da testare il funzionamento di =
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        // rimuove l'id del canale dall'elenco completo
//		var_dump($this->elencoIdCanali);
//		die();
//		$this->elencoIdCanali = array_diff($this->elencoIdCanali, array ($id_canale));

        /**
         * @TODO settare eliminata = 'S' quando il file viene tolto dall'ultimo canale
         */
    }

    /**
     * Seleziona l' id_canale per i quali il file è inerente
     *
     * @return array elenco degli id_canale
     */

    public function  getIdCanali()
    {
        if ($this->elencoIdCanali != null)

            return $this->elencoIdCanali;

        $id_file = $this->getIdFile();

        $db = FrontController :: getDbConnection('main');

        $query = 'SELECT id_canale FROM file_studente_canale WHERE id_file='.$db->quote($id_file);
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $res->fetchInto($row);

        $return = array($row[0]);

        return $return;

    }

    public function setIdCanali(array $idCanali)
    {
        $this->elencoIdCanali = $idCanali;
    }

    /**
     * Questa funzione verifica, dato un certo
     * id_file se � un file di tipo studente
     *
     * @param $id_file  id del file da verificare
     * @return $flag	true o false
     */

    public static function  isFileStudenti($id_file)
    {
        $flag = true;

        $db = FrontController :: getDbConnection('main');

        $query = 'SELECT count(id_file) FROM file_studente_canale WHERE id_file='.$db->quote($id_file).' GROUP BY id_file';
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        $res->fetchInto($ris);
        if($ris[0]==0) $flag=false;

        return $flag;
    }

    /**
     * Questa funzione restituisce il voto associato al file studente
     *
     * @param $id_file id del file
     */
     public static function getVoto($id_file)
     {

        $db = FrontController :: getDbConnection('main');

        $query = 'SELECT avg(voto) FROM file_studente_commenti WHERE id_file='.$db->quote($id_file).' AND eliminato = '.$db->quote(CommentoItem::NOT_ELIMINATO).' GROUP BY id_file';
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        $res->fetchInto($ris);

        return $ris[0];
     }

     /**
      * Questa funzione cancella tutti i commenti associati al file studente
      */

      function deleteAllCommenti()
      {
          $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $return = true;
        $query = 'UPDATE file_studente_commenti SET eliminato = '.$db->quote(CommentoItem::ELIMINATO).'WHERE id_file='.$db->quote($this->id_file);
        $res = $db->query($query);
        if (DB :: isError($res)) {
                $db->rollback();
                Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
                $return = false;
            }
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

        $db = & FrontController::getDbConnection('main');
        $query = 'UPDATE file SET eliminato  = '.$db->quote(self::ELIMINATO).' WHERE id_file = '.$db->quote($this->getIdFile());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB :: isError($res)) {
            $db->rollback();
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        return false;
    }

    /**
     * @return DBFileItemStudentiRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.files.file_item_studenti');
        }

        return self::$repository;
    }
}
