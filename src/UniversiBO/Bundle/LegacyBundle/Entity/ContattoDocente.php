<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * ContattoDocente rappresenta l'insieme di informazioni collegate al
 * contatto di un docente
 *
 * @todo eliminato accessors
 * @package universibo
 * @subpackage class
 * @version 2.2.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ContattoDocente
{
    const CHIUSO = 0;
    const APERTO = 1;
    const KILLED = 2;
    const CRITIC = 3;
    const INATTIVO = 4;
    const ELIMINATO = 'S';
    const NOT_ELIMINATO = 'N';

    /**
     * @var DBContattoDocenteRepository
     */
    private static $repository;

    /**
     * @access private
     */
    public $cod_doc =	null;

    /**
     * @access private
     */
    public $stato;

    /**
     * @access private
     */
    public $id_utente_assegnato;

    /**
     * @access private
     */
    public $ultima_modifica;

    /**
     * @access private
     */
    public $report;

    public $legend = array (
            self::CHIUSO => 'chiuso - non ci sono compiti da eseguire',
            self::APERTO => 'aperto - ci sono compiti da eseguire',
            self::KILLED => 'killed - non ne vuole sapere di universibo',
            self::CRITIC => 'critic - è un pezzo grosso, non è da contattare',
            self::INATTIVO => 'inattivo - non ha corsi attivi nell\'A.A. corrente',
             );

    public function __construct($coddoc, $state, $id, $mod, $report)
    {
        $this->cod_doc 	= $coddoc;
        $this->stato	= $state;
        $this->id_utente_assegnato	= $id;
        $this->ultima_modifica		= $mod;
        $this->report	= $report;
    }

    /**
     * resituisce il contattoDocente corrispondente al Docente
     *
     *	@deprecated
     *  @param int coddoc è il codice del docente di cui si vuole avere informazioni
     *  @return mixed resituisce ContattoDocente se esiste il contatto, false altrimenti
     */
    public static function getContattoDocente ($codDocente)
    {
        return self::getRepository()->findByCodDocente($codDocente);
    }

    /**
     * resituisce il contattoDocente corrispondente al Docente
     *
     *	@deprecated
     *  @return mixed resituisce array di ContattoDocente se esistono, false altrimenti
     */
    public static function getAllContattoDocente()
    {
        return self::getRepository()->findAll();
    }

    public function getStato()
    {
        return $this->stato;
    }

    public function getStatoDesc()
    {
        return $this->legend[$this->stato];
    }
    public function getReport()
    {
        return $this->report;
    }

    public function setReport($rep)
    {
        $this->report = $rep;
    }

    public function appendReport($rep)
    {
        $data =  getdate();
        $this->report = "----------".$data['mday'].'-'.$data['mon'].'-'.$data['year']
        .' '.$data['hours'].':'.$data['minutes'].'-----------'."\n".
        $rep."\n".$this->report."\n\n";
    }

    public function setUltimaModifica($ultimaModifica)
    {
        $this->ultima_modifica = $ultimaModifica;
    }

    public function getUltimaModifica()
    {
        return $this->ultima_modifica;
    }

    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * @param int s	stato del contatto
     * @param int id id utente di chi effettua le modifiche
     */
    public function setStato($s, $id = null)
    {
        $this->stato = $s;

        if ($id != null) {
            $text = User::getUsernameFromId($id).': modifica dello stato assegnato in '."\n".$this->stato.': '.$this->legend[$s];
            $this->appendReport($text);
        }
    }

    public function getCodDoc()
    {
        return $this->cod_doc;
    }

    public function getIdUtenteAssegnato()
    {
        return $this->id_utente_assegnato;
    }

    /**
     * @param int idUtenteMaster id di chi esegue la modifica della assegnamento
     * @param int newIdUtente nuovo collaboratore assegnato
     *
     */
    public function assegna($newIdUtente, $idUtenteMaster)
    {
        $text = User::getUsernameFromId($idUtenteMaster).': assegnato docente a '.User::getUsernameFromId($newIdUtente);
        $this->appendReport($text);
        $this->id_utente_assegnato = $newIdUtente;

    }

    public function updateContattoDocente()
    {
        return self::getRepository()->update($this);
    }

    public function insertContattoDocente()
    {
        return self::getRepository()->insert($this);
    }

    /**
     * @return DBContattoDocenteRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBContattoDocenteRepository(FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}

define('CHIUSO'  , ContattoDocente::CHIUSO);
define('APERTO'  , ContattoDocente::APERTO);
define('KILLED'  , ContattoDocente::KILLED);
define('CRITIC'  , ContattoDocente::CRITIC);
define('INATTIVO', ContattoDocente::INATTIVO);
