<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

define('CDL_NUOVO_ORDINAMENTO'   ,1);
define('CDL_SPECIALISTICA'       ,2);
define('CDL_VECCHIO_ORDINAMENTO' ,3);

/**
 * Cdl class.
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

class Cdl extends Canale
{
    /**
     * @var DBCdlRepository
     */
    private static $repository;

    /**
     * @private
     */
    public $cdlCodice = '';
    /**
     * @private
     */
    public $cdlNome = '';
    /**
     * @private
     */
    public $cdlCategoria = 0;
    /**
     * @private
     */
    public $cdlCodiceFacoltaPadre = '';
    /**
     * @private
     */
    public $cdlForumCatId = '';
    /**
     * @private
     */
    public $cdlCodDoc = '';



    /**
     * Crea un oggetto Cdl
     *
     * @param  int     $id_canale       identificativo del canae su database
     * @param  int     $permessi        privilegi di accesso gruppi {@see User}
     * @param  int     $ultima_modifica timestamp
     * @param  int     $tipo_canale     vedi definizione dei tipi sopra
     * @param  string  $immagine        uri               dell'immagine relativo alla cartella del template
     * @param  string  $nome            nome                 del canale
     * @param  int     $visite          numero             visite effettuate sul canale
     * @param  boolean $news_attivo     se              true il servizio notizie ? attivo
     * @param  boolean $files_attivo    se             true il servizio false ? attivo
     * @param  boolean $forum_attivo    se             true il servizio forum ? attivo
     * @param  int     $forum_forum_id  se           forum_attivo ? true indica l'identificativo del forum su database
     * @param  int     $forum_group_id  se           forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
     * @param  boolean $links_attivo    se true il servizio links ? attivo
     * @param  string  $cod_cdl         codice             identificativo d'ateneo del corso di laurea a 4 cifre
     * @param  string  $nome_cdl        descrizione       del nome del cdl
     * @param  int     $categoria_cdl   categoria     del tipo do cdl
     * @param  string  $cod_facolta     codice          identificativo d'ateneo della facolt? a cui appartiene il corso di laurea
     * @param  string  $cod_doc         codice             identificativo del docente
     * @param  string  $forum_cat_id    identificativo categoria del forum
     * @return Facolta
     */
    public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
                 $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo,
                 $cod_cdl, $nome_cdl, $categoria_cdl, $cod_facolta_padre, $cod_doc, $forum_cat_id)
    {

        parent::__construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
                 $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo);

        $this->cdlCodice	= $cod_cdl;
        $this->cdlNome		= $nome_cdl;
        $this->cdlCategoria	= $categoria_cdl;
        $this->cdlCodiceFacoltaPadre	= $cod_facolta_padre;
        $this->cdlForumCatId	= $forum_cat_id;
        $this->cdlCodDoc	= $cod_doc;
    }

    /**
     * Restituisce il nome/descrizione del corso di laurea
     *
     * @return string
     */
    public function getNome()
    {
        return $this->cdlNome;
    }

    /**
     * Imposta il nome/descrizione del corso di laurea
     *
     * @param string $nomeCdl nuovo nome CdL
     */
    public function setNome($nomeCdl)
    {
        $this->cdlNome = $nomeCdl;
    }

    /**
     * Restituisce il titolo/nome completo del cdl
     *
     * @return string
     */
    public function getTitolo()
    {
        return "CORSO DI LAUREA DI \n".$this->getNome();
    }

    /**
     * Restituisce la categoria del cdl
     *
     * define('CDL_NUOVO_ORDINAMENTO'   ,1);
     * define('CDL_SPECIALISTICA'       ,2);
     * define('CDL_VECCHIO_ORDINAMENTO' ,3);
     *
     * @return int
     */
    public function getCategoriaCdl()
    {
        return $this->cdlCategoria;
    }

    /**
     * Imposta la categoria del cdl
     *
     * define('CDL_NUOVO_ORDINAMENTO'   ,1);
     * define('CDL_SPECIALISTICA'       ,2);
     * define('CDL_VECCHIO_ORDINAMENTO' ,3);
     *
     * @param int
     */
    public function setCategoriaCdl($categoria)
    {
        $this->cdlCategoria = $categoria;
    }

    /**
     * Trasforma i codici di tipo corso del dataretriever (forse)
     * a categoria cdl usato in questa classe.
     */
    public function translateCategoriaCdl($categoria)
    {
        $translationTable =
        array(	'LT' => CDL_NUOVO_ORDINAMENTO,
                'LS' => CDL_SPECIALISTICA,
                'L'  => CDL_VECCHIO_ORDINAMENTO);

        if (array_key_exists($categoria, $translationTable))
            $result = $translationTable[$categoria];
        else
            $result = CDL_NUOVO_ORDINAMENTO; //se non so cosa metterci di default butto questo

        return $result;
    }

    /**
     * Ritorna la stringa descrittiva del titolo/nome breve del canale per il MyUniversiBO
     *
     * @return string
     */
    public function getNomeMyUniversiBO()
    {
        return $this->getNome().' - '.$this->getCodiceCdl();
    }

    /**
     * Restituisce il codice della facolta` a cui afferisce il cdl
     *
     * @return string
     */
    public function getCodiceFacoltaPadre()
    {
        return $this->cdlCodiceFacoltaPadre;
    }

    /**
     * Imposta il codice della facolta` a cui afferisce il cdl
     * @todo bisogna estendere a più facolta` perché la relazione è n-n e non 1-n
     *
     * @return string
     */
    public function setCodiceFacoltaPadre($codFac)
    {
        $this->cdlCodiceFacoltaPadre = $codFac;
    }

    /**
     * Restituisce il codice di ateneo a 4 cifre del cdl
     * es: ingegneria informatica -> '0048'
     *
     * @return string
     */
    public function getCodiceCdl()
    {
        return $this->cdlCodice;
    }

    /**
     * Impsta il codice di ateneo a 4 cifre del cdl
     * es: ingegneria informatica -> '0048'
     *
     * @return string
     */
    public function setCodiceCdl($cod_new)
    {
        $this->cdlCodice = $cod_new;
    }

    /**
     * Crea un oggetto Cdl dato il suo numero identificativo id_canale
     * Ridefinisce il factory method della classe padre per restituire un oggetto
     * del tipo Cdl
     *
     * @param  int   $id_canale numero identificativo del canale
     * @return mixed Facolta se eseguita con successo, false se il canale non esiste
     */
    public static function factoryCanale($id_canale)
    {
        return self ::selectCdlCanale($id_canale);
    }

    /**
     * Restituisce l'uri/link che mostra un canale
     *
     * @return string uri/link che mostra un canale
     */
    public function showMe()
    {
        return '/?do=ShowCdl&id_canale='.$this->id_canale;
    }


    /**
     * Seleziona da database e restituisce l'elenco di tutti gli oggetti Cdl corso di laurea
     *
     * @deprecated
     * @param  boolean $canaliAttivi se restituire solo i Cdl gi? associati ad un canale o tutti
     * @return mixed   array di Cdl se eseguita con successo, false in caso di errore
     */
    public static function selectCdlAll()
    {
        return self::getRepository()->findAll();
    }

    /**
     * Seleziona da database e restituisce l'oggetto corso di laurea
     * corrispondente al codice id_canale
     *
     * @deprecated
     * @param  int   $id_canale identificativo su DB del canale corrispondente al corso di laurea
     * @return mixed Cdl se eseguita con successo, false se il canale non esiste
     */
    public static function selectCdlCanale($idCanale)
    {
        return self::getRepository()->findByIdCanale($idCanale);
    }



    /**
     * Seleziona da database e restituisce l'oggetto Cdl
     * corrispondente al codice $cod_cdl
     *
     * @deprecated
     * @param  string  $cod_cdl stringa a 4 cifre del codice d'ateneo del corso di laurea
     * @return Facolta
     */
    public static function selectCdlCodice($codice)
    {
        return self::getRepository()->findByCodice($codice);
    }



    /**
     * Seleziona da database e restituisce un'array contenente l'elenco
     * in ordine alfabetico di tutti i cdl appartenenti alla facolt? data
     *
     * @deprecated
     * @param  string     $cod_facolta stringa a 4 cifre del codice d'ateneo della facolt?
     * @return array(Cdl)
     */
    public static function selectCdlElencoFacolta($codiceFacolta)
    {
        return self::getRepository()->findByFacolta($codiceFacolta);
    }

    /**
     * Restituisce l'uri del command che visulizza il canale
     *
     * @return string URI del command
     */
     public function getShowUri()
     {
         return '/?do=ShowCdl&id_canale='.$this->getIdCanale();
     }

    /**
     * Restituisce il codice docente del presidente del CDL
     *
     * @return string URI del command
     */
    public function getCodDocente()
    {
        return $this->cdlCodDoc;
    }


    /**
     * Imposta il codice docente del presidente del CDL
     *
     * @return string URI del command
     */
    public function setCodDocente($codDoc)
    {
        $this->cdlCodDoc = $codDoc;
    }


    /**
     * Ritorna l'id categoria del forum
     *
     * @return int $cat_id
     */
    public function getForumCatId()
    {
        return $this->cdlForumCatId;
    }

    /**
     * Imposta l'id categoria del forum
     *
     * @param int $cat_id
     */
    public function setForumCatId($cat_id)
    {
        $this->cdlForumCatId = $cat_id;
    }


    public function updateCdl()
    {
        self::getRepository()->update($this);
        // TODO pensare come gestire la cosa
        $this->updateCanale();
    }


    public function insertCdl()
    {
        if ($this->insertCanale() != true) {
            Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore inserimento Canale','file'=>__FILE__,'line'=>__LINE__));

            return false;
        }

        return self::getRepository()->insert($this);
    }

    /**
     * @return DBCdlRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = FrontController::getContainer()->get('universibo_legacy.repository.cdl');
        }

        return self::$repository;
    }
}
