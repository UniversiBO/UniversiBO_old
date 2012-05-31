<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Docente class, modella le informazioni relative ai docenti
 * A dir la verità non so perché estende User @see Collaboratore
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Docente extends User
{
    /**
     * @var DBDocenteRepository
     */
    private static $repository;

    /**
     * @access private
     */
    public $id_utente;

    /**
     * @access private
     */
    public $codDoc;

    /**
     * @access private
     */
    public $nomeDoc;

    /**
     * @access private
     */
    public $userCache = null;

    /**
     * @access private
     */
    public $rubricaCache = null;

    public function __construct($id_utente, $cod_doc, $nome_doc,
            $rubrica = null)
    {
        $this->id_utente = $id_utente;
        $this->codDoc = $cod_doc;
        $this->nomeDoc = $nome_doc;
        $this->rubricaCache = $rubrica;
    }

    public function getIdUtente()
    {
        return $this->id_utente;
    }

    public function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    public function getCodDoc()
    {
        return $this->codDoc;
    }

    public function getNomeDoc()
    {
        return $this->nomeDoc;
    }

    public function getHomepageDocente()
    {
        return 'http://www.unibo.it/Portale/Strumenti+del+Portale/Rubrica/paginaWebDocente.htm?mat='
                . $this->getCodDoc();
    }

    /**
     * Ritorna Preleva tutti i collaboratori dal database
     *
     * @static
     * @param  int   $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public function getUser()
    {
        if ($this->userCache == NULL) {
            $this->userCache = User::selectUser($this->getIdUtente());
        }

        return $this->userCache;
    }

    /**
     * Ritorna le info del docente prese dalla rubrica
     *
     * @return array
     */
    public function getInfoRubrica()
    {
        if ($this->rubricaCache == NULL) {
            $this->rubricaCache = $this->_getDocenteInfo();
        }

        return $this->rubricaCache;
    }

    /**
     * @access private
     */
    public function _getDocenteInfo()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'SELECT nome, cognome, prefissonome, sesso, email, descrizionestruttura FROM rub_docente WHERE cod_doc = '
                . $db->quote($this->getCodDoc());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();
        if ($rows == 0)

            return false;

        $row = $res->fetchRow();

        $rubrica = array_combine(
                array('nome', 'cognome', 'prefissonome', 'sesso', 'email',
                        'descrizionestruttura'), $row);

        $res->free();

        return $rubrica;

    }

    /**
     * Ritorna un collaboratori dato l'id_utente del database
     *
     * @static
     * @param  int   $id numero identificativo utente
     * @return array Collaboratori
     */
    public static function selectDocente($id, $isCodiceDocente = false)
    {
        return self::getRepository()
                ->findBy($isCodiceDocente ? 'cod_doc' : 'id_utente', $id);
    }

    public static function selectDocenteFromCod($codDoc)
    {
        return self::selectDocente($codDoc, true);
    }

    /**
     * @return DBDocenteRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBDocenteRepository(
                    FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}
