<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Universibo\Bundle\LegacyBundle\Framework\Error;

/**
 * Canale class.
 *
 * Un "canale" ? una pagina dinamica con a disposizione il collegamento
 * verso i vari servizi tramite un indentificativo, gestisce i diritti di
 * accesso per i diversi gruppi e diritti particolari 'ruoli' per alcuni utenti,
 * fornisce sistemi di notifica e per assegnare un nome ad un canale
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Canale
{
    const CDEFAULT = 1;
    const HOME = 2;
    const FACOLTA = 3;
    const CDL = 4;
    const INSEGNAMENTO = 5;

    /**
     * @private
     */
    public $id_canale = 0;
    /**
     * @private
     */
    public $permessi = 0;
    /**
     * @private
     */
    public $ultimaModifica = 0;
    /**
     * @private
     */
    public $tipoCanale = 0;
    /**
     * @private
     */
    public $immagine = '';
    /**
     * @private
     */
    private $nome = '';
    /**
     * @private
     */
    public $visite = 0;
    /**
     * @private
     */
    public $servizioNews = false;
    /**
     * @private
     */
    public $servizioFiles = false;
    /**
     * @private
     */
    public $servizioForum = false;
    /**
     * @private
     */
    public $forum = array();
    /**
     * @private
     */
    public $servizioLinks = false;
    /**
     * @private
     */
    public $ruoli = NULL;
    /**
     * @private
     */
    public $servizioFilesStudenti = false;

    /**
     * @var CanaleRepository
     */
    private static $repository;

    /**
     * Crea un oggetto canale
     *
     * $tipo_canale:
     *  define('CANALE_DEFAULT'      ,1);
     *  define('Canale::HOME'         ,2);
     *  define('CANALE_FACOLTA'      ,3);
     *  define('CANALE_CDL'          ,4);
     *  define('CANALE_INSEGNAMENTO' ,5);
     *
     * @see factoryCanale
     * @see selectCanale
     * @param  int     $id_canale       identificativo del canae su database
     * @param  int     $permessi        privilegi di accesso gruppi {@see User}
     * @param  int     $ultima_modifica timestamp
     * @param  int     $tipo_canale     vedi definizione dei tipi sopra
     * @param  string  $immagine        uri     dell'immagine relativo alla cartella del template
     * @param  string  $nome            nome       del canale
     * @param  int     $visite          numero   visite effettuate sul canale
     * @param  boolean $news_attivo     se    true il servizio notizie ? attivo
     * @param  boolean $files_attivo    se   true il servizio false ? attivo
     * @param  boolean $forum_attivo    se   true il servizio forum ? attivo
     * @param  int     $forum_forum_id  se forum_attivo ? true indica l'identificativo del forum su database
     * @param  int     $forum_group_id  se forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
     * @param  boolean $links_attivo    se true il servizio links ? attivo
     * @return Canale
     */
    public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
    $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo)
    {
        $this->id_canale = $id_canale;
        $this->permessi = $permessi;
        $this->ultimaModifica = $ultima_modifica;
        $this->tipoCanale = $tipo_canale;
        $this->immagine = $immagine;
        $this->nome = $nome;
        $this->visite = $visite;
        $this->servizioNews = $news_attivo;
        $this->servizioFiles = $files_attivo;
        $this->servizioForum = $forum_attivo;
        $this->forum['forum_id'] = $forum_forum_id;
        $this->forum['group_id'] = $forum_group_id;
        $this->servizioLinks = $links_attivo;
        $this->servizioFilesStudenti = $files_studenti_attivo;
        $this->bookmark = NULL;
    }



    /**
     * Ritorna l'id_canale che identifica il canale
     *
     * @return int
     */
    public function getIdCanale()
    {
        return $this->id_canale;
    }

    public function setIdCanale($id)
    {
        $this->id_canale = $id;
    }

    /**
     * Ritorna l' OR bit a bit dei gruppi che hanno accesso al canale
     *
     * @return int
     */
    public function getPermessi()
    {
        return $this->permessi;
    }


    public function setPermessi($permessi)
    {
        return $this->permessi = $permessi;
    }


    /**
     * Restituisce true se il gruppo o uno dei gruppi appartenenti a $groups
     * ha il permesso di accesso al canale, altrimenti false
     *
     * @param  int     $groups gruppi di cui si vuole verificare l'accesso
     * @return boolean
     */
    public function isGroupAllowed($groups)
    {
        return (boolean) ((int) $this->permessi & (int) $groups);
    }

    /**
     * Ritorna il tipo di canale dato l'id_canale del database
     *
     * @return int
     */
    public function getTipoCanale()
    {
        return $this->tipoCanale;
    }

    /**
     * Ritorna il timestamp dell'ultima modifica eseguita nel canale
     *
     * @return int
     */
    public function getUltimaModifica()
    {
        return $this->ultimaModifica;
    }

    /**
     * Imposta il timestamp dell'ultima modifica
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_files
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setUltimaModifica($timestamp, $updateDB = false)
    {
        $this->ultimaModifica = $timestamp;

        if ($updateDB) {
            throw new \InvalidParameterException('$updateDB must be false');
        }

        return true;
    }

    /**
     * Ritorna l'URL relativo alla cartella del template dell'immagine di intestazione del canale
     *
     * @return string
     */
    public function getImmagine()
    {
        return $this->immagine;
    }

    /**
     * Ritorna la stringa descrittiva del titolo/nome breve del canale
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    public function getNomeCanale()
    {
        return $this->nome;
    }

    /**
     * Ritorna la stringa descrittiva del titolo/nome breve del canale per il MyUniversiBO
     *
     * @return string
     */
    public function getNomeMyUniversiBO()
    {
        return $this->getNome();
    }

    /**
     * Restituisce se la stringa descrittiva nome ? impostata
     *
     * @return string
     */
    public function isNomeSet()
    {
        return $this->nome!='' && $this->nome!=NULL;
    }



    /**
     * Ritorna la stringa descrittiva del titolo/nome completo del canale
     *
     * @return string
     */
    public function getTitolo()
    {
        return $this->getNome();
    }



    /**
     * Ritorna il numero di visite effettuate nel canale
     *
     * @return int
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Setter for visite
     *
     * @param int $visite
     */
    public function setVisite($visite)
    {
        $this->visite = $visite;
    }

    /**
     * Ritorna l'oggetto News, false se il servizio non ? attivo
     *
     * @todo implementare News
     * @return mixed
     */
    public function getServizioNews()
    {
        return $this->servizioNews;
    }

    /**
     * Imposta il servizio news, true: attivo - false: non attivo
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_files
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setServizioNews($attiva_news, $updateDB = false)
    {
        $this->servizioNews = $attiva_news;
    }

    /**
     * Ritorna l'oggetto Files, false se il servizio non ? attivo
     *
     * @todo implementare Files
     * @return mixed
     */
    public function getServizioFiles()
    {
        return $this->servizioFiles;
    }



    /**
     * Imposta il servizio files, true: attivo - false: non attivo
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_files
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setServizioFiles($attiva_files, $updateDB = false)
    {
        $this->servizioFiles = $attiva_files;
    }



    /**
     * Ritorna l'oggetto Links, false se il servizio non ? attivo
     *
     * @todo implementare Links
     * @return mixed
     */
    public function getServizioLinks()
    {
        return $this->servizioLinks;
    }

    /**
     * Imposta il servizio links, true: attivo - false: non attivo
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_links
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setServizioLinks($attiva_links, $updateDB = false)
    {
        $this->servizioLinks = $attiva_links;
    }

    /**
     * Ritorna true , false se il servizio non ? attivo
     *
     * @todo implementare Files
     * @return boolean
     */
    public function getServizioFilesStudenti()
    {
        return $this->servizioFilesStudenti;
    }

    /**
     * Imposta il servizio Files Studenti, true: attivo - false: non attivo
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_files
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setServizioFilesStudenti($attiva_files_studenti, $updateDB = false)
    {
        $this->servizioFilesStudenti = $attiva_files_studenti;
    }

    /**
     * Ritorna l'oggetto Forum, false se il servizio non ? attivo
     *
     * @todo implementare Forum
     * @return mixed
     */
    public function getServizioForum()
    {
        return $this->servizioForum;
    }



    /**
     * Imposta il servizio forum, true: attivo - false: non attivo
     *
     * @todo implementare propagazione DB
     * @param  boolean $attiva_links
     * @param  boolean $updateDB     se true la modifica viene propagata al DB
     * @return boolean
     */
    public function setServizioForum($attiva_forum, $updateDB = false)
    {
        $this->servizioForum = $attiva_forum;
    }



    /**
     * Ritorna il forum_id delle tabelle di phpbb, , NULL se il forum non ? attivo
     *
     * @return mixed
     */
    public function getForumForumId()
    {
        return $this->forum['forum_id'];
    }



    /**
     * Ritorna il group_id delle tabelle di phpbb, NULL se il forum non ? attivo
     *
     * @return mixed
     */
    public function getForumGroupId()
    {
        return $this->forum['group_id'];
    }



    /**
     * Imposta il forum_id delle tabelle di phpbb, , NULL se il forum non ? attivo
     */
    public function setForumForumId($forum_id)
    {
        $this->forum['forum_id'] = $forum_id;
    }



    /**
     * Imposta il group_id delle tabelle di phpbb, NULL se il forum non ? attivo
     */
    public function setForumGroupId($group_id)
    {
        $this->forum['group_id'] = $group_id;
    }



    /**
     * Inizializza le informazioni del canale di CanaleCommand
     * Esegue il dispatch inizializzndo il corretto sottotipo di 'canale'
     *
     * @private
     * @return string
     */
    public function _dispatchCanale()
    {

        //$tipo_canale =  Canale::getTipoCanaleFromId ( $this->getRequestIdCanale() );

        $this->requestCanale = Canale::selectCanale( $this->getRequestIdCanale() );

        if ( $this->requestCanale === false )
            \Error::throwError(_ERROR_DEFAULT,array('msg'=>'Il canale richiesto non e` presente','file'=>__FILE__,'line'=>__LINE__));

        $canale = $this->getRequestCanale();
        $canale->addVisite();
    }

    /**
     * Restituisce l'uri/link che mostra un canale
     *
     * @return string uri/link che mostra un canale
     */
    public function showMe(RouterInterface $router)
    {
        if ($router !== null) {
            if ($this->getTipoCanale() == Canale::HOME) {
                return $router->generate('universibo_legacy_home');
            } else {
                $params = array('id_canale' => $this->getIdCanale());

                return $router->generate('universibo_legacy_canale', $params);
            }
        }
    }

    /**
     * compara per nome due canali
     */
    public static function compareByName($a, $b)
    {
        $nomea = strtolower($a['nome']);
        $nomeb = strtolower($b['nome']);

        return strnatcasecmp($nomea, $nomeb);
    }
}
