<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * Docente class, modella le informazioni relative ai docenti
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Docente
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
}
