<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \DB;
use \Error;

use UniversiBO\Bundle\LegacyBundle\App\ForumApi;
use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;
use UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage\LegacyCLInterpreter;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

// hack per caricare le costanti
class_exists(
        'UniversiBO\Bundle\LegacyBundle\App\InteractiveCommand\BaseInteractiveCommand');
/**
 * InteractiveCommandHandler is an extension of UniversiboCommand class.
 *
 * Manages Step interactions after login request by user
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto <evaimitico@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class InteractiveCommandHandler extends UniversiboCommand
{
    private $userLogin = null;

    public function execute()
    {
        $frontcontroller = $fc = $this->getFrontController();
        $template = $this->frontController->getTemplateEngine();
        //		$user = $this->getSessionUser();

        //se esiste user in $_SESSION o siamo giunti dal login, o siamo nel bel mezzo di una interazione a step.
        // VERIFY decidere se lanciare un errore o meno
        if (!isset($_SESSION['user']))
            $fc->redirectCommand();
        $this->userLogin = unserialize($_SESSION['user']);

        $referer = (array_key_exists('referer', $_SESSION)) ? $_SESSION['referer']
                : ((array_key_exists('HTTP_REFERER', $_SERVER)) ? $_SERVER['HTTP_REFERER']
                        : '');
        $_SESSION['referer'] = ($referer != '') ? $referer
                : $fc->getReceiverUrl($fc->getReceiverId()); // VERIFY meglio in homepage o in myuniversibo se loggato?

        $activeSteps = (array_key_exists('activeSteps', $_SESSION)) ? $_SESSION['activeSteps']
                : $this->getActiveInteractiveCommand();
        //		var_dump($activeSteps); die;
        if (count($activeSteps) == 0) {
            $sf = false;

            if (array_key_exists('symfony', $_SESSION) && !is_null($_SESSION['symfony'])) {
                $sf = $_SESSION['symfony'];
                $_SESSION['symfony'] = null;
            }
            // completo il login dell'utente
            $_SESSION = array();
            session_destroy();
            session_start();
            $this->userLogin->updateUltimoLogin(time(), true);
            $this->setSessionIdUtente($this->userLogin->getIdUser());
            $fc->setStyle($this->userLogin->getDefaultStyle());

            $forum = new ForumApi();
            $forum->login($this->userLogin);

            if ($sf) {
                $fc->redirectUri($sf);
            } else {

                if (!strstr($referer, 'forum')
                        && (!strstr($referer, 'do')
                                || strstr($referer, 'do=ShowHome')
                                || strstr($referer, 'do=ShowError')
                                || strstr($referer, 'do=Login')
                                || strstr($referer, 'do=RegStudente'))) {
                    $fc->redirectCommand('ShowMyUniversiBO');
                } elseif (strstr($referer, 'forum'))
                    $fc->redirectUri($forum->getMainUri());
                else
                    $fc->redirectUri($referer);
            }
        }

        $action = null;
        $action = (array_key_exists('action', $_GET)
                && in_array($_GET['action'], array(CANC_ACTION, BACK_ACTION))) ? $_GET['action']
                : $action;
        if (isset($_POST['action']))
            $action = NEXT_ACTION;

        $currentStep = current($activeSteps);
        if (!$this
                ->isAllowedInteractionForActualUser($this->userLogin,
                        $currentStep))
            $this->updateActiveSteps($activeSteps);

        $pieces = preg_split('/\\\\/', $currentStep['className']);
        $pluginName = array_pop($pieces);

        $esito = $this->executePlugin($pluginName, $action);
        /*var_dump($esito);
        die;*/

        //TODO verificare se esito � array?
        if (isset($esito['error'])) {
            /**
             * @todo mail agli sviluppatori per correggere subito l'errore, altrimenti la gente non si logga pi�!!
             * per il futuro, pensare a come disabilitare in automatico gli InteractiveCommand con errore
             */
            $notifica_titolo_long = 'WARNING: l\'InteractiveCommand '
                    . $currentStep['className'] . ' e\' errato';
            $notifica_titolo = substr($notifica_titolo_long, 0, 199);
            $notifica_dataIns = time();
            $notifica_urgente = false; // TODO settare come urgente
            $notifica_eliminata = false;
            $notifica_messaggio = '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
' . $notifica_titolo_long . '

Probabilmente l\'InteractiveCommand ' . $currentStep['className']
                    . ' non ha metodi implementati.
Risolvere subito il problema o disabilitarlo quanto prima,
perche` impedisce il login agli utenti
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~';

            $notifica_destinatario = 'mail://'
                    . $frontcontroller->getAppSetting('develEmail');

            $notifica = new NotificaItem(0, $notifica_titolo,
                    $notifica_messaggio, $notifica_dataIns, $notifica_urgente,
                    $notifica_eliminata, $notifica_destinatario);
            $notifica->insertNotificaItem();

            $this->updateActiveSteps($activeSteps);
        }
        //		var_dump($esito);
        if ($action == CANC_ACTION && $esito['priority'] == HIGH_INTERACTION
                && $esito['cancelled']) {
            $_SESSION = array();
            session_destroy();
            session_start();
            // TODO messaggio di errore per spiegare che � obbligatorio accettare?
            $fc->redirectUri($referer);
        }

        //  Elimino dalla lista gli step cancellati dall'utente e quelli completati con successo
        if ($esito['complete']
                || ($action == CANC_ACTION
                        && $esito['priority'] != HIGH_INTERACTION))
            $this->updateActiveSteps($activeSteps);

        $callbackName = $esito['stepName'];

        $template
                ->assign('InteractiveCommandHandler_stepPath',
                        'InteractiveCommand/' . $currentStep['className'] . '/'
                                . $callbackName . '.tpl'); //stepPath. estensione  e path hardcoded
        $template
                ->assign('InteractiveCommandHandler_title_lang',
                        $esito['title']); // TODO dare un title ad ogni InteractiveCommand?
        if (array_key_exists('back', $esito['navigation'])) {
            $template
                    ->assign('InteractiveCommandHandler_back_uri',
                            'v2.php?do=' . $fc->getCommandRequest()
                                    . '&action=' . BACK_ACTION);
            $template
                    ->assign('InteractiveCommandHandler_back_lang',
                            $esito['navigation']['back']);
        }
        $template
                ->assign('InteractiveCommandHandler_canc_uri',
                        'v2.php?do=' . $fc->getCommandRequest() . '&action='
                                . CANC_ACTION);
        $template
                ->assign('InteractiveCommandHandler_canc_lang',
                        $esito['navigation']['canc']);
        $template
                ->assign('InteractiveCommandHandler_next_lang',
                        $esito['navigation']['next']);
    }

    /**
     * @author Pinto
     * @access private
     */
    public function updateActiveSteps(&$activeSteps)
    {
        unset($activeSteps[key($activeSteps)]);
        $_SESSION['activeSteps'] = $activeSteps;
        $this->getFrontController()
                ->redirectCommand('InteractiveCommandHandler');
    }

    /**
     * @author Pinto
     * @access private
     * @return boolean
     */
    public function isAllowedInteractionForActualUser(&$user, &$activeStep)
    {
        //		var_dump($activeStep); die;
        if (empty($activeStep['restrictedTo']))

            return true;
        // nessun gruppo particolare specificato

        $allowedGroups = array();
        foreach ($activeStep['restrictedTo'] as $i)
            if (defined($i))
                $allowedGroups[] = constant($i);
        // verifico che il gruppo dell'utente sia tra quelli specificati
        //		var_dump($allowedGroups); die;
        return (in_array($user->getGroups(), $allowedGroups));
    }

    /**
     * @author Pinto
     * @access private
     * @return array list of available InteractiveCommand
     */
    public function getAllInteractiveCommand()
    {
        $list = $this->frontController->getAvailablePlugins();
        //		var_dump($list);
        $steps = array();
        foreach ($list as $item) {
            if (empty($item['condition'])
                    || $this->evaluateCondition($item['condition']))
                $steps[] = $item;
            //			var_dump($item);
            //			var_dump(get_parent_class($item)); die;
        }
        //		var_dump($steps); die;
        return $steps;
    }

    // 	/**
    // 	 * @author Pinto
    // 	 * @access private
    // 	 * @return array list of ancestor (almeno quelli che riesce a trovare)
    // 	 */
    // 	function get_all_ancerstors_of_class ($class) {
    // 		$list = array();
    // //		$ancestor = get_parent_class($class);
    // //		while ($ancestor != null && $ancestor != 'stdClass' )
    // //		{
    // //			$list[] 	= $ancestor;
    // //			$ancestor 	= get_parent_class($ancestor);
    // //		}
    // 		// versione alternativa migliore. PS servira' il controllo != da stdClass?
    // 		$parentClass = $class;
    // 		while (is_string($parentClass = get_parent_class($parentClass)) && strcasecmp($parentClass, 'stdClass') != 0) {
    //             $list[] = $parentClass;
    //         }
    // //        var_dump($list);
    // 		// TODO se il while si interrompe per il null, vuol dire che la lista � parziale. Gestirlo in modo diverso?
    // 		return $list;
    // 	}

    /**
     * @author Pinto
     * @access private
     * @return array list of active InteractiveCommand
     */
    public function getActiveInteractiveCommand()
    {
        // TODO: migliorare il confronto
        $allSteps = $this->getAllInteractiveCommand();
        $stepsDone = $this->getCompletedInteractiveCommandByUser();

        // 		echo '<pre>';
        // 		var_dump($allSteps);
        // 		var_dump($stepsDone);
        // 		echo '</pre>';
        // 		die;
        $ret = array();
        foreach ($allSteps as $i) {
            if (!in_array($i['bundleClass'], $stepsDone)) {
                $ret[] = $i;
            }
        }

        return $ret;
    }

    /**
     * @author Pinto
     * @access private
     * @return mixed array with the list of InteractiveCommand already completed by current user, false if empty
     */
    public function getCompletedInteractiveCommandByUser()
    {
        $db = $this->getFrontController()->getDbConnection('main');
        $user = unserialize($_SESSION['user']);

        $query = 'SELECT id_step, nome_classe FROM  	step_log
                    WHERE id_utente = ' . $db->quote($user->getIdUser())
                . ' AND  esito_positivo IS NOT NULL ' . // NB suppongo che quelli con esito 'n' siano quelli una-tantum (bassa priorit�) rifiutati
                '';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows = 0)

            return array();

        $list = array();
        while ($res->fetchInto($row)) {
            // TODO this replace is really ugly
            $list[$row[0]] = str_replace('\\\\', '\\', $row[1]);
        }
        $res->free();

        return $list;
    }

    /**
     * valuta se la condizione espressa in linguaggio ConditionLanguage � verificata
     *
     * @param  string  $CL_code
     * @return boolean
     */
    public function evaluateCondition($CL_code)
    {
        return true;
    }
}
