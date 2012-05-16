<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \Error;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\App\CanaleCommand;

/**
 * ShowHome: mostra la homepage
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowHome extends CanaleCommand
{

    /**
     * Inizializza il comando ShowHome ridefinisce l'initCommand() di CanaleCommand
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);

        $canale = $this->getRequestCanale();
        //var_dump($canale);

        if ( $canale->getTipoCanale() != CANALE_HOME )
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>'Il tipo canale richiesto non corrisponde al comando selezionato','file'=>__FILE__,'line'=>__LINE__));
    }

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template->assign('home_langWelcome', 'Benvenuto in UniversiBO!');
        $template->assignUnicode('home_langWhatIs', 'Questa è la nuova versione della community e degli strumenti per la didattica ideato dagli studenti dell\'Università di Bologna.');
        $template->assignUnicode('home_langMission', 'L\'obiettivo verso cui è tracciata la rotta delle iniziative e dei servizi che trovate su questo portale è di "aiutare gli studenti ad aiutarsi tra loro", fornire un punto di riferimento centralizzato in cui prelevare tutte le informazioni didattiche riguardanti i propri corsi di studio e offrire un mezzo di interazione semplice e veloce con i docenti che partecipano all\'iniziativa.');

        $this->executePlugin('ShowNewsLatest', array( 'num' => 4 ) );

        $this->executePlugin('ShowLinks', array( 'num' => 12 ) );



        return 'default';
    }
}
