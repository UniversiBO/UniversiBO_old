<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\News;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;

use \DB;
use \Error;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Entity\User;


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


class NewsItem {
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
    var $titolo='';

    /**
     * @private
     */
    var $notizia='';

    /**
     * @private
     */
    var $id_utente=0;

    /**
     * @private
     */
    var $username='';

    /**
     * data e ora di inserimento
     * @private
     */
    var $dataIns=0;


    /**
     * @private
     */
    var $dataScadenza=NULL;


    /**
     * @private
     */
    var $ultimaModifica = NULL;

    /**
     * @private
     */
    var $urgente=false;

    /**
     * @private
     */
    var $id_notizia=0;

    /**
     * @private
     */
    var $eliminata=false;

    /**
     * @private
     */
    var $elencoCanali=NULL;

    /**
     * @private
     */
    var $elencoIdCanali=NULL;



    /**
     * Crea un oggetto NewsItem con i parametri passati
     *
     *
     * @param  int $id_notizia id della news
     * @param  string $titolo titolo della news max 150 caratteri
     * @param  string $notizia corpo della news
     * @param  int $dataIns timestamp del giorno di inserimento
     * @param  int $dataScadenza timestamp del giorno di scadenza
     * @param  int $ultimaModifica timestamp ultima modifica della notizia
     * @param  boolean $urgente flag notizia urgente o meno
     * @param  boolean $eliminata flag stato della news
     * @param  int $id_utente id dell'autore della news
     * @param  string $username username dell'autore della news
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
    function getTitolo()
    {
        return $this->titolo;
    }


    /**
     * Recupera il testo della notizia
     *
     * @return string
     */
    function getNotizia()
    {
        return $this->notizia;
    }


    /**
     * Recupera l'id_utente dell'autore della notizia
     *
     * @return int
     */
    function getIdUtente()
    {
        return $this->id_utente;
    }


    /**
     * Recupera lo username dell'autore della notizia
     *
     * @return string
     */
    function getUsername()
    {
        return $this->username;
    }


    /**
     * Recupera la data di inserimento della notizia
     *
     * @return int
     */
    function getDataIns()
    {
        return $this->dataIns;
    }


     
    /**
     * Recupera la data di scadenza della notizia
     *
     * @return int
     */
    function getDataScadenza()
    {
        return $this->dataScadenza;
    }


    /**
     * Recupera l'urgenza della notizia
     *
     * @return boolean
     */
    function isUrgente()
    {
        return $this->urgente;
    }


    /**
     * Recupera l'id della notizia
     *
     * @return int
     */
    function getIdNotizia()
    {
        return $this->id_notizia;
    }

    /**
     * Recupera lo stato della notizia
     *
     * @return boolean
     */
    function isEliminata()
    {
        return $this->eliminata;
    }


    /**
     * Recupera il timestamp dell'ultima modifica della notizia
     *
     * @return int timestamp dell'ultima modifica della notizia
     */
    function getUltimaModifica()
    {
        return $this->ultimaModifica;
    }


    /**
     * Imposta il titolo della notizia
     *
     * @param  string $titolo titolo della news max 150 caratteri
     */
    function setTitolo($titolo)
    {
        $this->titolo=$titolo;
    }


    /**
     * Imposta il testo della notizia
     *
     * @param  string $notizia corpo della news
     */
    function setNotizia($notizia)
    {
        $this->notizia=$notizia;
    }


    /**
     * Imposta l'id_utente dell'autore della notizia
     *
     * @param  int $id_utente id dell'autore della news
     */
    function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }


    /**
     * Imposta lo username dell'autore della notizia
     *
     * @param  string $username username dell'autore della news
     */
    function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * Imposta la data di inserimento della notizia
     *
     * @param  int $dataIns timestamp del giorno di inserimento
     */
    function setDataIns($dataIns)
    {
        $this->dataIns=$dataIns;
    }


    /**
     *
     * Imposta la data di scadenza della notizia
     *
     * @param  int $dataScadenza timestamp del giorno di scadenza
     */
    function setDataScadenza($dataScadenza) {
        $this->dataScadenza=$dataScadenza;
    }


    /**
     * Imposta l'urgenza della notizia
     *
     * @param  boolean $urgente flag notizia urgente o meno
     */
    function setUrgente($urgente)
    {
        $this->urgente=$urgente;
    }




    /**
     * Imposta il timestamp dell'ultima modifica della notizia
     *
     * @param int timestamp dell'ultima modifica della notizia
     */
    function setUltimaModifica($ultimaModifica)
    {
        $this->ultimaModifica = $ultimaModifica;
    }


    /**
     *
     * Imposta l'id della notizia
     *
     * @param  int $id_notizia id della news
     */
    function setIdNotizia($id_notizia)
    {
        $this->id_notizia=$id_notizia;
    }


    /**
     *
     * Imposta lo stato della notizia
     *
     * @param  boolean $eliminata flag stato della news
     */
    function setEliminata($eliminata)
    {
        $this->eliminata=$eliminata;
    }


    /**
     * Recupera una notizia dal database
     *
     * @static
     * @param int $id_notizia  id della news
     * @return NewsItem
     */
    public static function selectNewsItem ($id_notizia)
    {
        $id_notizie = array($id_notizia);
        $news = NewsItem::selectNewsItems($id_notizie);
        if ($news === false) return false;
        return $news[0];
    }



    /**
     * Recupera un elenco di notizie dal database
     *
     * @static
     * @param array $id_notizie array elenco di id della news
     * @return array NewsItems
     */
    public static function selectNewsItems (array $ids)
    {
        return self::getRepository()->findMany($ids);
    }

    /**
     * Verifica se la notizia ? scaduta
     *
     * @return boolean
     */
    function isScaduta()
    {
        return $this->getDataScadenza() < time();
    }


    /**
     * Seleziona gli id_canale per i quali la notizia ? inerente
     *
     * @static
     * @return array	elenco degli id_canale
     */
    function getIdCanali()
    {
        if ($this->elencoIdCanali != NULL)
            return $this->elencoIdCanali;
         
        $id_notizia = $this->getIdNotizia();
         
        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_canale FROM news_canale WHERE id_news='.$db->quote($id_notizia).' ORDER BY id_canale';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $elenco_id_canale = array();

        while($res->fetchInto($row))
        {
            $elenco_id_canale[] = $row[0];
        }

        $res->free();

        $this->elencoIdCanali = $elenco_id_canale;

        return $this->elencoIdCanali;

    }


    /**
     * rimuove la notizia dal canale specificato
     *
     * @param int $id_canale   identificativo del canale
     */
    function removeCanale($id_canale)
    {
         
        $db = FrontController::getDbConnection('main');

        $query = 'DELETE FROM news_canale WHERE id_canale='.$db->quote($id_canale).' AND id_news='.$db->quote($this->getIdNotizia());
        //? da testare il funzionamento di =
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        // rimuove l'id del canale dall'elenco completo
        $this->elencoIdCanali = array_diff ($this->elencoIdCanali, array($id_canale));

        /**
         * @TODO settare eliminata = 'S' quando la notizia viene tolta dall'ultimo canale
         */
    }

     
    /**
     * aggiunge la notizia al canale specificato
     *
     * @param int $id_canale   identificativo del canale
     * @return boolean  true se esito positivo
     */
    function addCanale($id_canale)
    {
        $return = true;

        if ( !Canale::canaleExists($id_canale) ){
            return false;
            //Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il canale selezionato non esiste','file'=>__FILE__,'line'=>__LINE__));
        }
         
        $db = FrontController::getDbConnection('main');
         
        /*	 	$query = 'SELECT id_notizia FROM news_canale WHERE id_canale = '.$db->quote($id_canale).' AND id_notizia = '.$db->quote($this->getIdNotizia());
         $res = $db->query($query);

        if (DB::isError($res)){
        $return = false;
        Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        if ($res->numRows());
        */
        $query = 'INSERT INTO news_canale (id_news, id_canale) VALUES ('.$db->quote($this->getIdNotizia()).','.$db->quote($id_canale).')';
        //? da testare il funzionamento di =
        $res = $db->query($query);
        if (DB::isError($res)) {
            return false;
            //	$db->rollback();
            //	Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $this->elencoIdCanale[] = $id_canale;

        return true;

    }

    /**
     * Inserisce una notizia sul DB
     *
     * @return	 boolean true se avvenua con successo, altrimenti Error object
     */
    function insertNewsItem()
    {
        $db = FrontController::getDbConnection('main');

        ignore_user_abort(1);
        $db->autoCommit(false);
        $next_id = $db->nextID('news_id_news');
        $return = true;
        $scadenza = ($this->getDataScadenza() == NULL) ? ' NULL ' : $db->quote($this->getDataScadenza());
        $eliminata = ($this->isEliminata()) ? NewsItem::ELIMINATA : self::NOT_ELIMINATA;
        $flag_urgente = ($this->isUrgente()) ? self::URGENTE : self::NOT_URGENTE;
        $query = 'INSERT INTO news (id_news, titolo, data_inserimento, data_scadenza, notizia, id_utente, eliminata, flag_urgente, data_modifica) VALUES '.
                '( '.$next_id.' , '.
                $db->quote($this->getTitolo()).' , '.
                $db->quote($this->getDataIns()).' , '.
                $scadenza.' , '.
                $db->quote($this->getNotizia()).' , '.
                $db->quote($this->getIdUtente()).' , '.
                $db->quote($eliminata).' , '.
                $db->quote($flag_urgente).' , '.
                $db->quote($this->getUltimaModifica()).' )';
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)){
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $this->setIdNotizia($next_id);

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return $return;
    }

    /**
     * Aggiorna le modifiche alla notizia nel DB
     *
     * @return	 boolean true se avvenua con successo, altrimenti Error object
     */
    function updateNewsItem()
    {
        $db = FrontController::getDbConnection('main');

        ignore_user_abort(1);
        $db->autoCommit(false);
        $return = true;
        $scadenza = ($this->getDataScadenza() == NULL) ? ' NULL ' : $db->quote($this->getDataScadenza());
        $flag_urgente = ($this->isUrgente()) ? self::URGENTE : self::NOT_URGENTE;
        $deleted = ($this->isEliminata()) ? NewsItem::ELIMINATA : self::NOT_ELIMINATA;
        $query = 'UPDATE news SET titolo = '.$db->quote($this->getTitolo())
        .' , data_inserimento = '.$db->quote($this->getDataIns())
        .' , data_scadenza = '.$scadenza
        .' , notizia = '.$db->quote($this->getNotizia())
        .' , id_utente = '.$db->quote($this->getIdUtente())
        .' , eliminata = '.$db->quote($deleted)
        .' , flag_urgente = '.$db->quote($flag_urgente)
        .' , data_modifica = '.$db->quote($this->getUltimaModifica())
        .' WHERE id_news = '.$db->quote($this->getIdNotizia());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)){
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return $return;
    }

    /**
     * La funzione deleteNewsItem controlla se la notizia ? stata eliminata da tutti i canali in cui era presente, e aggiorna il db
     */

    function deleteNewsItem()
    {
        $lista_canali = $this->getIdCanali();
        if(count($lista_canali) == 0)
        {
            $this->eliminata = true;
            $this->updateNewsItem();
        }
    }
    
    /**
     * @return DBNewsItemRepository
     */
    private static function getRepository()
    {
    	if(is_null(self::$repository))
    	{
    		self::$repository = new DBNewsItemRepository(FrontController::getDbConnection('main'));
    	}
    	return self::$repository;
    }
}
