<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

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
    public function setStato($s, $username = null)
    {
        $this->stato = $s;

        if ($username != null) {
            $text = $username.': modifica dello stato assegnato in '."\n".$this->stato.': '.$this->legend[$s];
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
    public function assegna($newUsername, $newIdUtente, $usernameMaster)
    {
        $text = $usernameMaster.': assegnato docente a '.$newUsername;
        $this->appendReport($text);
        $this->id_utente_assegnato = $newIdUtente;
    }
}
