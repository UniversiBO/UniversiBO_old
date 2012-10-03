<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Links;
use \DB;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

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
     * @var int
     */
    private $id_link = 0;
    /**
     * @var int
     */
    private $id_canale = 0;
    /**
     * @var int
     */
    private $id_utente = 0;
    /**
     * @var string
     */
    private $uri = '';
    /**
     * @var string
     */
    private $label = '';
    /**
     * @var string
     */
    private $description = '';

    /**
     * @var DBLinkRepository
     */
    private static $repository;

    /**
     * Crea un oggetto link
     *
     * @param  int    $id_link     identificativo del link su database
     * @param  int    $id_canale   identificativo del canale a cui si riferisce il link
     * @param  string $uri         uniform resurce identifier (link)
     * @param  string $label       testo del link (di solito quello tra <a>...</a>)
     * @param  string $description testo descrittivo della risorsa puntata dal link
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
    public function getIdLink()
    {
        return $this->id_link;
    }

    public function setIdLink($id_link)
    {
        $this->id_link = $id_link;
    }

    /**
     * Ritorna l'id_canale
     *
     * @return int
     */
    public function getIdCanale()
    {
        return $this->id_canale;
    }

    /**
     * Ritorna l'id_canale
     *
     * @return int
     */
    public function setIdCanale($id_canale)
    {
        $this->id_canale = $id_canale;
    }

    /**
     * Ritorna l'uniform resurce identifier (link)
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Ritorna l'uniform resurce identifier (link)
     *
     * @return string
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * @param $id_utente int
     */
    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Ritorna il testo descrittivo della risorsa puntata dal link
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Ritorna il testo del link (di solito quello tra <a>...</a>)
     *
     * @return string
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Inserisce su Db le informazioni riguardanti un NUOVO link
     *
     * @return boolean
     */
    public function insertLink()
    {
        return self::getRepository()->insert($this);
    }

    /**
     * Recupera un link
     *
     * @param  int  $id_link id del link
     * @return Link / false
     */
    public static function selectLink($id_link)
    {
        return self::getRepository()->find($id_link);
    }

    /**
     * Recupera un elenco di link dal database
     *
     * @param  array $id_links array elenco di id dei link
     * @return Link  array di Link
     */
    public static function selectLinks($id_links)
    {
        return self::getRepository()->findMany($id_links);
    }

    /**
     * Aggiorna il contenuto su DB riguardante le informazioni del link
     *
     * @return boolean true se avvenua con successo, altrimenti false e throws Error object
     */
    public function updateLink()
    {
        return self::getRepository()->update($this);
    }

    /**
     * Aggiorna il contenuto su DB eliminando un Link
     *
     * @return boolean true se avvenua con successo, altrimenti false e throws Error object
     */
    public function deleteLink()
    {
        return self::getRepository()->delete($this);
    }

    /**
     * Recupera un elenco di link riferiti ad un canale dal database
     *
     * @param  array $id_canale id del canale
     * @return Link  array di Link
     */
    public static function selectCanaleLinks($id_canale)
    {
        return self::getRepository()->findByChannelId($id_canale);
    }

    /**
     * Restituisce il nick dello user
     *
     * @return il nickname
     */

    public function getUsername()
    {
        return self::getRepository()->getUsername($this);
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

    /**
     * @return DBLinkRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.links.link');
        }

        return self::$repository;
    }
}
