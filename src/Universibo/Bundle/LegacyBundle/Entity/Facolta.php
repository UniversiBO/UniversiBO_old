<?php
namespace Universibo\Bundle\LegacyBundle\Entity;
use Symfony\Component\Routing\RouterInterface;

use \DB;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

global $__facoltaElencoCodice;
global $__facoltaElencoAlfabetico;
global $__facoltaElencoCanale;

$facoltaElencoCodice = NULL;
$facoltaElencoAlfabetico = NULL;
$facoltaElencoCanale = NULL;

/**
 * Facolta class.
 *
 * Modella una facolt?.
 * Fornisce metodi statici che permettono l'accesso
 * ottimizzato alle istanze di Facolt?
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Facolta extends Canale
{

    /**
     * @var string
     */
    private $facoltaCodice = '';
    /**
     * @var string
     */
    private $facoltaNome = '';
    /**
     * @var string
     */
    private $facoltaUri = '';

    /**
     * @var DBFacoltaRepository
     */
    private static $repository;

    /**
     * Crea un oggetto facolta
     *
     * @see selectFacoltaCanale
     * @see selectFacoltaCodice
     * @see selectFacoltaElenco
     * @param  int     $id_canale       identificativo del canae su database
     * @param  int     $permessi        privilegi di accesso gruppi {@see User}
     * @param  int     $ultima_modifica timestamp
     * @param  int     $tipo_canale     vedi definizione dei tipi sopra
     * @param  string  $immagine        uri            dell'immagine relativo alla cartella del template
     * @param  string  $nome            nome              del canale
     * @param  int     $visite          numero          visite effettuate sul canale
     * @param  boolean $news_attivo     se           true il servizio notizie ? attivo
     * @param  boolean $files_attivo    se          true il servizio false ? attivo
     * @param  boolean $forum_attivo    se          true il servizio forum ? attivo
     * @param  int     $forum_forum_id  se        forum_attivo ? true indica l'identificativo del forum su database
     * @param  int     $forum_group_id  se        forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
     * @param  boolean $links_attivo    se true il servizio links ? attivo
     * @param  string  $cod_facolta     codice       identificativo d'ateneo della facolt? a 4 cifre
     * @param  string  $nome_facolta    descrizione del nome della facolt?
     * @param  string  $uri_facolta     link         al sito internet ufficiale della facolt?
     * @return Facolta
     */
    public function __construct($id_canale, $permessi, $ultima_modifica,
            $tipo_canale, $immagine, $nome, $visite, $news_attivo,
            $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id,
            $links_attivo, $files_studenti_attivo, $cod_facolta, $nome_facolta,
            $uri_facolta)
    {
        parent::__construct($id_canale, $permessi, $ultima_modifica,
                $tipo_canale, $immagine, $nome, $visite, $news_attivo,
                $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id,
                $links_attivo, $files_studenti_attivo);

        $this->facoltaCodice = $cod_facolta;
        $this->facoltaNome = $nome_facolta;
        $this->facoltaUri = $uri_facolta;
    }

    /**
     * Restituisce il nome della facolt?
     *
     * @return string
     */
    public function getNome()
    {
        return $this->facoltaNome;
    }

    /**
     * Imposta il nome della facolt?
     *
     * @param string 'INGEGNERIA'
     */
    public function setNome($nome_fac)
    {
        $this->facoltaNome = $nome_fac;
    }

    /**
     * Restituisce il titolo/nome completo della facolt?
     *
     * @return string
     */
    public function getTitolo()
    {
        return "FACOLTA' DI \n" . $this->getNome();
    }

    /**
     * Restituisce il link alla homepage ufficiale della facolt?
     *
     * @return string
     */
    public function getUri()
    {
        return $this->facoltaUri;
    }

    /**
     * Imposta il link alla homepage ufficiale della facolt?
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->facoltaUri = $uri;
    }

    /**
     * Restituisce il codice di ateneo a 4 cifre della facolt?
     * es: ingegneria -> '0021'
     *
     * @return string
     */
    public function getCodiceFacolta()
    {
        return $this->facoltaCodice;
    }

    /**
     * Imposta il codice di ateneo a 4 cifre della facolt?
     * @param string $cod_fac es: ingegneria -> '0021'
     */
    public function setCodiceFacolta($cod_fac)
    {
        $this->facoltaCodice = $cod_fac;
    }

    /**
     * Crea un oggetto facolta dato il suo numero identificativo id_canale
     * Ridefinisce il factory method della classe padre per restituire un oggetto
     * del tipo Facolta
     *
     * @param  int   $id_canale numero identificativo del canale
     * @return mixed Facolta se eseguita con successo, false se il canale non esiste
     */
    public static function factoryCanale($id_canale)
    {
        return Facolta::selectFacoltaCanale($id_canale);
    }

    /**
     * Restituisce l'uri/link che mostra un canale
     *
     * @return string uri/link che mostra un canale
     */
    public function showMe(RouterInterface $router)
    {
        if ($router !== null) {
            return $router->generate('universibo_legacy_facolta', array('id_canale' => $this->getIdCanale()));
        }

        return '/?do=ShowFacolta&id_canale=' . $this->id_canale;
    }

    /**
     * Seleziona da database e restituisce l'oggetto facolt?
     * corrispondente al codice id_canale
     *
     * @static
     * @param  int   $id_canale id_del canale corrispondente alla facolt?
     * @return mixed Facolta se eseguita con successo, false se il canale non esiste
     */
    public static function selectFacoltaCanale($id_canale)
    {
        global $__facoltaElencoCanale;

        if ($__facoltaElencoCanale == NULL) {
            Facolta::_selectFacolta();
        }

        if (!array_key_exists($id_canale, $__facoltaElencoCanale))
            return false;

        return $__facoltaElencoCanale[$id_canale];
    }

    /**
     * Seleziona da database e restituisce l'oggetto facolt?
     * corrispondente al codice $cod_facolta
     *
     * @static
     * @param  string  $cod_facolta stringa a 4 cifre del codice d'ateneo della facolt?
     * @return Facolta
     */
    public static function selectFacoltaCodice($cod_facolta)
    {
        global $__facoltaElencoCodice;

        if ($__facoltaElencoCodice == NULL) {
            Facolta::_selectFacolta();
        }

        if (!array_key_exists($cod_facolta, $__facoltaElencoCodice))
            return false;

        return $__facoltaElencoCodice[$cod_facolta];
    }

    /**
     * Seleziona da database e restituisce un'array contenente l'elenco
     * in ordine alfabetico di tutte le facolt?
     *
     * @param  string         $cod_facolta stringa a 4 cifre del codice d'ateneo della facolt?
     * @return array(Facolta)
     */
    public static function selectFacoltaElenco()
    {
        global $__facoltaElencoAlfabetico;

        if ($__facoltaElencoAlfabetico == NULL) {
            self::_selectFacolta();
        }

        return $__facoltaElencoAlfabetico;
    }

    /**
     * @deprecated
     */
    public function updateFacolta()
    {
        $this->getRepository()->update($this);
    }

    /**
     * Siccome nella maggiorparte delle chiamate viene eseguito l'accesso a tutte le
     * facolt? questa procedura si occupa di eseguire il caching degli oggetti facolt?
     * in variabili static (globali per comodit? implementativa) e permette di
     * alleggerire i futuri accessi a DB implementando di fatto insieme ai metodi
     * select*() i meccanismi di un metodo singleton factory
     *
     * @deprecated
     * @return none
     */
    private static function _selectFacolta()
    {

        global $__facoltaElencoCodice;
        global $__facoltaElencoAlfabetico;
        global $__facoltaElencoCanale;

        $__facoltaElencoAlfabetico = array();
        $__facoltaElencoCanale = array();
        $__facoltaElencoCodice = array();

        foreach (self::getRepository()->findAll() as $facolta) {
            $__facoltaElencoAlfabetico[] = $facolta;
            $__facoltaElencoCodice[$facolta->getCodiceFacolta()] = $facolta;
            $__facoltaElencoCanale[$facolta->getIdCanale()] = $facolta;
        }
    }

    /**
     * Inserisce su Db le informazioni riguardanti un NUOVO canale
     *
     * @param  int     $id_canale numero identificativo utente
     * @return boolean
     */
    public function insertFacolta()
    {
        $result = self::getRepository()->insert($this);

        Facolta::_selectFacolta();

        return $result;
    }

    /**
     * Restituisce l'uri del command che visulizza il canale
     *
     * @return string URI del command
     */
    public function getShowUri()
    {
        return '/?do=ShowFacolta&id_canale=' . $this->getIdCanale();
    }

    /**
     * @return DBFacoltaRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.facolta');
        }

        return self::$repository;
    }
}
