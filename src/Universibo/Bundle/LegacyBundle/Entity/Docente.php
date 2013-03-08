<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * Docente class, modella le informazioni relative ai docenti
 *
 * @package universibo
 * @version 2.0.0
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Docente
{
    /**
     * @var integer
     */
    private $idUtente;

    /**
     * @var string
     */
    private $codDoc;

    /**
     * @var string
     */
    private $nomeDoc;

    /**
     * @access private
     */
    private $rubricaCache = null;

    /**
     *
     * @param integer $idUtente
     * @param string  $cod_doc
     * @param string  $nome_doc
     * @param mixed   $rubrica
     */
    public function __construct($idUtente = 0, $cod_doc ='', $nome_doc='',
            $rubrica = null)
    {
        $this->idUtente = $idUtente;
        $this->codDoc = $cod_doc;
        $this->nomeDoc = $nome_doc;
        $this->rubricaCache = $rubrica;
    }

    public function getIdUtente()
    {
        return $this->idUtente;
    }

    public function setIdUtente($idUtente)
    {
        $this->idUtente = $idUtente;
    }

    public function setCodDoc($codDoc)
    {
        $this->codDoc = $codDoc;
    }

    public function getCodDoc()
    {
        return $this->codDoc;
    }

    public function getNomeDoc()
    {
        return $this->nomeDoc;
    }

    public function setNomeDoc($nomeDoc)
    {
        $this->nomeDoc = $nomeDoc;

        return $this;
    }

    public function getHomepageDocente()
    {
        return 'http://www.unibo.it/SitoWebDocente/default.htm?mat=' . $this->getCodDoc();
    }
}
