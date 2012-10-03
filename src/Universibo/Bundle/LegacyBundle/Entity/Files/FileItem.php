<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

/**
 * FileItem class
 *
 * Rappresenta un singolo file.
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class FileItem
{
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';

    //Todo: mancano le get&set dell'id_file e id_utente

    /**
     * @private
     */
    private $id_file = 0;

    /**
     * @private
     */
    private $permessi_download = 0;

    /**
     * @private
     */
    private $permessi_visualizza = 0;

    /**
     * @private
     */
    private $id_utente = 0;

    /**
     * @private
     */
    private $titolo = '';

    /**
     * @private
     */
    private $descrizione = '';

    /**
     * @private
     */
    private $data_inserimento = 0;

    /**
     * @private
     */
    private $data_modifica = 0;

    /**
     * @private
     */
    private $dimensione = 0;

    /**
     * @private
     */
    private $download = 0;

    /**
     * @private
     */
    private $nome_file = '';

    /**
     * @private
     */
    private $id_categoria = 0;

    /**
     * @private
     */
    private $id_tipo_file = 0;

    /**
     * @private
     */
    private $hash_file = '';

    /**
     * @private
     */
    private $password = '';

    /**
     * @private
     */
    private $username = '';

    /**
     * @private
     */
    //	var $eliminato = false

    /**
     * @private
     */
    private $categoria_desc = '';

    /**
     * @private
     */
    private $tipo_desc = '';

    /**
     * @private
     */
    private $tipo_icona = '';

    /**
     * @private
     */
    private $tipo_info = '';

    ///////////////////////////////////////////

    /**
     * @private
     */
    private $elencoIdCanali = NULL;

    /**
     * @private
     */
    private $elencoCanali = NULL;

    /**
     * @private
     */
    private $paroleChiave = NULL;

    /**
     * @var DBFileItemRepository
     */
    private static $repository;

    /**
     * Crea un oggetto FileItem con i parametri passati
     *
     *
     * @param  int      $id_file           id del file
     * @param  string   $titolo            titolo del file
     * @param  string   $descrizione       descrizione completa del file
     * @param  int      $dimensione        dimensione in kb del file
     * @param  int      $permessi_download categoria utenti ai quali ? permesso il download
     * @param  boolean  $eliminato         flag stato del file
     * @return FileItem
     */

    public function __construct($id_file, $permessi_download,
            $permessi_visualizza, $id_utente, $titolo, $descrizione,
            $data_inserimento, $data_modifica, $dimensione, $download,
            $nome_file, $id_categoria, $id_tipo_file, $hash_file, $password,
            $username, $categoria_desc, $tipo_desc, $tipo_icona, $tipo_info /*, $eliminato*/)
    {
        $this->id_file = $id_file;
        $this->permessi_download = $permessi_download;
        $this->permessi_visualizza = $permessi_visualizza;
        $this->id_utente = $id_utente;
        $this->titolo = $titolo;
        $this->descrizione = $descrizione;
        $this->data_inserimento = $data_inserimento;
        $this->data_modifica = $data_modifica;
        $this->dimensione = $dimensione;
        $this->download = $download;
        $this->nome_file = $nome_file;
        $this->id_categoria = $id_categoria;
        $this->id_tipo_file = $id_tipo_file;
        $this->hash_file = $hash_file;
        $this->password = $password;
        $this->username = $username;
        $this->categoria_desc = $categoria_desc;
        $this->tipo_desc = $tipo_desc;
        $this->tipo_icona = $tipo_icona;
        $this->tipo_info = $tipo_info;
        // $this->eliminato = $eliminato;
    }

    /**
     * @todo finire tutti i set/get
     */

    /**
     * Recupera il titolo del file
     *
     * @return string
     */

    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * Recupera la descrizione completa del file
     *
     * @return string
     */

    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * Recupera la dimensione del file
     *
     * @return int
     */

    public function getDimensione()
    {
        return $this->dimensione;
    }

    /**
     * Recupera l'id della categoria di utenti ai quali e permesso il download
     *
     * @return int
     */
    public function getPermessiDownload()
    {
        return $this->permessi_download;
    }

    /**
     * Recupera l'id delle categorie di utenti ai quali e permesso la visualizzazione
     *
     * @return int
     */
    public function getPermessiVisualizza()
    {
        return $this->permessi_visualizza;
    }

    /**
     * Recupera la data di caricamento del file (timestamp)
     *
     * @return int
     */

    public function getDataInserimento()
    {
        return $this->data_inserimento;
    }

    /**
     * Recupera la data di ultima modifica del file (timestamp)
     *
     * @return int
     */

    public function getDataModifica()
    {
        return $this->data_modifica;
    }

    //	/**
    //	 * Recupera l'hash del file (almeno credo)
    //	 *
    //	 * @return string
    //	 */
    //
    //	function getHashFile() {
    //		return $this->hash_file;
    //	}
    //
    /**
     * Recupera delle informazioni aggiuntive sul file
     *
     * @return string
     */

    public function getTipoInfo()
    {
        return $this->tipo_info;
    }

    /**
     * Recupera delle informazioni sull'icona del file
     *
     * @return string
     */

    public function getTipoIcona()
    {
        return $this->tipo_icona;
    }

    /**
     * Recupera la descrizione del file (il formato)
     *
     * @return string
     */

    public function getTipoDesc()
    {
        return $this->tipo_desc;
    }

    /**
     * Recupera la descrizione del file (la tipologia del documento...appunti, esami...)
     *
     * @return string
     */

    public function getCategoriaDesc()
    {
        return $this->categoria_desc;
    }

    /**
     * Recupera lo username dell'autore del file
     *
     * @return string
     */

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Recupera la password del file
     *
     * @return string
     */

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Recupera l'hash del file
     *
     * @return string
     */

    public function getHashFile()
    {
        return $this->hash_file;
    }

    /**
     * Recupera l'id del tipo del file
     *
     * @return string
     */

    public function getIdTipoFile()
    {
        return $this->id_tipo_file;
    }

    /**
     * Recupera l'identificativo della categoria del file
     *
     * @return string
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * Recupera il nome del file
     *
     * @return string
     */
    public function getNomeFile()
    {
        return $this->getIdFile() . '_' . $this->nome_file;
    }

    /**
     * Ritorna il nome del file ripulito
     *
     * @param string
     * @return string
     */
    public static function normalizzaNomeFile($string)
    {
        return preg_replace('/([^a-zA-Z0-9_\.])/', '_', $string);
    }

    /**
     * Recupera il nome originale del file come ? registrato su database
     *
     * @return string
     */

    public function getRawNomeFile()
    {
        return $this->nome_file;
    }

    /**
     * Recupera il numero dei download eseguiti del file
     *
     * @return string
     */

    public function getDownLoad()
    {
        return $this->download;
    }

    /**
     * Recupera l'id del file
     *
     * @return int
     */
    public function getIdFile()
    {
        return $this->id_file;
    }

    /**
     * Imposta l'id del file
     *
     * @param int
     */
    public function setIdFile($id_file)
    {
        $this->id_file = $id_file;
    }

    /**
     * Recupera l'id dell'utente che ha inserito il file
     *
     * @return int
     */
    public function getIdUtente()
    {
        return $this->id_utente;
    }

    /**
     * Imposta l'id dell'utente che ha inserito il file
     *
     * @param int
     */
    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    /**
     * Imposta la descrizione completa del file
     *
     * @param string $descrizione descrizione completa del file
     */

    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    /**
     * Imposta la dimensione del file
     *
     * @param int $dimensione dimensione in kb del file
     */

    public function setDimensione($dimensione)
    {
        $this->dimensione = $dimensione;
    }

    /**
     * Imposta l'id della categoria di utenti ai quali ? permesso il download
     *
     * @param int $permessi_download categoria utenti ai quali ? permesso il download
     */

    public function setPermessiDownload($permessi_download)
    {
        $this->permessi_download = $permessi_download;
    }

    /**
     * Imposta l'id della categoria di utenti ai quali ? permesso visualizzare il file
     *
     * @param int $permessi_visualizza categoria utenti ai quali ? visualizzare il file
     */

    public function setPermessiVisualizza($permessi_visualizza)
    {
        $this->permessi_visualizza = $permessi_visualizza;
    }

    /**
     * Imposta la data di inserimento del file (timestamp)
     *
     * @param int $data_inserimento timestamp del giorno di caricamento del file
     */
    public function setDataInserimento($data_inserimento)
    {
        $this->data_inserimento = $data_inserimento;
    }

    /**
     * Imposta la data di modifica del file
     *
     * @param int $data_modifica timestamp del giorno dell'ultima operazione sul file
     */

    public function setDataModifica($data_modifica)
    {
        $this->data_modifica = $data_modifica;
    }

    /**
     * Imposta l'hash del file (almeno credo)
     *
     * @param string $hash hash del file
     */

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Ritorna l'hash sicuro di una stringa
     *
     * @param  string $string
     * @return string
     */
    public static function passwordHashFunction($string)
    {
        return md5($string);
    }

    /**
     * Imposta delle informazioni aggiuntive sul file
     *
     * @param string $tipo_info informarzioni aggiuntive
     */

    public function setTipoInfo($tipo_info)
    {
        $this->tipo_info = $tipo_info;
    }

    /**
     * Imposta delle informazioni sull'icona del file
     *
     * @param string $tipo_icona
     */

    public function setTipoIcona($tipo_icona)
    {
        $this->tipo_icona = $tipo_icona;
    }

    /**
     * Imposta la descrizione del file (il formato)
     *
     * @param string $tipo_desc
     */

    public function setTipoDesc($tipo_desc)
    {
        $this->tipo_icona = $tipo_desc;
    }

    /**
     * Imposta la descrizione del file (la tipologia del documento...appunti, esami...)
     *
     * @param string $categoria_desc
     */

    public function setCategoriaDesc($categoria_desc)
    {
        $this->categoria_desc = $categoria_desc;
    }

    /**
     * Imposta lo username dell'autore
     *
     * @param string $username
     */

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Imposta la password dell'utente del file
     *
     * @param string $password
     */

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Imposta l'hash del file
     *
     * @param string $hash_file
     */

    public function setHashFile($hash_file)
    {
        $this->hash_file = $hash_file;
    }

    /**
     * Imposta l'identificativo del tipo del file
     *
     * @param string $id_tipo_file
     */

    public function setIdTipoFile($id_tipo_file)
    {
        $this->id_tipo_file = $id_tipo_file;
    }

    /**
     * Imposta l'identificativo della categoria del file
     *
     * @param string $id_categoria
     */

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    /**
     * Imposta il nome del file
     *
     * @param string $nome_file
     */

    public function setRawNomeFile($nome_file)
    {
        $this->nome_file = $nome_file;
    }

    /**
     * @param int  $download
     * @param bool $update_db (must be false)
     */
    public function setDownload($download, $update_db = false)
    {
        $this->download = $download;

        if ($update_db) {
            throw new \InvalidParameterException('$update_db MUST be false');
        }
    }

    /**
     * Aumenta il contatore dei download
     *
     * @param string $download
     */

    public function addDownload()
    {
        $this->setDownload(1 + $this->getDownload(), true);
    }

    /**
     * Imposta il titolo del file
     *
     * @param string $titolo
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    }

    /**
     * Restituisce il tipo di un file su hd tra i tipi ammissibili riconosciuti
     *
     * @param string $nome_file percorso in cui si trova il file
     */
    public static function guessTipo($nome_file, array $tipi_regex)
    {
        foreach ($tipi_regex as $key => $pattern) {
            if ($key === 1) {
                continue;
            }

            if (@preg_match($value, $nome_file)) {
                return $key;
            }
        }

        return 1;
    }
}
