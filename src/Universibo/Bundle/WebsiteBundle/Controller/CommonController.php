<?php
namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommonController extends Controller
{
    /**
     * @Template()
     * @return array
     */
    public function dateAction()
    {
        $krono = $this->get('universibo_legacy.krono');

        return array(
            'longDate' => $krono->k_date('%j %F %Y'),
            'time' => $krono->k_date('%H:%i')
        );
    }

    /**
     * @Template()
     * @return array
     */
    public function disclaimerAction()
    {
       return array('disclaimer' => array('Le informazioni contenute nel sito non hanno carattere di ufficialità.',
                'I contenuti sono mantenuti in maniera volontaria dai partecipanti alla comunità di studenti e docenti di UniversiBO. L\'Università di Bologna - Alma Mater Studiorum non può essere considerata legalmente responsabile di alcun contenuto di questo sito.',
                'Ogni marchio citato in queste pagine appartiene al legittimo proprietario.' .
                'Con il contenuto delle pagine appartenenti a questo sito non si è voluto ledere i diritti di nessuno, quindi nel malaugurato caso che questo possa essere avvenuto, vi invitiamo a contattarci affinché le parti in discussione vengano eliminate o chiarite.'));
    }

    /**
     * @Template()
     */
    public function versionAction()
    {
        return array('version' => '2.6.x-dev');
    }
    
    /**
     * @Template()
     * @return array
     */
    public function assetsAction()
    {
        return array();
    }

    /**
     * @Template()
     * @return array
     */
    public function calendarAction()
    {
        $krono = $this->get('universibo_legacy.krono');

        //calendario
        $curr_timestamp = time();
        $curr_mday = date("j", $curr_timestamp);  //inizializzo giorno corrente
        $curr_mese = date("n", $curr_timestamp);  //inizializzo mese corrente
        $curr_anno = date("Y", $curr_timestamp);  //inizializzo anno corrente
        //inizializzo variabili del primo giorno del mese
        $inizio_mese_timestamp = mktime(0, 0, 0, $curr_mese, 1, $curr_anno);
        $inizio_mese_wday = date("w", $inizio_mese_timestamp);

        $giorni_del_mese = date("t", $curr_timestamp); //inizializzo numero giorni del mese corrente
        //inizializzazione contatore dei giorni del mese (con offset giorni vuoti prima dell'1 del mese)
        $conta_mday = ($inizio_mese_wday == 0) ? -5 : 2 - $inizio_mese_wday;

        /* if($inizio_mese_wday==0) $conta_mday=-5;
         else $conta_mday=2-$inizio_mese_wday; */

        $conta_wday = 1;  //variabile contatore dei giorni della settimana
        $tpl_mese = array();

        while ($conta_mday <= $giorni_del_mese) {
            $tpl_settimana = array();

            //disegno una settimana
            do {
                //disegna_giorno($tipo,$numero);
                $c_string = "$conta_mday";
                $today = ($conta_mday == $curr_mday) ? 'true' : 'false';
                if ($conta_mday < 1 || $conta_mday > $giorni_del_mese)
                    $tpl_day = array('numero' => '-', 'tipo' => 'empty', 'today' => $today);
                elseif ($this->isFestivo($conta_mday, $curr_mese, $curr_anno))
                $tpl_day = array('numero' => $c_string, 'tipo' => 'festivo', 'today' => $today);
                elseif ($conta_wday % 7 == 0)
                $tpl_day = array('numero' => $c_string, 'tipo' => 'domenica', 'today' => $today);
                else
                    $tpl_day = array('numero' => $c_string, 'tipo' => 'feriale', 'today' => $today);

                //$tpl_day = array('numero' => $c_string, 'tipo' => $tipo, 'today' => $today);
                $tpl_settimana[] = $tpl_day;
                $conta_wday++;
                $conta_mday++;
            } while ($conta_wday % 7 != 1);

            $tpl_mese[] = $tpl_settimana;
        }

        $weekDays = array('L', 'M', 'M', 'G', 'V', 'S', 'D');

        return array('weekDays' => $weekDays, 'month' => $krono->k_date('%F'),
            'data' => $tpl_mese);
    }

    private function isFestivo($mday, $mese, $anno)
    {
        return ( ($mese == 1 && ($mday == 1 || $mday == 6 )) ||
                ($mese == 4 && $mday == 25) ||
                ($mese == 5 && $mday == 1) ||
                ($mese == 8 && $mday == 15) ||
                ($mese == 11 && $mday == 1) ||
                ($mese == 12 && ($mday == 8 || $mday == 25 || $mday == 26) ) ||
                (easter_date($anno) == mktime(0, 0, 0, $mese, $mday, $anno) ) ||
                (easter_date($anno) == mktime(0, 0, 0, $mese, $mday - 1, $anno) ) );
    }
}
