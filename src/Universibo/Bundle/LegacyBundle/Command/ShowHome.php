<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\LegacyBundle\App\CanaleCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * ShowHome: mostra la homepage
 *
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
        $this->getRequest()->attributes->set('id_canale', 1);

        parent::initCommand($frontController);

        $canale = $this->getRequestCanale();

        if ($canale->getTipoCanale() != Canale::HOME)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $this->sessionUser->getId(),
                            'msg' => 'Il tipo canale richiesto non corrisponde al comando selezionato',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $template->assign('home_langWelcome', 'Benvenuto in UniversiBO!');
        $template
                ->assign('home_langWhatIs',
                        'Questa è la nuova versione della community e degli strumenti per la didattica ideato dagli studenti dell\'Università di Bologna.');
        $template
                ->assign('home_langMission',
                        'L\'obiettivo verso cui è tracciata la rotta delle iniziative e dei servizi che trovate su questo portale è di "aiutare gli studenti ad aiutarsi tra loro", fornire un punto di riferimento centralizzato in cui prelevare tutte le informazioni didattiche riguardanti i propri corsi di studio e offrire un mezzo di interazione semplice e veloce con i docenti che partecipano all\'iniziativa.');

        $this->executePlugin('ShowNewsLatest', array('num' => 4));

        $this->executePlugin('ShowLinks', array('num' => 12));

        return 'default';
    }
}
