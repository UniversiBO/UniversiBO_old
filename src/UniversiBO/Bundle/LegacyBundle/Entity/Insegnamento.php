<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Insegnamento class.
 *
 * Modella un insegnamento e le informazioni associate.
 * Ad un insegnamento possono essere associate da 1 a n attivit� didattiche (PrgAttivitaDidattica).
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class Insegnamento extends Canale
{

    /**
     * @private
     * per il caching del nome dell'insegnamento
     */
    var $insegnamentoNome = NULL;

    /**
     * @private
     * per il caching del nome dell'insegnamento
     */
    var $insegnamentoTitle = NULL;

    /**
     * @private
     * per il caching di tutte le attivit? collegate a questo insegnamento
     */
    var $elencoAttivita = NULL;

    /**
     * @private
     * per il caching di tutte le attivit? collegate a questo insegnamento
     */
    var $elencoAttivitaPadre = NULL;

    /**
     * @private
     * per il caching di tutti i codici dei cdl cui afferisce l'insegnamento
     */
    var $elencoCodiciCDL = NULL;

    /**
     * Crea un oggetto Insegnamento
     *
     * @param int $id_canale 		identificativo del canale su database
     * @param int $permessi 		privilegi di accesso gruppi {@see User}
     * @param int $ultima_modifica 	timestamp
     * @param int $tipo_canale 	 	vedi definizione dei tipi sopra
     * @param string  $immagine		uri dell'immagine relativo alla cartella del template
     * @param string $nome			nome del canale
     * @param int $visite			numero visite effettuate sul canale
     * @param boolean $news_attivo	se true il servizio notizie ? attivo
     * @param boolean $files_attivo	se true il servizio false ? attivo
     * @param boolean $forum_attivo	se true il servizio forum ? attivo
     * @param int $forum_forum_id	se forum_attivo ? true indica l'identificativo del forum su database
     * @param int $forum_group_id	se forum_attivo ? true indica l'identificativo del grupop moderatori del forum su database
     * @param boolean $links_attivo se true il servizio links ? attivo
     * @param string $cod_facolta	codice identificativo d'ateneo della facolt? a 4 cifre
     * @param string $nome_facolta	descrizione del nome della facolt?
     * @param string $uri_facolta	link al sito internet ufficiale della facolt?
     * @return Insegnamento
     */
    public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
    $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo, $files_studenti_attivo, $elenco_attivita)
    {

        parent::__construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
                $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo, $files_studenti_attivo);

        //inizializza l'elenco delle attivit? padre/non sdoppiate
        //var_dump($elenco_attivita);
        $this->elencoAttivita = $elenco_attivita;
        $num = count($elenco_attivita);
        $this->elencoCodiciCDL = array();
        for ($i = 0; $i < $num; $i++)
        {
            if ($elenco_attivita[$i]->isSdoppiato() == false)
            {
                $this->elencoAttivitaPadre[] = $elenco_attivita[$i];
            }
            $this->elencoCodiciCDL[] = $elenco_attivita[$i]->getCodiceCdl();
        }

        //var_dump($this->elencoAttivitaPadre);
        $num = count($this->elencoAttivitaPadre);
        $att = $this->elencoAttivitaPadre[0];
        //inizializza il nome dell'esame
        if ( $num == 1 )
        {
            $cod_ril = ($att->getTranslatedCodRil() == '') ? '' : ' '.$att->getTranslatedCodRil();
            //var_dump($cod_ril);
            $this->insegnamentoNome = $att->getNomeMateriaIns().$cod_ril.' aa. '.$att->getAnnoAccademico().'/'.($att->getAnnoAccademico()+1)." \n ".$att->getNomeDoc();
        }
        else
        {
            // CHE CAS-INOOOOO!!!!!
            $nome     = NULL;
            $max_anno = 0;
            $nomi    = array();
            $e_nomi    = array();
            $b_nomi    = array();
            $t_nomi  = array();
            $anni    = array();
            $docenti = array();
            $cod_ril = array();
            //$app	 = array('nomi'=>NULL,'b_nomi'=>NULL,'e_nomi'=>NULL,'anni'=>NULL,'docenti'=>NULL,'cod_ril'=>NULL);

            $app_elenco_attivita = array();
            $num_att = count($this->elencoAttivitaPadre);
            for ($i = 0; $i < $num_att; $i++)
            {
                $app_elenco_attivita = $this->elencoAttivitaPadre;
                //var_dump($app_elenco_attivita);
                $nomi[$i]    = $app_elenco_attivita[$i]->getNomeMateriaIns();
                $b_nomi[$i]  = substr($nomi[$i], 0, -3);    //nome materia meno le ultime 3 lettere
                $e_nomi[$i]  = substr($nomi[$i], -3, 0);    //ultime 3 lettere del nome materia
                $anni[$i]    = $app_elenco_attivita[$i]->getAnnoAccademico();
                if ($max_anno < $anni[$i]) $max_anno = $anni[$i];
                $docenti[$i] = $app_elenco_attivita[$i]->getNomeDoc();
                $cod_ril[$i] = $app_elenco_attivita[$i]->getCodRil();
            }

            // "NOME ESAME L-A" && "NOME ESAME L-B" -->  "NOME ESAME L-A + L-B"
            //var_dump($e_nomi);
            $fin = array_values($e_nomi);
            if ( (count(array_unique($b_nomi)) == 1) && (count($fin) == 2) ) //bisognerebbe verificare che tutti gli altri campi sono invarianti al raggruppamento
            {
                $nome = $b_nomi[0].$fin[0].' + '.$fin[1];
                for ($i = 0; $i < $num_att; $i++)
                {
                    $nomi[$i] = $nome;
                }
            }

            // "NOME ESAME 2002" && "NOME ESAME 2003" -->  "NOME ESAME 2003/2003"
            //if ( (count(array_unique($anni)) != 1))  //bisognerebbe verificare che tutti gli altri campi sono invarianti al raggruppamento
            //{
                $anniStr = implode('/',array_unique($anni)).'/'.($max_anno+1);
                for ($i = 0; $i < $num_att; $i++)
                {
                    $anni[$i] = $anniStr;
                }

                //}

                //bugfix
                $app_nomi = array();

                //costruisce la mappa dei nomi
                for ($i = 0; $i < $num_att; $i++)
                {
                    //se A-Z non lo metto nel nome
                    if ($cod_ril[$i] == "A-Z") $codice_ril = " ";
                    else $codice_ril = " (".$cod_ril[$i].")";
                    $app_nomi[$i] = $nomi[$i].$codice_ril.' aa. '.$anni[$i]." \nprof. ". ucwords(strtolower($att->getNomeDoc()));
                }
                //var_dump($cod_ril);
                $this->insegnamentoNome = implode(' & ',array_unique($app_nomi));

        }

    }



    /**
     * Crea un oggetto Insegnamento dato il suo numero identificativo id_canale
     * Ridefinisce il factory method della classe padre per restituire un oggetto
     * del tipo Insegnamento
     *
     * @static
     * @param int $id_canale numero identificativo del canale
     * @return mixed Facolta se eseguita con successo, false se il canale non esiste
     */
    function factoryCanale($id_canale)
    {
        $return = Insegnamento::selectInsegnamentoCanale($id_canale);
        return $return;
    }


    /**
     * Restituisce l'uri/link che mostra un canale
     *
     * @return string uri/link che mostra un canale
     */
    function showMe()
    {
        return 'index.php?do=ShowInsegnamento&id_canale='.$this->id_canale;
    }


    /**
     * Restituisce il nome dell'insegnamento:
     * Se ? impostato un nome del canale nella tabella canale lo restituisce
     * Altrimenti se l'Insegnamento ? composta da una sola PrgAttivitaDidattica padre ne restituisce il nome
     * Altrimenti se ? composto da pi? PrgAttivitaDidattica che differiscono per le ultime 3 lettere
     *   restituisce NOME_MATERIA PRI+SEC RIL AA
     * Se ? composto da pi? entit? di cui al punto precedente di anni accademici differenti
     *   restituisce {NOME} AA1/AA2
     *
     * @return string
     */
    function getNome()
    {
        if ($this->isNomeSet()) return parent::getNome();
        return $this->insegnamentoNome;
    }



    /**
     * Restituisce il titolo/nome completo dell'insegnamento
     *
     * @return string
     */
    function getTitolo()
    {
        return "INSEGNAMENTO DI \n".$this->getNome();
    }


    /**
     * Restituisce un array con chiavi numeriche
     * di oggetti PrgAttivitaDidattica corrispondenti a questo Insegnamento
     *
     * @return string
     */
    function getElencoAttivita()
    {
        return $this->elencoAttivita;
    }


    /**
     * Restituisce un array con chiavi numeriche
     * di oggetti PrgAttivitaDidattica NON SDOPPIATE / PADRE
     * corrispondenti a questo Insegnamento
     *
     * @return string
     */
    function getElencoAttivitaPadre()
    {
        return $this->elencoAttivitaPadre;
    }

    /**
     * Restituisce un array con i codici dei cdl di questo insegnamento
     *
     * @return array
     */
    function getElencoCodiciCdl()
    {
        return $this->elencoCodiciCDL;
    }

    /**
     * Seleziona da database e restituisce l'oggetto Insegnamento
     * corrispondente al codice id_canale
     *
     * @static
     * @param int $id_canale identificativo su DB del canale corrispondente al corso di laurea
     * @return mixed Insegnamento se eseguita con successo, false se il canale non esiste
     */
    function selectInsegnamentoCanale($id_canale)
    {

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, id_canale, files_studenti_attivo FROM canale WHERE id_canale = '.$db->quote($id_canale).';';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        $res->fetchInto($row);
        $res->free();

        if( $rows > 1) Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: canale insegnamento non unico','file'=>__FILE__,'line'=>__LINE__));
        if( $rows = 0) return false;

        $elenco_attivita = PrgAttivitaDidattica::selectPrgAttivitaDidatticaCanale($id_canale);
        //var_dump($row);
        $insegnamento = new Insegnamento($row[12], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[13]=='S',$elenco_attivita);

        return $insegnamento;

    }

    /**
     * Restituisce l'uri del command che visulizza il canale
     *
     * @return string URI del command
     */
    //	 function getShowUri()
    //	 {
    //	 	return 'index.php?do=ShowInsegnamento&id_canale='.$this->getIdCanale();
        //	 }


        /*
         * Seleziona da database e restituisce l'oggetto Cdl
        * corrispondente al codice $cod_cdl
        *
        * @todo implementare se serve
        * @static
        * @param string $cod_cdl stringa a 4 cifre del codice d'ateneo del corso di laurea
        * @return Facolta
        *
        function selectInsegnamentoCodice( ...tutta la chiave... )
        {

        $db = FrontController::getDbConnection('main');

        $query = 'SELECT ... WHERE a.id_canale = b.id_canale AND b.cod_corso = '.$db->quote($cod_cdl);

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();

        if( $rows == 0) return false;

        $res->fetchInto($row);
        $insegnamento = new Insegnamento(  ... $row[16] ...  );

        return $insegnamento;

        }
        */

    }
