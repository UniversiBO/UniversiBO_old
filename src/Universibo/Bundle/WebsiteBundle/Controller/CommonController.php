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
     * @return array
     */
    public function calendarAction()
    {
        $krono = $this->get('universibo_legacy.krono');

        $weekDays = array('L', 'M', 'M', 'G', 'V', 'S', 'D');

        return array('weekDays' => $weekDays, 'month' => $krono->k_date('%F'));
    }
}
