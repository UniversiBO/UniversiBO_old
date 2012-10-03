<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * PrgAttivitaDidattica class.
 *
 * Modella una attivit? didattica e le informazioni associate.
 * Ad un insegnamento possono essere associate da 1 a n attivit? didattiche
 * ma sia l'insegnamento che l'attivit? didattiche associate corrispondono
 * allo stesso canale, enteambe le classi estendono Canale.
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 * @todo fare i metodi di accesso attraverso tutta la chiave del DB
 */
class PrgAttivitaDidattica extends Canale
{

    /**
     * @private
     */
    private $annoAccademico;

    /**
     * @private
     */
    private $codiceCdl; //(cod_corso)

    /**
     * @private
     */
    private $codInd;

    /**
     * @private
     */
    private $codOri;

    /**
     * @private
     */
    private $codMateria;

    /**
     * @private
     */
    private $nomeMateria;

    /**
     * @private
     */
    private $annoCorso;

    /**
     * @private
     */
    private $codMateriaIns;

    /**
     * @private
     */
    private $nomeMateriaIns;

    /**
     * @private
     */
    private $annoCorsoIns;

    /**
     * @private
     */
    private $codRil;

    /**
     * @private
     */
    private $codModulo;

    /**
     * @private
     */
    private $codDoc;

    /**
     * @private
     */
    private $nomeDoc;

    /**
     * @private
     */
    private $flagTitolareModulo;

    /**
     * @private
     */
    private $tipoCiclo;

    /**
     * @private
     */
    private $codAte;

    /**
     * @private
     */
    private $annoCorsoUniversibo;

    /**
     * @private
     */
    private $sdoppiato;

    /**
     * null se attività padre, id_sdop se attività sdoppiato (integer)
     * @private
     */
    private $id_sdop;

    /**
     * Crea un oggetto PrgAttivitaDidattica
     *
     * @param int     $id_canale       identificativo del canale su database
     * @param int     $permessi        privilegi di accesso gruppi {@see User}
     * @param int     $ultima_modifica timestamp
     * @param int     $tipo_canale     vedi definizione dei tipi sopra
     * @param string  $immagine        uri    dell'immagine relativo alla cartella del template
     * @param string  $nome            nome      del canale
     * @param int     $visite          numero   visite effettuate sul canale
     * @param boolean $news_attivo     se   true il servizio notizie ? attivo
     * @param boolean $files_attivo    se  true il servizio false ? attivo
     * @param boolean $forum_attivo    se  true il servizio forum ? attivo
     * @param int     $forum_forum_id  se forum_attivo ? true indica l'identificativo del forum su database
     * @param int     $forum_group_id  se forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
     * @param boolean $links_attivo    se true il servizio links ? attivo
     *
     * @param  int          $annoAccademico
     * @param  string       $codiceCdl
     * @param  string       $codInd
     * @param  string       $codOri
     * @param  string       $codMateria
     * @param  string       $nomeMateria
     * @param  string       $annoCorso
     * @param  string       $codMateriaIns
     * @param  string       $nomeMateriaIns
     * @param  int          $annoCorsoIns
     * @param  string       $codRil
     * @param  string       $codModulo
     * @param  string       $codDoc
     * @param  string       $nomeDoc             //questo sar? da cambiare in futuro qualora si voglia riferire un oggetto docente
     * @param  string       $flagTitolareModulo
     * @param  string       $tipoCiclo
     * @param  string       $codAte
     * @param  int          $annoCorsoUniversibo
     * @param  boolean      $sdoppiato
     * @return Insegnamento
     */
    public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine,
    $nome, $visite, $news_attivo, $files_attivo, $forum_attivo,
    $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo, $annoAccademico, $codiceCdl,
    $codInd, $codOri, $codMateria, $nomeMateria, $annoCorso,
    $codMateriaIns, $nomeMateriaIns, $annoCorsoIns, $codRil, $codModulo,
    $codDoc, $nomeDoc, $flagTitolareModulo, $tipoCiclo, $codAte,
    $annoCorsoUniversibo, $sdoppiato, $id_sdop)
    {

        parent::__construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
                $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo);

        $this->annoAccademico	   = $annoAccademico;
        $this->codiceCdl		   = $codiceCdl;
        $this->codInd			   = $codInd;
        $this->codOri			   = $codOri;
        $this->codMateria		   = $codMateria;
        $this->nomeMateria		   = $nomeMateria;
        $this->annoCorso		   = $annoCorso;
        $this->codMateriaIns	   = $codMateriaIns;
        $this->nomeMateriaIns	   = $nomeMateriaIns;
        $this->annoCorsoIns		   = $annoCorsoIns;
        $this->codRil			   = $codRil;
        $this->codModulo		   = $codModulo;
        $this->codDoc			   = $codDoc;
        $this->nomeDoc			   = $nomeDoc;
        $this->flagTitolareModulo  = $flagTitolareModulo;
        $this->tipoCiclo		   = $tipoCiclo;
        $this->codAte			   = $codAte;
        $this->annoCorsoUniversibo = $annoCorsoUniversibo;
        $this->sdoppiato		   = $sdoppiato;
        $this->id_sdop			   = $id_sdop;

    }



    public function getAnnoAccademico()
    {
        return  $this->annoAccademico;
    }

    public function setAnnoAccademico($value)
    {
        $this->annoAccademico = $value;
    }


    public function getCodiceCdl()
    {
        return  $this->codiceCdl;
    }

    public function setCodiceCdl($value)
    {
        $this->codiceCdl = $value;
    }


    public function getCodInd()
    {
        return  $this->codInd;
    }

    public function setCodInd($value)
    {
        $this->codInd = $value;
    }


    public function getCodOri()
    {
        return  $this->codOri;
    }

    public function setCodOri($value)
    {
        $this->codOri = $value;
    }


    public function getCodMateria()
    {
        return  $this->codMateria;
    }

    public function setCodMateria($value)
    {
        $this->codMateria = $value;
    }


    public function getNomeMateria()
    {
        return  $this->nomeMateria;
    }

    public function setNomeMateria($value)
    {
        $this->nomeMateria = $value;
    }


    public function getAnnoCorso()
    {
        return  $this->annoCorso;
    }

    public function setAnnoCorso($value)
    {
        $this->annoCorso = $value;
    }


    public function getCodMateriaIns()
    {
        return  $this->codMateriaIns;
    }

    public function setCodMateriaIns($value)
    {
        $this->codMateriaIns = $value;
    }


    public function getNomeMateriaIns()
    {
        return  $this->nomeMateriaIns;
    }

    public function setNomeMateriaIns($value)
    {
        $this->nomeMateriaIns = $value;
    }


    public function getAnnoCorsoIns()
    {
        return  $this->annoCorsoIns;
    }

    public function setAnnoCorsoIns($value)
    {
        $this->annoCorsoIns = $value;
    }

    public function getCodRil()
    {
        return  $this->codRil;
    }

    public function setCodRil($value)
    {
        $this->codRil = $value;
    }

    public function getCodModulo()
    {
        return  $this->codModulo;
    }

    public function setCodModulo($value)
    {
        $this->codModulo = $value;
    }


    public function getCodDoc()
    {
        return  $this->codDoc;
    }

    public function setCodDoc($value)
    {
        $this->codDoc = $value;
    }


    public function getNomeDoc()
    {
        return  $this->nomeDoc;
    }

    public function setNomeDoc($value)
    {
        $this->nomeDoc = $value;
    }


    public function isTitolareModulo()
    {
        return  $this->flagTitolareModulo == 'S';
    }

    public function setTitolareModulo($value)
    {
        $this->flagTitolareModulo = ($value == true) ? 'S' : 'N';
    }


    public function getTipoCiclo()
    {
        return  $this->tipoCiclo;
    }

    public function setTipoCiclo($value)
    {
        $this->tipoCiclo = $value;
    }


    public function getCodAte()
    {
        return  $this->codAte;
    }

    public function setCodAte($value)
    {
        $this->codAte = $value;
    }


    public function getAnnoCorsoUniversibo()
    {
        return  $this->annoCorsoUniversibo;
    }

    public function setAnnoCorsoUniversibo($value)
    {
        $this->annoCorsoUniversibo = $value;
    }

    public function isSdoppiato()
    {
        return  $this->sdoppiato;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    public function getTranslatedCodRil($cod_ril = NULL)
    {
        if ($cod_ril == NULL) $cod_ril = $this->getCodRil();
        return  ($cod_ril == 'A-Z') ? '' : '('.$cod_ril.')';
    }

    /**
     * Restituisce il nome dell'attivit? didattica
     *
     * @return string
     */
    public function getNome()
    {
        return $this->getNomeMateriaIns().' '.$this->getTranslatedCodRil();
    }

    /**
     * Restituisce il titolo/nome completo dell'attivit? didattica
     *
     * @return string
     */
    public function getTitolo()
    {
        return 'ATTIVITA\' DIDATTICA DI '.$this->getNome();
    }

    /**
     * Restituisce l'id_sdop se attività sdoppiata, null altrimenti
     *
     * @return string
     */
    public function getIdSdop()
    {
        return $this->id_sdop;
    }
}
