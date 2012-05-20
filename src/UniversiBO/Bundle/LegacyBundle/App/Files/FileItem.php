<?php
namespace UniversiBO\Bundle\LegacyBundle\App\Files;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

/**
 * FileItem class
 *
 * Rappresenta un singolo file.
 *
 * @package universibo
 * @subpackage Files
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabio Crisci <fabioc83@yahoo.it>
 * @author Daniele Tiles
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class FileItem {
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';

    //Todo: mancano le get&set dell'id_file e id_utente

    /**
     * @private
     */
    var $id_file = 0;

    /**
     * @private
     */
    var $permessi_download = 0;

    /**
     * @private
     */
    var $permessi_visualizza = 0;

    /**
     * @private
     */
    var $id_utente = 0;

    /**
     * @private
     */
    var $titolo = '';

    /**
     * @private
     */
    var $descrizione = '';

    /**
     * @private
     */
    var $data_inserimento = 0;

    /**
     * @private
     */
    var $data_modifica = 0;

    /**
     * @private
     */
    var $dimensione = 0;

    /**
     * @private
     */
    var $download = 0;

    /**
     * @private
     */
    var $nome_file = '';

    /**
     * @private
     */
    var $id_categoria = 0;


    /**
     * @private
     */
    var $id_tipo_file = 0;


    /**
     * @private
     */
    var $hash_file = '';


    /**
     * @private
     */
    var $password = '';

    /**
     * @private
     */
    var $username = '';


    /**
     * @private
     */
    //	var $eliminato = false


    /**
     * @private
     */
    var $categoria_desc = '';


    /**
     * @private
     */
    var $tipo_desc = '';




    /**
     * @private
     */
    var $tipo_icona = '';



    /**
     * @private
     */
    var $tipo_info = '';

    ///////////////////////////////////////////


    /**
     * @private
     */
    var $elencoIdCanali = NULL;

    /**
     * @private
     */
    var $elencoCanali = NULL;

    /**
     * @private
     */
    var $paroleChiave = NULL;

    /**
     * Crea un oggetto FileItem con i parametri passati
     *
     *
     * @param int     $id_file           id del file
     * @param string  $titolo            titolo del file
     * @param string  $descrizione       descrizione completa del file
     * @param int     $dimensione        dimensione in kb del file
     * @param int     $permessi_download categoria utenti ai quali ? permesso il download
     * @param boolean $eliminato         flag stato del file
     * @return FileItem
     */

    public function __construct($id_file, $permessi_download, $permessi_visualizza, $id_utente, $titolo, $descrizione, $data_inserimento, $data_modifica, $dimensione, $download, $nome_file, $id_categoria, $id_tipo_file, $hash_file, $password, $username, $categoria_desc, $tipo_desc, $tipo_icona, $tipo_info /*, $eliminato*/)
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

    function getTitolo() {
        return $this->titolo;
    }

    /**
     * Recupera la descrizione completa del file
     *
     * @return string
     */

    function getDescrizione() {
        return $this->descrizione;
    }

    /**
     * Recupera la dimensione del file
     *
     * @return int
     */

    function getDimensione() {
        return $this->dimensione;
    }

    /**
     * Recupera l'id della categoria di utenti ai quali e permesso il download
     *
     * @return int
     */
    function getPermessiDownload() {
        return $this->permessi_download;
    }

    /**
     * Recupera l'id delle categorie di utenti ai quali e permesso la visualizzazione
     *
     * @return int
     */
    function getPermessiVisualizza() {
        return $this->permessi_visualizza;
    }

    /**
     * Recupera la data di caricamento del file (timestamp)
     *
     * @return int
     */

    function getDataInserimento() {
        return $this->data_inserimento;
    }

    /**
     * Recupera la data di ultima modifica del file (timestamp)
     *
     * @return int
     */

    function getDataModifica() {
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

    function getTipoInfo() {
        return $this->tipo_info;
    }

    /**
     * Recupera delle informazioni sull'icona del file
     *
     * @return string
     */

    function getTipoIcona() {
        return $this->tipo_icona;
    }

    /**
     * Recupera la descrizione del file (il formato)
     *
     * @return string
     */

    function getTipoDesc() {
        return $this->tipo_desc;
    }

    /**
     * Recupera la descrizione del file (la tipologia del documento...appunti, esami...)
     *
     * @return string
     */

    function getCategoriaDesc() {
        return $this->categoria_desc;
    }

    /**
     * Recupera lo username dell'autore del file
     *
     * @return string
     */

    function getUsername() {
        return $this->username;
    }

    /**
     * Recupera la password del file
     *
     * @return string
     */

    function getPassword()
    {
        return $this->password;
    }

    /**
     * Recupera l'hash del file
     *
     * @return string
     */

    function getHashFile() {
        return $this->hash_file;
    }

    /**
     * Recupera l'id del tipo del file
     *
     * @return string
     */

    function getIdTipoFile() {
        return $this->id_tipo_file;
    }

    /**
     * Recupera l'identificativo della categoria del file
     *
     * @return string
     */
    function getIdCategoria() {
        return $this->id_categoria;
    }

    /**
     * Recupera il nome del file
     *
     * @return string
     */
    function getNomeFile() {
        return $this->getIdFile().'_'.$this->nome_file;
    }


    /**
     * Ritorna il nome del file ripulito
     *
     * @param string
     * @return string
     */
    function normalizzaNomeFile($string) {
        return preg_replace('/([^a-zA-Z0-9_\.])/','_',$string);
    }


    /**
     * Recupera il nome originale del file come ? registrato su database
     *
     * @return string
     */

    function getRawNomeFile() {
        return $this->nome_file;
    }

    /**
     * Recupera il numero dei download eseguiti del file
     *
     * @return string
     */

    function getDownLoad() {
        return $this->download;
    }

    /**
     * Recupera l'id del file
     *
     * @return int
     */
    function getIdFile() {
        return $this->id_file;
    }

    /**
     * Recupera le parole chiave
     *
     * @return array di string
     */
    function getParoleChiave() {
        if ($this->paroleChiave == NULL)
            $this->paroleChiave = FileKeyWords::selectFileKeyWords($this->getIdFile());

        return $this->paroleChiave;
    }


    /**
     * Imposta le parole chiave
     *
     * @param array di string
     */
    function setParoleChiave($paroleChiave) {
        FileKeyWords::updateFileKeyWords($this->getIdFile(), $paroleChiave);
        $this->paroleChiave = $paroleChiave;

        return $this->paroleChiave;
    }


    /**
     * Imposta l'id del file
     *
     * @param int
     */
    function setIdFile($id_file) {
        $this->id_file = $id_file;
    }

    /**
     * Recupera l'id dell'utente che ha inserito il file
     *
     * @return int
     */
    function getIdUtente() {
        return $this->id_utente;
    }

    /**
     * Imposta l'id dell'utente che ha inserito il file
     *
     * @param int
     */
    function setIdUtente($id_utente) {
        $this->id_utente = $id_utente;
    }

    /**
     * Imposta la descrizione completa del file
     *
     * @param string $descrizione descrizione completa del file
     */

    function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    /**
     * Imposta la dimensione del file
     *
     * @param int $dimensione dimensione in kb del file
     */

    function setDimensione($dimensione) {
        $this->dimensione = $dimensione;
    }

    /**
     * Imposta l'id della categoria di utenti ai quali ? permesso il download
     *
     * @param int $permessi_download categoria utenti ai quali ? permesso il download
     */

    function setPermessiDownload($permessi_download) {
        $this->permessi_download = $permessi_download;
    }

    /**
     * Imposta l'id della categoria di utenti ai quali ? permesso visualizzare il file
     *
     * @param int $permessi_visualizza categoria utenti ai quali ? visualizzare il file
     */

    function setPermessiVisualizza($permessi_visualizza) {
        $this->permessi_visualizza = $permessi_visualizza;
    }

    /**
     * Imposta la data di inserimento del file (timestamp)
     *
     * @param int $data_inserimento timestamp del giorno di caricamento del file
     */
    function setDataInserimento($data_inserimento) {
        $this->data_inserimento = $data_inserimento;
    }

    /**
     * Imposta la data di modifica del file
     *
     * @param int $data_modifica timestamp del giorno dell'ultima operazione sul file
     */

    function setDataModifica($data_modifica) {
        $this->data_modifica = $data_modifica;
    }

    /**
     * Imposta l'hash del file (almeno credo)
     *
     * @param string $hash hash del file
     */

    function setHash($hash) {
        $this->hash = $hash;
    }


    /**
     * Ritorna l'hash sicuro di una stringa
     *
     * @param string $string
     * @return string
     */
    function passwordHashFunction($string)
    {
        return md5($string);
    }


    /**
     * Imposta delle informazioni aggiuntive sul file
     *
     * @param string $tipo_info informarzioni aggiuntive
     */

    function setTipoInfo($tipo_info) {
        $this->tipo_info = $tipo_info;
    }

    /**
     * Imposta delle informazioni sull'icona del file
     *
     * @param string $tipo_icona
     */

    function setTipoIcona($tipo_icona) {
        $this->tipo_icona = $tipo_icona;
    }

    /**
     * Imposta la descrizione del file (il formato)
     *
     * @param string $tipo_desc
     */

    function setTipoDesc($tipo_desc) {
        $this->tipo_icona = $tipo_desc;
    }

    /**
     * Imposta la descrizione del file (la tipologia del documento...appunti, esami...)
     *
     * @param string $categoria_desc
     */

    function setCategoriaDesc($categoria_desc) {
        $this->categoria_desc = $categoria_desc;
    }

    /**
     * Imposta lo username dell'autore
     *
     * @param string $username
     */

    function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Imposta la password dell'utente del file
     *
     * @param string $password
     */

    function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Imposta l'hash del file
     *
     * @param string $hash_file
     */

    function setHashFile($hash_file) {
        $this->hash_file = $hash_file;
    }

    /**
     * Imposta l'identificativo del tipo del file
     *
     * @param string $id_tipo_file
     */

    function setIdTipoFile($id_tipo_file) {
        $this->id_tipo_file = $id_tipo_file;
    }

    /**
     * Imposta l'identificativo della categoria del file
     *
     * @param string $id_categoria
     */

    function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    /**
     * Imposta il nome del file
     *
     * @param string $nome_file
     */

    function setRawNomeFile($nome_file) {
        $this->nome_file = $nome_file;
    }

    /**
     * Imposta il numero dei download dei file
     *
     * @param string $download
     */

    function setDownload($download, $update_db = false) {
        $this->download = $download;

        if ( $update_db == true )
        {
            $db = FrontController::getDbConnection('main');

            $query = 'UPDATE file SET download = '.$db->quote($download).' WHERE id_file = '.$db->quote($this->getIdFile());
            $res = $db->query($query);
            if (DB::isError($res))
                Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
            $rows = $db->affectedRows();

            if( $rows == 1) return true;
            elseif( $rows == 0) return false;
            else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database file non unico','file'=>__FILE__,'line'=>__LINE__));
        }

    }


    /**
     * Aumenta il contatore dei download
     *
     * @param string $download
     */

    function addDownload() {
        $this->setDownload(1 + $this->getDownload(), true);
    }


    /**
     * Imposta il titolo del file
     *
     * @param string $titolo
     */
    function setTitolo($titolo) {
        $this->titolo = $titolo;
    }


    /**
     * Restituisce il tipo di un file su hd tra i tipi ammissibili riconosciuti
     *
     * @param string $nome_file percorso in cui si trova il file
     * @TODO preg_match
     */
    function guessTipo($nome_file)
    {
        static $tipi_regex = NULL;

        if ($tipi_regex == NULL)
        {
            $db = & FrontController::getDbConnection('main');

            $query = 'SELECT id_file_tipo, pattern_riconoscimento FROM file_tipo WHERE id_file_tipo != 1';
            $res = & $db->query($query);

            if (DB :: isError($res))
                Error::throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

            $tipi_regex = array ();

            while ($res->fetchInto($row)) {
                $tipi_regex[$row[0]] = $row[1];
            }

            $res->free();

        }
        //echo $nome_file;
        foreach($tipi_regex as $key => $value)
        {
            // @TODO cambiare database
            //echo '['.$value.'-'.ereg($value, $nome_file).']';
            if ( @ereg($value, $nome_file) )

                return $key;
        }

        return 1;

    }


    /**
     * Recupera i possibili tipi di un file
     *
     * @static
     * @return array [id_tipo] => 'descrizione'
     */
    function getTipi()
    {
        static $tipi = NULL;

        if ($tipi != NULL)

            return $tipi;

        $db = & FrontController::getDbConnection('main');

        $query = 'SELECT id_file_tipo, descrizione FROM file_tipo';
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error::throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $tipi = array ();

        while ($res->fetchInto($row)) {
            $tipi[$row[0]] = $row[1];
        }

        $res->free();

        return $tipi;
    }



    /**
     * Recupera le categorie possibili di un file
     *
     * @static
     * @return array [id_categoria] => 'descrizione'
     */
    function getCategorie()
    {
        static $categorie = NULL;

        if ($categorie != NULL)

            return $categorie;

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_file_categoria, descrizione FROM file_categoria';
        $res = $db->query($query);

        if (DB :: isError($res))
            Error::throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $categorie = array ();

        while ($res->fetchInto($row)) {
            $categorie[$row[0]] = $row[1];
        }

        $res->free();

        return $categorie;
    }

    /**
     * Preleva da database i file del canale $id_canale
     *
     * @static
     * @param int $id_canale identificativo su database del canale
     * @return array elenco FileItem , array vuoto se non ci sono file
     */
    function selectFileCanale($id_canale)
    {

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT A.id_file  FROM file A, file_canale B
        WHERE A.id_file = B.id_file AND eliminato!='.$db->quote( FILE_ELIMINATO ).
        ' AND B.id_canale = '.$db->quote($id_canale).' AND A.data_inserimento < '.$db->quote(time()).
        'ORDER BY A.id_categoria, A.data_inserimento DESC';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $id_file_list = array();

        while ( $res->fetchInto($row) )
        {
            $id_file_list[]= $row[0];
        }

        $res->free();

        return $id_file_list;

    }


    /**
     * Recupera un file dal database
     *
     * @static
     * @param int $id_file id del file
     * @return FileItem
     */
    function  selectFileItem($id_file) {
        $id_files = array ($id_file);
        $files = & FileItem :: selectFileItems($id_files);
        if ($files === false)

            return false;
        return $files[0];
    }

    /**
     * Recupera un elenco di file dal database
     * non ritorna i files eliminati
     *
     * @static
     * @param array $id_file array elenco di id dei file
     * @return array FileItem
     */
    function  selectFileItems($id_files) {

        $db =  FrontController::getDbConnection('main');

        if (count($id_files) == 0)	{
            $return = array(); return $return;
        }

        //esegue $db->quote() su ogni elemento dell'array
        //array_walk($id_notizie, array($db, 'quote'));
        if (count($id_files) == 1)
            $values = $id_files[0];
        else
            $values = implode(',', $id_files);

        //		$query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        //						 A.descrizione, data_inserimento, data_modifica, dimensione, download,
        //						 nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        //						 username, C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        //						 FROM file A, utente B, file_categoria C, file_tipo D
        //						 WHERE A.id_utente = B.id_utente AND A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
        $query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        A.descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        FROM file A, file_categoria C, file_tipo D
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
        $res = & $db->query($query);

        //echo $query;

        if (DB :: isError($res))
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows == 0)

            return false;
        $files_list = array ();

        while ($res->fetchInto($row)) {
            $username = User::getUsernameFromId($row[3]);
            $files_list[] = new FileItem($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $username, $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }

    /**
     * restituisce tutti i file caricati da un determinato utente
     *
     * @param int     $id_utente NB deve essere un id valido, fate il check prima di invocare il metodo
     * @param boolean $order     ordina i file in ordine decrescente di data
     * @return mixed false se non trova file, array di fileItem altrimenti
     */
    function  selectFileItemsByIdUtente($id_utente, $order=false) {

        $db = FrontController :: getDbConnection('main');

        $query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        A.descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        FROM file A, file_categoria C, file_tipo D
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND  eliminato!='.$db->quote(FILE_ELIMINATO) .
        ' AND id_utente = '. $db->quote($id_utente) . ($order ? ' ORDER BY data_inserimento DESC' : '');
        $res = & $db->query($query);

        //echo $query;

        if (DB :: isError($res))
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows == 0)

            return false;
        $files_list = array ();

        while ($res->fetchInto($row)) {
            $username = User::getUsernameFromId($row[3]);
            $files_list[] = new FileItem($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $username, $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }


    /**
     * Seleziona gli id_canale per i quali il file ? inerente
     * non si possono fare garanzie sull'ordine dei canali
     *
     * @return array	elenco degli id_canale
     */
    function  getIdCanali() {
        if ($this->elencoIdCanali != NULL)

            return $this->elencoIdCanali;

        $id_file = $this->getIdFile();

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_canale FROM file_canale WHERE id_file='.$db->quote($id_file).' ORDER BY id_canale';
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $elenco_id_canale = array ();

        while ($res->fetchInto($row)) {
            $elenco_id_canale[] = $row[0];
        }

        $res->free();

        $this->elencoIdCanali = & $elenco_id_canale;

        return $this->elencoIdCanali;

    }

    /**
     * rimuove il file dal canale specificato
     *
     * @param int $id_canale identificativo del canale
     */
    function removeCanale($id_canale) {

        $db = & FrontController :: getDbConnection('main');

        $query = 'DELETE FROM file_canale WHERE id_canale='.$db->quote($id_canale).' AND id_file='.$db->quote($this->getIdFile());
        //? da testare il funzionamento di =
        $res = & $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        // rimuove l'id del canale dall'elenco completo
        $this->elencoIdCanali = array_diff($this->elencoIdCanali, array ($id_canale));

        /**
         * @TODO settare eliminata = 'S' quando il file viene tolto dall'ultimo canale
         */
    }

    /**
     * aggiunge il file al canale specificato
     *
     * @param int $id_canale identificativo del canale
     * @return boolean  true se esito positivo
     */
    function addCanale($id_canale) {
        $return = true;

        if (!Canale::canaleExists($id_canale)) {
            return false;
            //Error::throwError(_ERROR_CRITICAL,array('msg'=>'Il canale selezionato non esiste','file'=>__FILE__,'line'=>__LINE__));
        }

        $db = & FrontController :: getDbConnection('main');

        $query = 'INSERT INTO file_canale (id_file, id_canale) VALUES ('.$db->quote($this->getIdFile()).','.$db->quote($id_canale).')';
        //? da testare il funzionamento di =
        $res = $db->query($query);
        if (DB :: isError($res)) {
            return false;
            //	$db->rollback();
            //	Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $this->elencoIdCanale[] = $id_canale;

        return true;

    }

    /**
     * Inserisce un file sul DB
     *
     * @param array $array_id_canali elenco dei canali in cui bisogna inserire il file. Se non si passa un canale si recupera quello corrente.
     * @return	 boolean true se avvenua con successo, altrimenti Error object
     */
    function insertFileItem() {
        $db = & FrontController :: getDbConnection('main');

        ignore_user_abort(1);
        $db->autoCommit(false);
        $next_id = $db->nextID('file_id_file');
        $this->setIdFile($next_id);
        $return = true;
        $eliminata = self::NOT_ELIMINATO;
        $query = 'INSERT INTO file (id_file, permessi_download, permessi_visualizza, id_utente, titolo,
        descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, id_categoria, id_tipo_file, hash_file, password, eliminato) VALUES '.
        '( '.$next_id.' , '.
        $db->quote($this->getPermessiDownload()).' , '.
        $db->quote($this->getPermessiVisualizza()).' , '.
        $db->quote($this->getIdUtente()).' , '.
        $db->quote($this->getTitolo()).' , '.
        $db->quote($this->getDescrizione()).' , '.
        $db->quote($this->getDataInserimento()).' , '.
        $db->quote($this->getDataModifica()).' , '.
        $db->quote($this->getDimensione()).' , '.
        $db->quote($this->getDownload()).' , '.
        $db->quote($this->getRawNomeFile()).' , '.
        $db->quote($this->getIdCategoria()).' , '.
        $db->quote($this->getIdTipoFile()).' , '.
        $db->quote($this->getHashFile()).' , '.
        $db->quote($this->getPassword()).' , '.
        $db->quote(FILE_NOT_ELIMINATO).' )';

        $res = $db->query($query);
        //echo $query;

        if (DB :: isError($res)) {
            $db->rollback();
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB::errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }


        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);
    }

    /**
     * Aggiorna le modifiche al file nel DB
     *
     * @return	boolean true se avvenua con successo, altrimenti lancia Error Critical
     */
    function updateFileItem()
    {
        $db = & FrontController::getDbConnection('main');

        ignore_user_abort(1);
        $db->autoCommit(false);
        $return = true;
        //$scadenza = ($this->getDataScadenza() == NULL) ? ' NULL ' : $db->quote($this->getDataScadenza());
        //$flag_urgente = ($this->isUrgente()) ? NEWS_URGENTE : NEWS_NOT_URGENTE;
        //$deleted = ($this->isEliminata()) ? NEWS_ELIMINATA : NEWS_NOT_ELIMINATA;
        $query = 'UPDATE file SET id_file = '.$db->quote($this->getIdFile()).' , permessi_download = '.$db->quote($this->getPermessiDownload()).' , permessi_visualizza = '.$db->quote($this->getPermessiVisualizza()).' , id_utente = '.$db->quote($this->getIdUtente()).' , titolo = '.$db->quote($this->getTitolo()).' , descrizione = '.$db->quote($this->getDescrizione()).' , data_inserimento = '.$db->quote($this->getDataInserimento()).' , data_modifica = '.$db->quote($this->getDataModifica()).' , dimensione = '.$db->quote($this->getDimensione()).' , download = '.$db->quote($this->getDownload()).' , nome_file = '.$db->quote($this->getRawNomeFile()).' , id_categoria = '.$db->quote($this->getIdCategoria()).' , id_tipo_file = '.$db->quote($this->getIdTipoFile()).' , hash_file = '.$db->quote($this->getHashFile()).' , password = '.$db->quote($this->getPassword()).' WHERE id_file = '.$db->quote($this->getIdFile());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB :: isError($res)) {
            $db->rollback();
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
            $return = false;
        }

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return $return;
    }


    /**
     * controlla se il file ? stato eliminato da tutti i canali in cui era presente, e aggiorna il db
     *
     * @return	 boolean true se avvenua con successo, altrimenti false
     */
    function deleteFileItem()
    {
        $lista_canali = & $this->getIdCanali(true);
        if (count($lista_canali) == 0) {
            $db = & FrontController::getDbConnection('main');
            $query = 'UPDATE file SET eliminato  = '.$db->quote(FILE_ELIMINATO).' WHERE id_file = '.$db->quote($this->getIdFile());
            //echo $query;
            $res = $db->query($query);
            //var_dump($query);
            if (DB :: isError($res)) {
                $db->rollback();
                Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
            }

            return true;
        }

        return false;
    }
}

define('FILE_ELIMINATO', FileItem::ELIMINATO);
define('FILE_NOT_ELIMINATO', FileItem::NOT_ELIMINATO);
