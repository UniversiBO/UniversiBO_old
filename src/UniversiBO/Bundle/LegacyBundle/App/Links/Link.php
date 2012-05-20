<?php
namespace UniversiBO\Bundle\LegacyBundle\App\Links;
use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Link class
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <i.bartolini@reply.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 */
class Link
{

    /**
     * @private
     */
    var $id_link = 0;
    /**
     * @private
     */
    var $id_canale = 0;
    /**
     * @private
     */
    var $id_utente = 0;
    /**
     * @private
     */
    var $uri = '';
    /**
     * @private
     */
    var $label = '';
    /**
     * @private
     */
    var $description = '';

    /**
     * Crea un oggetto link
     *
     * @param int    $id_link     identificativo del link su database
     * @param int    $id_canale   identificativo del canale a cui si riferisce il link
     * @param string $uri         uniform resurce identifier (link)
     * @param string $label       testo del link (di solito quello tra <a>...</a>)
     * @param string $description testo descrittivo della risorsa puntata dal link
     * @return Link
     */
    public function __construct($id_link, $id_canale, $id_utente, $uri, $label,
            $description)
    {
        $this->id_link = $id_link;
        $this->id_canale = $id_canale;
        $this->id_utente = $id_utente;
        $this->uri = $uri;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * Ritorna l'id_link
     *
     * @return int
     */
    function getIdLink()
    {
        return $this->id_link;
    }

    /**
     * Ritorna l'id_canale
     *
     * @return int
     */
    function getIdCanale()
    {
        return $this->id_canale;
    }

    /**
     * Ritorna l'id_canale
     *
     * @return int
     */
    function setIdCanale($id_canale)
    {
        $this->id_canale = $id_canale;
    }

    /**
     * Ritorna l'uniform resurce identifier (link)
     *
     * @return string
     */
    function getUri()
    {
        return $this->uri;
    }

    /**
     * Ritorna l'uniform resurce identifier (link)
     *
     * @return string
     */
    function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * @param $id_utente int
     */
    function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    function getLabel()
    {
        return $this->label;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Ritorna il testo descrittivo della risorsa puntata dal link
     *
     * @return string
     */
    function getDescription()
    {
        return $this->description;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Inserisce su Db le informazioni riguardanti un NUOVO link
     *
     * @return boolean
     */
    function insertLink()
    {
        $db = FrontController::getDbConnection('main');

        $this->id_link = $db->nextID('link_id_link');

        $query = 'INSERT INTO link (id_link, id_canale, id_utente, uri, label, description) VALUES ('
                . $this->getIdLink() . ' , ' . $this->getIdCanale() . ' , '
                . $this->getIdUtente() . ' , ' . $db->quote($this->getUri())
                . ' , ' . $db->quote($this->getLabel()) . ' , '
                . $db->quote($this->getDescription()) . ' )';

        //echo $query;
        $res = $db->query($query);
        if (DB::isError($res)) {
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

            return false;
        }

        return true;
    }

    /**
     * Recupera un link
     *
     * @static
     * @param int $id_link id del link
     * @return Link / false
     */
    function selectLink($id_link)
    {
        $id_links = array($id_link);
        $links = Link::selectLinks($id_links);
        if ($links === false) {
            $ret = false;

            return $ret;
        }

        return $links[0];
    }

    /**
     * Recupera un elenco di link dal database
     *
     * @static
     * @param array $id_links array elenco di id dei link
     * @return Link array di Link
     */
    function selectLinks($id_links)
    {

        $db = FrontController::getDbConnection('main');

        if (count($id_links) == 0) {
            $ret = array();

            return $ret;
        }

        //esegue $db->quote() su ogni elemento dell'array
        //array_walk($id_notizie, array($db, 'quote'));
        $values = implode(',', $id_links);
        $query = 'SELECT id_link, id_canale, uri, label, description, id_utente FROM link WHERE id_link IN ('
                . $values . ')';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows == 0) {
            $ret = false;

            return $ret;
        }
        $link_list = array();

        while ($res->fetchInto($row)) {
            $link_list[] = new Link($row[0], $row[1], $row[5], $row[2],
                    $row[3], $row[4]);
        }

        $res->free();

        return $link_list;
    }

    /**
     * Aggiorna il contenuto su DB riguardante le informazioni del link
     *
     * @return boolean true se avvenua con successo, altrimenti false e throws Error object
     */
    function updateLink()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'UPDATE link SET uri = ' . $db->quote($this->getUri())
                . ' , label = ' . $db->quote($this->getLabel())
                . ' , id_canale = ' . $this->getIdCanale() . ' , id_utente = '
                . $this->getIdUtente() . ' , description = '
                . $db->quote($this->getDescription()) . ' WHERE id_link = '
                . $this->getIdLink();

        //echo $query;
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $rows = $db->affectedRows();

        if ($rows == 1)

            return true;
        elseif ($rows == 0)

            return false;
        else
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => 'Errore generale database: canale non unico',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    /**
     * Aggiorna il contenuto su DB eliminando un Link
     *
     * @return boolean true se avvenua con successo, altrimenti false e throws Error object
     */
    function deleteLink()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'DELETE FROM link WHERE id_link= '
                . $db->quote($this->getIdLink());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $rows = $db->affectedRows();

        if ($rows == 1)

            return true;
        elseif ($rows == 0)

            return false;
        else
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => 'Errore generale database: canale non unico',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    /**
     * Recupera un elenco di link riferiti ad un canale dal database
     *
     * @static
     * @param array $id_canale id del canale
     * @return Link array di Link
     */
    function selectCanaleLinks($id_canale)
    {

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_link, id_canale, id_utente, uri, label, description FROM link WHERE id_canale = ('
                . $db->quote($id_canale) . ') ORDER BY id_link DESC';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows = 0)

            return false;
        $link_list = array();

        while ($res->fetchInto($row)) {
            $link_list[] = new Link($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5]);
        }

        $res->free();

        return $link_list;
    }

    /**
     * Restituisce il nick dello user
     *
     * @return il nickname
     */

    function getUsername()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'SELECT username FROM utente WHERE id_utente= '
                . $db->quote($this->id_utente);
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $rows = $res->numRows();
        if ($rows == 0)
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => 'Non esiste un utente con questo id_user',
                            'file' => __FILE__, 'line' => __LINE__));
        $res->fetchInto($row);
        $res->free();

        return $row[0];

    }

    /**
     * La funzione verifica se il link è interno o meno
     * @return boolean
     */
    public function isInternalLink()
    {
        $request_protocol = (array_key_exists('HTTPS', $_SERVER)
                && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
        $uri = $request_protocol . '://' . $_SERVER['HTTP_HOST'];
        //	  	var_dump($uri);

        return preg_match('/^' . str_replace('/', '\\/', $uri) . '.*$/',
                $this->getUri());
    }
}
