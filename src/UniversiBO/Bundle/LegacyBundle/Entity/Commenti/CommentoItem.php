<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Commenti;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

/**
 * CommentoItem class
 *
 * Rappresenta un singolo commento su un FileStudente.
 *
 * @package universibo
 * @subpackage Commenti
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @author Fabrizio Pinto
 * @author Davide Bellettini
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class CommentoItem
{
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';
    /**
     * @private
     */
    var $id_commento = 0;
    /**
     * @private
     */
    var $id_file_studente = 0;
    /**
     * @private
     */
    var $id_utente = 0;
    /**
     * @private
     */
    var $commento = '';
    /**
     * @private
     */
    var $voto = -1;

    /**
     * @private
     */
    var $eliminato = self::NOT_ELIMINATO;

    /**
     * Crea un oggetto CommentoItem
     * @param $id_file_studente id di un File Studente
     * @param $id_utente id di un utente, quello che ha fatto il commento
     * @param $commento commento a un File Studente
     * @param $voto proposto per un file studente
     */

    public function __construct($id_commento,$id_file_studente,$id_utente,$commento,$voto,$eliminato)
    {
        $this->id_commento = $id_commento;
        $this->id_file_studente = $id_file_studente;
        $this->id_utente = $id_utente;
        $this->commento = $commento;
        $this->voto = $voto;
        $this->eliminato = $eliminato;
    }

    function getIdCommento()
    {
        return $this->id_commento;
    }

    function isEliminato()
    {
        $flag = false;
        if($this->eliminato == FILE_ELIMINATO) $flag=true;

        return $flag;
    }

    /**
     * Restituisce l'id_file_studente del commento
     */

     function getIdFileStudente()
     {
         return $this->id_file_studente;
     }

     /**
     * Setta l'id_file_studente del commento
     */

     function setIdFileStudente($id_file_studente)
     {
         $this->id_file_studente = $id_file_studente;
     }

     /**
     * Restituisce l'id_utente che ha scritto il commento
     */

     function getIdUtente()
     {
         return $this->id_utente;
     }

     /**
     * Setta l'id_utente che ha scritto il commento
     */

     function setIdUtente($id_utente)
     {
         $this->id_utente = $id_utente;
     }

     /**
     * Restituisce il commento al File Studente
     */

     function getCommento()
     {
         return $this->commento;
     }

     /**
     * Setta il commento al File Studente
     */

     function setCommento($commento)
     {
         $this->commento = $commento;
     }

     /**
     * Restituisce il voto associato al file studente
     */

     function getVoto()
     {
         return $this->voto;
     }

     /**
     * Setta il voto associato al File Studente
     */

     function setVoto($voto)
     {
         $this->voto = $voto;
     }

     /**
      *
      */

     function  selectCommentiItem($id_file)
     {
         $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_commento,id_utente,commento,voto FROM file_studente_commenti WHERE id_file='.$db->quote($id_file).' AND eliminato = '.$db->quote(self::NOT_ELIMINATO).' ORDER BY voto DESC';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $commenti_list = array();

        while ( $res->fetchInto($row) )
        {
            $commenti_list[]= new CommentoItem($row[0],$id_file,$row[1],$row[2],$row[3],self::NOT_ELIMINATO);
        }

        $res->free();


        return $commenti_list;
     }

     /**
      *
      */

     function  selectCommentoItem($id_commento)
     {
         $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_file,id_utente,commento,voto FROM file_studente_commenti WHERE id_commento='.$db->quote($id_commento).' AND eliminato = '.$db->quote(self::NOT_ELIMINATO);
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));


        if($res->fetchInto($row) )
        {
            $commenti= new CommentoItem($id_commento,$row[0],$row[1],$row[2],$row[3],self::NOT_ELIMINATO);
        }
        else return false;

        $res->free();


        return $commenti;
     }

     /**
     * Conta il numero dei commenti presenti per il file
     *
     * @static
     * @param int $id_file identificativo su database del file studente
     * @return numero dei commenti
     */
    function  quantiCommenti($id_file)
    {

         $db = FrontController::getDbConnection('main');

        $query = 'SELECT count(*) FROM file_studente_commenti WHERE id_file = '.$db->quote($id_file).' AND eliminato = '.$db->quote(self::NOT_ELIMINATO).' GROUP BY id_file';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $res->fetchInto($row);
        $res->free();


        return $row[0];

    }

    /**
     * Restituisce il nick dello user
     *
     * @return il nickname
     */

     function getUsername()
     {
        return User::getUsernameFromId($this->id_utente);

//	 	$db = FrontController::getDbConnection('main');
//
//		$query = 'SELECT username FROM utente WHERE id_utente= '.$db->quote($this->id_utente);
//		$res = $db->query($query);
//		if (DB::isError($res))
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
//		$rows = $res->numRows();
//		if( $rows == 0)
//			 Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non esiste un utente con questo id_user','file'=>__FILE__,'line'=>__LINE__));
//		$res->fetchInto($row);
//		$res->free();
//		return $row[0];

     }

    /**
     * Aggiunge un Commento sul DB
     */

     function  insertCommentoItem($id_file_studente,$id_utente,$commento,$voto)
     {
         $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $next_id = $db->nextID('file_studente_commenti_id_commento');
        $this->id_commento=$next_id;
        $return = true;
        $query = 'INSERT INTO file_studente_commenti (id_commento,id_file,id_utente,commento,voto,eliminato) VALUES ('.$next_id.','.$db->quote($id_file_studente).','.$db->quote($id_utente).','.$db->quote($commento).','.$db->quote($voto).','.$db->quote(self::NOT_ELIMINATO).')';
        $res = $db->query($query);
        if (DB :: isError($res))
            {
                $db->rollback();
                Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
                $return = false;
            }
        ignore_user_abort(0);

        return $return;
     }

     /**
     * Modifica un Commento sul DB
     */

     function  updateCommentoItem($id_commento,$commento,$voto)
     {
         $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $return = true;
        $query = 'UPDATE file_studente_commenti SET commento='.$db->quote($commento).', voto= '.$db->quote($voto).' WHERE id_commento='.$db->quote($id_commento);
        $res = $db->query($query);
        if (DB :: isError($res))
            {
                $db->rollback();
                Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
                $return = false;
            }
        ignore_user_abort(0);

        return $return;
     }

     /**
      * Cancella un commento sul DB
      */

      function  deleteCommentoItem($id_commento)
      {
              $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $return = true;
        $query = 'UPDATE file_studente_commenti SET eliminato = '.$db->quote(self::ELIMINATO).'WHERE id_commento='.$db->quote($id_commento);
        $res = $db->query($query);
        if (DB :: isError($res))
            {
                $db->rollback();
                Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
                $return = false;
            }
        ignore_user_abort(0);

        return $return;
      }
      /**
     * Questa funzione verifica se esiste già un commento inserito dall'utente
     *
     * @param $id_file, $id_utente id del file e dell'utente
     * @return un valore booleano
     */
    function  esisteCommento($id_file,$id_utente)
    {
        $flag = false;

        $db = FrontController :: getDbConnection('main');

        $query = 'SELECT id_commento FROM file_studente_commenti WHERE id_file ='.$db->quote($id_file).' AND id_utente = '.$db->quote($id_utente).' AND eliminato = '.$db->quote(self::NOT_ELIMINATO).'GROUP BY id_file,id_utente,id_commento';
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        $res->fetchInto($ris);


        return $ris[0];
    }
}

define('COMMENTO_ELIMINATO', CommentoItem::ELIMINATO);
define('COMMENTO_NOT_ELIMINATO', CommentoItem::NOT_ELIMINATO);
