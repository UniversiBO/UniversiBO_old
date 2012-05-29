<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
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
    var $annoAccademico;

    /**
     * @private
     */
    var $codiceCdl; //(cod_corso)

    /**
     * @private
     */
    var $codInd;

    /**
     * @private
     */
    var $codOri;

    /**
     * @private
     */
    var $codMateria;

    /**
     * @private
     */
    var $nomeMateria;

    /**
     * @private
     */
    var $annoCorso;

    /**
     * @private
     */
    var $codMateriaIns;

    /**
     * @private
     */
    var $nomeMateriaIns;

    /**
     * @private
     */
    var $annoCorsoIns;

    /**
     * @private
     */
    var $codRil;

    /**
     * @private
     */
    var $codModulo;

    /**
     * @private
     */
    var $codDoc;

    /**
     * @private
     */
    var $nomeDoc;

    /**
     * @private
     */
    var $flagTitolareModulo;

    /**
     * @private
     */
    var $tipoCiclo;

    /**
     * @private
     */
    var $codAte;

    /**
     * @private
     */
    var $annoCorsoUniversibo;

    /**
     * @private
     */
    var $sdoppiato;

    /**
     * null se attivit� padre, id_sdop se attivit� sdoppiato (integer)
     * @private
     */
    var $id_sdop;

    /**
     * @var DBPrgAttivitaDidatticaRepository
     */
    private static $repository;


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
     * @param int     $annoAccademico
     * @param string  $codiceCdl
     * @param string  $codInd
     * @param string  $codOri
     * @param string  $codMateria
     * @param string  $nomeMateria
     * @param string  $annoCorso
     * @param string  $codMateriaIns
     * @param string  $nomeMateriaIns
     * @param int     $annoCorsoIns
     * @param string  $codRil
     * @param string  $codModulo
     * @param string  $codDoc
     * @param string  $nomeDoc             //questo sar? da cambiare in futuro qualora si voglia riferire un oggetto docente
     * @param string  $flagTitolareModulo
     * @param string  $tipoCiclo
     * @param string  $codAte
     * @param int     $annoCorsoUniversibo
     * @param boolean $sdoppiato
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



    function getAnnoAccademico()
    {
        return  $this->annoAccademico;
    }

    function setAnnoAccademico($value)
    {
        $this->annoAccademico = $value;
    }


    function getCodiceCdl()
    {
        return  $this->codiceCdl;
    }

    function setCodiceCdl($value)
    {
        $this->codiceCdl = $value;
    }


    function getCodInd()
    {
        return  $this->codInd;
    }

    function setCodInd($value)
    {
        $this->codInd = $value;
    }


    function getCodOri()
    {
        return  $this->codOri;
    }

    function setCodOri($value)
    {
        $this->codOri = $value;
    }


    function getCodMateria()
    {
        return  $this->codMateria;
    }

    function setCodMateria($value)
    {
        $this->codMateria = $value;
    }


    function getNomeMateria()
    {
        return  $this->nomeMateria;
    }

    function setNomeMateria($value)
    {
        $this->nomeMateria = $value;
    }


    function getAnnoCorso()
    {
        return  $this->annoCorso;
    }

    function setAnnoCorso($value)
    {
        $this->annoCorso = $value;
    }


    function getCodMateriaIns()
    {
        return  $this->codMateriaIns;
    }

    function setCodMateriaIns($value)
    {
        $this->codMateriaIns = $value;
    }


    function getNomeMateriaIns()
    {
        return  $this->nomeMateriaIns;
    }

    function setNomeMateriaIns($value)
    {
        $this->nomeMateriaIns = $value;
    }


    function getAnnoCorsoIns()
    {
        return  $this->annoCorsoIns;
    }

    function setAnnoCorsoIns($value)
    {
        $this->annoCorsoIns = $value;
    }

    function getCodRil()
    {
        return  $this->codRil;
    }

    function setCodRil($value)
    {
        $this->codRil = $value;
    }

    function getCodModulo()
    {
        return  $this->codModulo;
    }

    function setCodModulo($value)
    {
        $this->codModulo = $value;
    }


    function getCodDoc()
    {
        return  $this->codDoc;
    }

    function setCodDoc($value)
    {
        $this->codDoc = $value;
    }


    function getNomeDoc()
    {
        return  $this->nomeDoc;
    }

    function setNomeDoc($value)
    {
        $this->nomeDoc = $value;
    }


    function isTitolareModulo()
    {
        return  $this->flagTitolareModulo == 'S';
    }

    function setTitolareModulo($value)
    {
        $this->flagTitolareModulo = ($value == true) ? 'S' : 'N';
    }


    function getTipoCiclo()
    {
        return  $this->tipoCiclo;
    }

    function setTipoCiclo($value)
    {
        $this->tipoCiclo = $value;
    }


    function getCodAte()
    {
        return  $this->codAte;
    }

    function setCodAte($value)
    {
        $this->codAte = $value;
    }


    function getAnnoCorsoUniversibo()
    {
        return  $this->annoCorsoUniversibo;
    }

    function setAnnoCorsoUniversibo($value)
    {
        $this->annoCorsoUniversibo = $value;
    }


    //	function getCodiceFacolta()
    //	{
    //		return $this->facoltaCodice;
    //	}
    //
    //	function setCodiceFacolta($value)
    //	{
    //		$this->facoltaCodice = $value;
    //	}


    function isSdoppiato()
    {
        return  $this->sdoppiato;
    }





    /**
     * Restituisce
     *
     * @return string
     */
    function getTranslatedCodRil($cod_ril = NULL)
    {
        if ($cod_ril == NULL) $cod_ril = $this->getCodRil();

        return  ($cod_ril == 'A-Z') ? '' : '('.$cod_ril.')';
    }





    /**
     * Restituisce il nome dell'attivit? didattica
     *
     * @return string
     */
    function getNome()
    {
        return $this->getNomeMateriaIns().' '.$this->getTranslatedCodRil();
    }



    /**
     * Restituisce il titolo/nome completo dell'attivit? didattica
     *
     * @return string
     */
    function getTitolo()
    {
        return 'ATTIVITA\' DIDATTICA DI '.$this->getNome();
    }


    /**
     * Restituisce l'id_sdop se attivit� sdoppiata, null altrimenti
     *
     * @return string
     */
    function getIdSdop()
    {
        return $this->id_sdop;
    }




    /**
     * Crea un oggetto PrgAttivitaDidattica dato il suo numero identificativo id_canale
     * Ridefinisce il factory method della classe padre per restituire un oggetto
     * del tipo PrgAttivitaDidattica
     *
     * @param int $id_canale numero identificativo del canale
     * @return mixed Facolta se eseguita con successo, false se il canale non esiste
     */
    public static function factoryCanale($id_canale)
    {
        return self::selectPrgAttivitaDidatticaCanale($id_canale);
    }

    /**
     * Seleziona da database un'array di attività didattiche il cui primo elemento
     * è quello corrispondendete alla chiave indicata, e i successivi sono tutti i suoi sdoppiati
     *
     * @static
     * @param int $id_canale identificativo su DB del canale corrispondente al corso di laurea
     * @return mixed array di PrgAttivitaDidattica se eseguita con successo, false se il canale non esiste
     */
    public static function selectPrgAttivitaDidattica($anno_accademico, $cod_corso, $cod_ind, $cod_ori, $cod_materia,
            $cod_materia_ins, $anno_corso, $anno_corso_ins, $cod_ril, $cod_ate)
    {

        $db = FrontController::getDbConnection('main');

        $anno_accademico = $db->quote( $anno_accademico );
        $cod_corso = $db->quote( $cod_corso );
        $cod_ind = $db->quote( $cod_ind );
        $cod_ori = $db->quote( $cod_ori );
        $cod_materia = $db->quote( $cod_materia );
        $cod_materia_ins = $db->quote( $cod_materia_ins );
        $anno_corso = $db->quote( $anno_corso );
        $anno_corso_ins = $db->quote( $anno_corso_ins );
        $cod_ril = $db->quote( $cod_ril );
        $cod_ate = $db->quote( $cod_ate );

        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo, sdoppiato ) *
        FROM (
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '.$db->quote('N').' AS sdoppiato, null AS id_sdop
        FROM canale c, prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND i.cod_corso='.$cod_corso.'
        AND i.cod_ind='.$cod_ind.'
        AND i.cod_ori='.$cod_ori.'
        AND i.cod_materia='.$cod_materia.'
        AND i.cod_materia_ins='.$cod_materia_ins.'
        AND i.anno_corso='.$anno_corso.'
        AND i.anno_corso_ins='.$anno_corso_ins.'
        AND i.cod_ril='.$cod_ril.'
        AND i.cod_ate='.$cod_ate.'
        UNION
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo, c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, '.$db->quote('S').' AS sdoppiato, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND i.cod_corso='.$cod_corso.'
        AND i.cod_ind='.$cod_ind.'
        AND i.cod_ori='.$cod_ori.'
        AND i.cod_materia='.$cod_materia.'
        AND i.cod_materia_ins='.$cod_materia_ins.'
        AND i.anno_corso='.$anno_corso.'
        AND i.anno_corso_ins='.$anno_corso_ins.'
        AND i.cod_ril='.$cod_ril.'
        AND i.cod_ate='.$cod_ate.'
        ) AS cdl
        ) AS cdl1
        ORDER BY 33, 32, 30, 23';

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();

        if( $rows == 0) {
            $ret = array(); return $ret;
        }
        $elenco = array();
        while (	$res->fetchInto($row) )
        {
            $prgAtt = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                    $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                    $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                    $row[30], $row[31], $row[32]=='S' , $row[33]);

            $elenco[] = $prgAtt;
        }
        $res->free();

        return $elenco;
    }


    /**
     * Seleziona da database e restituisce un array con chiavi numeriche
     * di oggetti PrgAttivitaDidattica corrispondenti al codice id_canale
     * tra gli sdoppiati non sono presi quelli con lo stesso cod_ind e cod_ori
     *
     * @deprecated
     * @param int $id_canale identificativo su DB del canale corrispondente al corso di laurea
     * @return mixed array di PrgAttivitaDidattica se eseguita con successo, false se il canale non esiste
     */
    public static function selectPrgAttivitaDidatticaCanale($id_canale)
    {
        return self::getRepository()->findByChannelId($id_canale);
    }

    /**
     * Seleziona da database e restituisce l'attivit� didattica sdoppiata
     * con id id_sdop
     *
     * @static
     * @param int $id_sdop identificativo dell'attivit� sdoppiata
     * @return mixed PrgAttivitaDidattica se eseguita con successo, false se il canale non esiste
     */
    public static function selectPrgAttivitaDidatticaSdoppiata($id_sdop)
    {

        $db = FrontController::getDbConnection('main');

        $id_sdop = $db->quote($id_sdop);
        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo) *
        FROM (

        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND i.cod_doc=d.cod_doc
        AND s.id_sdop='.$db->quote($id_sdop).'
        ) AS cdl
        ) AS cdl1
        ORDER BY 32, 30, 23';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */
        //		var_dump($query); die;
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();

        if( $rows == 0) {
            $ret = array(); return $ret;
        }

        $res->fetchInto($row);
        $prgAtt = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                $row[30], $row[31], true, $row[32] );

        $res->free();

        return $prgAtt;
    }


    /*
     * Seleziona da database e restituisce l'oggetto Cdl
    * corrispondente al codice $cod_cdl
    *
    * @todo implementare se serve
    * @static
    * @param string $cod_cdl stringa a 4 cifre del codice d'ateneo del corso di laurea
    * @return Facolta
    *
    function selectPrgAttivitaDidatticaCodice( ...tutta la chiave... )
    {
    $db = FrontController::getDbConnection('main');

    $query = 'SELECT ... WHERE a.id_canale = b.id_canale AND b.cod_corso = '.$db->quote($cod_cdl);

    $res = $db->query($query);
    if (DB::isError($res))
        Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

    $rows = $res->numRows();

    if( $rows == 0) return false;

    $res->fetchInto($row);
    $prgAtt = new PrgAttivitaDidattica(  ... $row[16] ...  );

    return $prgAtt;
    }
    */


    /**
     * Seleziona da database e restituisce un'array contenente l'elenco
     * in ordine anno/ciclo/alfabetico di tutti le distinte attivit? didattiche
    * appartenenti al corso di laurea in un dato anno accademico.
    * Ritorna solo una volta le attivit? mutuate/comuni appartenenti a due
    * indirizzi/orientamenti distinti, o moduli identici in tutto il resto della chiave
    *
    * @static
    * @param string $cod_cdl stringa a 4 cifre del codice del corso di laurea
    * @param int $anno_accademico anno accademico
    * @return array(Insegnamento)
    */
    public static function selectPrgAttivitaDidatticaElencoCdl($cod_cdl, $anno_accademico)
    {

        $db = FrontController::getDbConnection('main');

        $cod_cdl         = $db->quote($cod_cdl);
        $anno_accademico = $db->quote($anno_accademico);

        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo ) *
        FROM (
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '.$db->quote('N').' AS sdoppiato, null AS id_sdop
        FROM canale c, prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND cod_corso='.$cod_cdl.'
        UNION
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo,  '.$db->quote('S').' AS sdoppiato, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND i.cod_doc=d.cod_doc
        AND s.cod_corso='.$cod_cdl.'
        AND s.anno_accademico='.$anno_accademico.'
        ) AS cdl
        ) AS cdl1
        ORDER BY 32, 30, 23';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */

        $res = $db->query($query);
        if (DB::isError($res))
        {
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

            return false;
        }

        $rows = $res->numRows();

        if( $rows == 0)
        {
            $array = array();

            return $array;
        }
        $elenco = array();
        while (	$res->fetchInto($row) )
        {
            $prgAtt = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                    $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                    $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                    $row[30], $row[31], $row[32]=='S' , $row[33]);

            $elenco[] = $prgAtt;
        }

        return $elenco;
    }

    /**
     * aggiorna i valori di codiceDocente, ciclo, annoCorsoUniversibo
     */
    public function updatePrgAttivitaDidattica()
    {
        return self::getRepository()->update($this);
    }


    /**
     * @return DBPrgAttivitaDidatticaRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBPrgAttivitaDidatticaRepository(
                    FrontController::getDbConnection('main'));
        }


        return self::$repository;
    }
}
