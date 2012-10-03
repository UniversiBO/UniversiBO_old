<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Links;

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
     * La funzione verifica se il link è interno o meno
     * @deprecated
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
