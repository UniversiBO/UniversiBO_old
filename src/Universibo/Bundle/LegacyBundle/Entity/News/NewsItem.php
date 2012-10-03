<?php
namespace Universibo\Bundle\LegacyBundle\Entity\News;

/**
 *
 * NewsItem class
 *
 * Rappresenta una singola news.
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class NewsItem
{
    const ELIMINATA = 'S';
    const NOT_ELIMINATA = 'N';
    const URGENTE = 'S';
    const NOT_URGENTE = 'N';

    //	/**
    //	 * ? costante per il valore del flag per le notizie eliminate
    //	 *
    //	 * @private
    //	 */
    //	var $ELIMINATA='S';

    /**
     * @var DBNewsItemRepository
     */
    private static $repository;

    /**
     * @private
     */
    public $titolo='';

    /**
     * @private
     */
    public $notizia='';

    /**
     * @private
     */
    public $id_utente=0;

    /**
     * @private
     */
    public $username='';

    /**
     * data e ora di inserimento
     * @private
     */
    public $dataIns=0;

    /**
     * @private
     */
    public $dataScadenza=NULL;

    /**
     * @private
     */
    public $ultimaModifica = NULL;

    /**
     * @private
     */
    public $urgente=false;

    /**
     * @private
     */
    public $id_notizia=0;

    /**
     * @private
     */
    public $eliminata=false;

    /**
     * @private
     */
    public $elencoCanali=NULL;

    /**
     * @private
     */
    public $elencoIdCanali=NULL;

    /**
     * Crea un oggetto NewsItem con i parametri passati
     *
     *
     * @param  int      $id_notizia     id della news
     * @param  string   $titolo         titolo della news max 150 caratteri
     * @param  string   $notizia        corpo della news
     * @param  int      $dataIns        timestamp del giorno di inserimento
     * @param  int      $dataScadenza   timestamp del giorno di scadenza
     * @param  int      $ultimaModifica timestamp ultima modifica della notizia
     * @param  boolean  $urgente        flag notizia urgente o meno
     * @param  boolean  $eliminata      flag stato della news
     * @param  int      $id_utente      id dell'autore della news
     * @param  string   $username       username dell'autore della news
     * @return NewsItem
     */

    public function __construct($id_notizia, $titolo, $notizia, $dataIns, $dataScadenza, $ultimaModifica, $urgente, $eliminata, $id_utente, $username)
    {
        $this->id_notizia     = $id_notizia;
        $this->titolo         = $titolo;
        $this->notizia        = $notizia;
        $this->dataIns        = $dataIns;
        $this->ultimaModifica = $ultimaModifica;
        $this->dataScadenza   = $dataScadenza;
        $this->urgente        = $urgente;
        $this->eliminata      = $eliminata;
        $this->id_utente      = $id_utente;
        $this->username       = $username;
    }

    /**
     *
     * Recupera il titolo della notizia
     *
     * @return String
     */
    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * Recupera il testo della notizia
     *
     * @return string
     */
    public function getNotizia()
    {
        return $this->notizia;
    }

    /**
     * Recupera l'id_utente dell'autore della notizia
     *
     * @return int
     */
    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * Recupera lo username dell'autore della notizia
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Recupera la data di inserimento della notizia
     *
     * @return int
     */
    public function getDataIns()
    {
        return $this->dataIns;
    }



    /**
     * Recupera la data di scadenza della notizia
     *
     * @return int
     */
    public function getDataScadenza()
    {
        return $this->dataScadenza;
    }


    /**
     * Recupera l'urgenza della notizia
     *
     * @return boolean
     */
    public function isUrgente()
    {
        return $this->urgente;
    }

    /**
     * Recupera l'id della notizia
     *
     * @return int
     */
    public function getIdNotizia()
    {
        return $this->id_notizia;
    }

    /**
     * Recupera lo stato della notizia
     *
     * @return boolean
     */
    public function isEliminata()
    {
        return $this->eliminata;
    }


    /**
     * Recupera il timestamp dell'ultima modifica della notizia
     *
     * @return int timestamp dell'ultima modifica della notizia
     */
    public function getUltimaModifica()
    {
        return $this->ultimaModifica;
    }


    /**
     * Imposta il titolo della notizia
     *
     * @param string $titolo titolo della news max 150 caratteri
     */
    public function setTitolo($titolo)
    {
        $this->titolo=$titolo;
    }


    /**
     * Imposta il testo della notizia
     *
     * @param string $notizia corpo della news
     */
    public function setNotizia($notizia)
    {
        $this->notizia=$notizia;
    }


    /**
     * Imposta l'id_utente dell'autore della notizia
     *
     * @param int $id_utente id dell'autore della news
     */
    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Imposta lo username dell'autore della notizia
     *
     * @param string $username username dell'autore della news
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Imposta la data di inserimento della notizia
     *
     * @param int $dataIns timestamp del giorno di inserimento
     */
    public function setDataIns($dataIns)
    {
        $this->dataIns=$dataIns;
    }

    /**
     *
     * Imposta la data di scadenza della notizia
     *
     * @param int $dataScadenza timestamp del giorno di scadenza
     */
    public function setDataScadenza($dataScadenza)
    {
        $this->dataScadenza=$dataScadenza;
    }

    /**
     * Imposta l'urgenza della notizia
     *
     * @param boolean $urgente flag notizia urgente o meno
     */
    public function setUrgente($urgente)
    {
        $this->urgente=$urgente;
    }




    /**
     * Imposta il timestamp dell'ultima modifica della notizia
     *
     * @param int timestamp dell'ultima modifica della notizia
     */
    public function setUltimaModifica($ultimaModifica)
    {
        $this->ultimaModifica = $ultimaModifica;
    }


    /**
     *
     * Imposta l'id della notizia
     *
     * @param int $id_notizia id della news
     */
    public function setIdNotizia($id_notizia)
    {
        $this->id_notizia=$id_notizia;
    }

    /**
     *
     * Imposta lo stato della notizia
     *
     * @param boolean $eliminata flag stato della news
     */
    public function setEliminata($eliminata)
    {
        $this->eliminata=$eliminata;
    }

    /**
     * Verifica se la notizia ? scaduta
     *
     * @return boolean
     */
    public function isScaduta()
    {
        return $this->getDataScadenza() < time();
    }

    public function setIdCanali(array $elencoIdCanali)
    {
        $this->elencoIdCanali = $elencoIdCanali;
    }
}
