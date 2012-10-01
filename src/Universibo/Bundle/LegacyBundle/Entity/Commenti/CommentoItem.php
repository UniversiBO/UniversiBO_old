<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Commenti;
use \DB;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\CoreBundle\Entity\User;

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
     * @var int
     */
    private $id_commento = 0;
    /**
     * @var int
     */
    private $id_file_studente = 0;
    /**
     * @var int
     */
    private $id_utente = 0;
    /**
     * @var string
     */
    private $commento = '';
    /**
     * @var int
     */
    private $voto = -1;

    /**
     * @var string
     */
    private $eliminato = self::NOT_ELIMINATO;

    /**
     * @var DBCommentoItemRepository
     */
    private static $repository;

    /**
     * Crea un oggetto CommentoItem
     * @param $id_file_studente id di un File Studente
     * @param $id_utente id di un utente, quello che ha fatto il commento
     * @param $commento commento a un File Studente
     * @param $voto proposto per un file studente
     */

    public function __construct($id_commento, $id_file_studente, $id_utente,
            $commento, $voto, $eliminato)
    {
        $this->id_commento = $id_commento;
        $this->id_file_studente = $id_file_studente;
        $this->id_utente = $id_utente;
        $this->commento = $commento;
        $this->voto = $voto;
        $this->eliminato = $eliminato;
    }

    public function getIdCommento()
    {
        return $this->id_commento;
    }

    public function isEliminato()
    {
        return $this->eliminato === self::ELIMINATO;
    }

    /**
     * Restituisce l'id_file_studente del commento
     */

    public function getIdFileStudente()
    {
        return $this->id_file_studente;
    }

    /**
     * Setta l'id_file_studente del commento
     */

    public function setIdFileStudente($id_file_studente)
    {
        $this->id_file_studente = $id_file_studente;
    }

    /**
     * Restituisce l'id_utente che ha scritto il commento
     */

    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * Setta l'id_utente che ha scritto il commento
     */

    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Restituisce il commento al File Studente
     */

    public function getCommento()
    {
        return $this->commento;
    }

    /**
     * Setta il commento al File Studente
     */

    public function setCommento($commento)
    {
        $this->commento = $commento;
    }

    /**
     * Restituisce il voto associato al file studente
     */

    public function getVoto()
    {
        return $this->voto;
    }

    /**
     * Setta il voto associato al File Studente
     */

    public function setVoto($voto)
    {
        $this->voto = $voto;
    }

    /**
     * @deprecated
     */
    public static function selectCommentiItem($id_file)
    {
        return self::getRepository()->findByFileId($id_file);
    }

    /**
     * @deprecated
     */
    public static function selectCommentoItem($id_commento)
    {
        return self::getRepository()->find($id_commento);
    }

    /**
     * Conta il numero dei commenti presenti per il file
     *
     * @deprecated
     * @param  int    $id_file identificativo su database del file studente
     * @return numero dei commenti
     */
    public static function quantiCommenti($id_file)
    {
        return self::getRepository()->countByFile($id_file);
    }

    /**
     * Restituisce il nick dello user
     *
     * @deprecated
     * @return il nickname
     */

    public function getUsername()
    {
        return User::getUsernameFromId($this->id_utente);
    }

    /**
     * Aggiunge un Commento sul DB
     */
    public static function insertCommentoItem($id_file_studente, $id_utente, $commento, $voto)
    {
        ignore_user_abort(1);

        $return = self::getRepository()->insertFromFields($id_file_studente, $id_utente, $commento, $voto);

        ignore_user_abort(0);

        return $return;
    }

    /**
     * Modifica un Commento sul DB
     */

    public static function updateCommentoItem($id_commento, $commento, $voto)
    {
        ignore_user_abort(1);

        $return = self::getRepository()->updateFromFields($id_commento, $commento, $voto);

        ignore_user_abort(0);

        return $return;
    }

    /**
     * Cancella un commento sul DB
     */

    public static function deleteCommentoItem($id_commento)
    {
        ignore_user_abort(1);

        $return = self::getRepository()->deleteById($id_commento);

        ignore_user_abort(0);

        return $return;
    }
    /**
     * Questa funzione verifica se esiste già un commento inserito dall'utente
     *
     * @deprecated
     * @param $id_file, $id_utente id del file e dell'utente
     * @return un valore booleano
     */
    public static function esisteCommento($id_file, $id_utente)
    {
        return self::getRepository()->exists($id_file, $id_utente);

    }

    /**
     * @return DBCommentoItemRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.commenti.commento_item');
        }

        return self::$repository;
    }
}

define('COMMENTO_ELIMINATO', CommentoItem::ELIMINATO);
define('COMMENTO_NOT_ELIMINATO', CommentoItem::NOT_ELIMINATO);
