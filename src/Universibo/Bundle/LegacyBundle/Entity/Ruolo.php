<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\CoreBundle\Entity\User;

define('NOTIFICA_NONE'   ,0);
define('NOTIFICA_URGENT' ,1);
define('NOTIFICA_ALL'    ,2);

/**
 * Classe Ruolo, contiene informazioni relative alle propriet? che legano uno User ad un Canale
 *
 * Contiene le informazioni che legano un utente ad un canale,
 * i diritti di accesso (moderatore, referente, ecc...)
 * l'istante dell'ultimo accesso, l'inserimento o meno tra i bookmark/preferiti/my_universibo
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */
class Ruolo
{
    const NONE = 0;
    const MODERATORE = 1;
    const REFERENTE = 2;

    /**
     * @access private
     */
    public $id_utente = 0;

    /**
     * @access private
     */
    public $id_canale = 0;

    /**
     * @access private
     */
    public $user = NULL; //riferimento all'oggetto canale

    /**
     * @access private
     */
    public $canale = NULL;  //riferimento all'oggetto user

    /**
     * @access private
     */
    public $nome = '';

    /**
     * @access private
     */
    public $ultimoAccesso = 0;

    /**
     * @access private
     */
    public $tipoNotifica = '';

    /**
     * @access private
     */
    public $myUniversibo = true;

    /**
     * @access private
     */
    public $moderatore = false;

    /**
     * @access private
     */
    public $referente = false;

    /**
     * @access private
     */
    public $nascosto = false;

    /**
     * Crea un oggetto Ruolo
     *
     * @see selectRuolo
     * @param  int     $id_utente      numero identificativo utente
     * @param  int     $id_canale      numero identificativo canale
     * @param  string  $nome           nome		identificativo del ruolo (stringa personalizzata dall'utente per identificare il canale)
     * @param  int     $ultimo_accesso timestamp dell'ultimo accesso al canale da parete dell'utente
     * @param  boolean $moderatore     true se l'utente possiede diritti di moderatore sul canale
     * @param  boolean $referente      true se l'utente possiede diritti di referente sul canale
     * @param  boolean $my_universibo  true se l'utente ha inserito il canale tra i suoi preferiti
     * @param  boolean $nascosto       se il ruolo ? nascosto o visibile da tutti
     * @param  User    $user           riferimento all'oggetto User
     * @param  Canale  $canale         riferimento all'oggetto Canale
     * @return Ruolo
     */

    public function __construct($id_utente, $id_canale, $nome, $ultimo_accesso, $moderatore, $referente, $my_universibo, $notifica, $nascosto, $user=NULL, $canale=NULL)
    {
        $this->id_utente = $id_utente;
        $this->id_canale = $id_canale;
        $this->user = $user; //riferimento all'oggetto canale
        $this->canale = $canale;  //riferimento all'oggetto user

        $this->ultimoAccesso = $ultimo_accesso;
        $this->tipoNotifica = $notifica;
        $this->nome = $nome;

        $this->myUniversibo = $my_universibo;
        $this->moderatore = $moderatore;
        $this->referente = $referente;

        $this->nascosto = $nascosto;
    }



    /**
     * Ritorna l'ID dello User nel database
     *
     * @return int
     */
    public function getId()
    {
        return $this->id_utente;
    }

    /**
     * Ritorna l'ID del canale nel database
     *
     * @return int
     */
    public function getIdCanale()
    {
        return $this->id_canale;
    }

    /**
     * Restituisce l'oggetto Canale collegato al ruolo
     *
     * @return Canale
     */
    public function getCanale()
    {
        if ($this->canale == NULL) {
            $this->canale = Canale::selectCanale($this->getIdCanale());
        }

        return $this->canale;
    }

    /**
     * Restituisce il nome del canale corrente specificato dal'utente
     *
     * @return int livello di notifica
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Imposta il nome del canale corrente specificato dal'utente
     *
     * @deprecated
     * @param  string  $nome     nome del canale corrente specificato dal'utente
     * @param  boolean $updateDB se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateNome($nome, $updateDB = false)
    {
        $this->setNome($nome);

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Ritorna l'ultimo accesso dell'utente al canale
     *
     * @return int timestamp dell'ultimo accesso
     */
    public function getUltimoAccesso()
    {
        return $this->ultimoAccesso;
    }

    /**
     * Imposta l'ultimo accesso dell'utente al canale
     * @deprecated
     * @param  int     $ultimo_accesso timestamp dell'ultimo accesso
     * @param  boolean $updateDB       se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateUltimoAccesso($ultimoAccesso, $updateDB = false)
    {
        $this->setUltimoAccesso($ultimoAccesso);

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }

    public function setUltimoAccesso($ultimoAccesso)
    {
        $this->ultimoAccesso = $ultimoAccesso;
    }

    /**
     * restituisce il livello di notifica dell'utente nel canale corrente
     * define('RUOLO_NOTIFICA_NONE'   ,0);
     * define('RUOLO_NOTIFICA_URGENT' ,1);
     * define('RUOLO_NOTIFICA_ALL'    ,2);
     *
     * @return int livello di notifica
     */
    public function getTipoNotifica()
    {
        return $this->tipoNotifica;
    }

    /**
     * restituisce il livello di notifica dell'utente nel canale corrente
     * define('NOTIFICA_NONE'   ,0);
     * define('NOTIFICA_URGENT' ,1);
     * define('NOTIFICA_ALL'    ,2);
     *
     * @return int livello di notifica
     */
    public static function getLivelliNotifica()
    {
        return array(	NOTIFICA_NONE => 'Nessuna',
                NOTIFICA_URGENT => 'Solo Urgenti',
                NOTIFICA_ALL => 'Tutti');
    }

    /**
     * Imposta il livello di notifica dell'utente nel canale corrente
     * define('NOTIFICA_NONE'   ,0);
     * define('NOTIFICA_URGENT' ,1);
     * define('NOTIFICA_ALL'    ,2);
     *
     * @deprecated
     * @param  int     $tipo_notifica livello di notifica
     * @param  boolean $updateDB      se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateTipoNotifica($tipoNotifica, $updateDB = false)
    {
        $this->setTipoNotifica($tipoNotifica);

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }

    public function setTipoNotifica($tipoNotifica)
    {
        $this->tipoNotifica = $tipoNotifica;
    }

    /**
     * Verifica se nel ruolo corrente l'utente ? moderatore del cananle
     *
     * @return boolean true se ? moderatore, viceversa false
     */
    public function isModeratore()
    {
        return $this->moderatore;
    }

    /**
     * Imposta i diritti di moderatore nel ruolo
     *
     * @param  boolean $moderatore livello di notifica
     * @param  boolean $updateDB   se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateSetModeratore($moderatore, $updateDB = false)
    {
        $this->setModeratore($moderatore);

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }

    public function setModeratore($moderatore)
    {
        $this->moderatore = $moderatore;
    }

    /**
     * Verifica se nel ruolo corrente l'utente ? referente del canale
     *
     * @return boolean true se ? referente, viceversa false
     */
    public function isReferente()
    {
        return $this->referente;
    }

    /**
     * Verifica se nel ruolo corrente l'utente ? referente del canale
     *
     * @return boolean true se ? referente, viceversa false
     */
    public function isNascosto()
    {
        return $this->nascosto;
    }

    /**
     * Imposta i diritti di referente nel ruolo
     *
     * @param  boolean $referente livello di notifica
     * @param  boolean $updateDB  se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateSetReferente($referente, $updateDB = false)
    {
        $this->setReferente($referente);

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }

    public function setReferente($referente)
    {
        $this->referente = $referente;
    }

    /**
     * Verifica se nel ruolo corrente l'utente ? tra i canali scelti dall'utente
     *
     * @return boolean true se ? referente, viceversa false
     */
    public function isMyUniversibo()
    {
        return $this->myUniversibo;
    }

    /**
     * Aggiunge il canale
     *
     * @param  boolean $referente livello di notifica
     * @param  boolean $updateDB  se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function updateAddMyUniversibo($updateDB = false)
    {
        $this->setMyUniversibo(true, $updateDB);   //non l'ho capita, non ricorda che fa! ma funziona!?
    }

    /**
     * Imposta la selezione preferiti MyUniversibo relativo all'utente (che spiegazione del cavolo)
     *
     * @param  boolean $my_universibo livello di notifica
     * @param  boolean $updateDB      se true la modifica viene propagata al DB
     * @return boolean true se avvenuta con successo
     */
    public function setMyUniversibo($my_universibo, $updateDB = false)
    {
        $this->myUniversibo = $my_universibo;

        if ($updateDB) {
            throw new \InvalidArgumentException('$updateDB must be false');
        }

        return true;
    }
}
