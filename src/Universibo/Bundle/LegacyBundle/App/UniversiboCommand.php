<?php
namespace Universibo\Bundle\LegacyBundle\App;

use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Facolta;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\Framework\BaseCommand;
use Universibo\Bundle\LegacyBundle\Entity\User;

use \Error;
/**
 * UniversiboCommand is the abstract super class of all command classes
 * used in the universibo application.
 *
 * Adds user authorization and double view (popup/index)
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */
abstract class UniversiboCommand extends BaseCommand
{
    /**
     * User
     */
    protected $sessionUser;

    /**
     * Restituisce l'id_utente del dello user nella sessione corrente
     *
     * @static
     * @return int
     */
    public function getSessionIdUtente()
    {
        return $_SESSION['id_utente'];
    }

    /**
     * Salva l'id_utente dello user nella sessione corrente
     *
     * @static
     * @protected
     * @param int $id_utente id_utente dello user
     */
    public function setSessionIdUtente($id_utente)
    {
        $_SESSION['id_utente'] = $id_utente;
    }

    /**
     * Restituisce true se un utente (anche ospite) ? stato registrato nella sessione corrente
     *
     * @static
     * @return boolean
     */
    public function sessionUserExists()
    {
        return array_key_exists('id_utente', $_SESSION) && isset($_SESSION['id_utente']);
    }

    /**
     * Restituisce l'oggetto utente della sessione corrente.
     *
     * Pu? essere chiamata solo dopo che ? stata eseguita initCommand altrimenti
     * il valore di ritorno ? indefinito
     *
     * @return User
     */
    public function getSessionUser()
    {
        return $this->sessionUser;
    }

    /**
     * Inizializza l' UniversiboCommand ridefinisce l'init() del BaseCommand.
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);

        $template = $frontController->getTemplateEngine();
        $template->assign('error_notice_present', 'false');

        $this->_setUpUserUniversibo();

        $this->_initTemplateUniversibo();
    }

    /**
     * Ridefinisce il metodo della classe padre
     * Si occupa di raccogliere tutti gli errori non ancora lanciati
     */
    public function shutdownCommand()
    {
        parent::shutdownCommand();


        if ($this->isPopup()) {
            $this->_shutdownTemplatePopupUniversibo();
        } else {
            $this->_shutdownTemplateIndexUniversibo();
        }

        //raccolgo tutti gli errori
        while (($current_error = Error::retrieve(_ERROR_NOTICE)) !== false) {
            echo $current_error->throwError();
        }

        while (($current_error = Error::retrieve(_ERROR_DEFAULT)) !== false) {
            echo $current_error->throwError();
        }

        while (($current_error = Error::retrieve(_ERROR_CRITICAL)) !== false) {
            echo $current_error->throwError();
        }

        $fc = $this->getFrontController();
        $fc->getMail(MAIL_KEEPALIVE_CLOSE);
    }

    /**
     * Restituisce se la pagina chiamata ? di tipo indice (con menu) o popup (senza menu)
     *
     * @return boolean
     */
    public function isPopup()
    {
        return (boolean) (array_key_exists('pageType', $_GET) && $_GET['pageType'] == 'popup');
    }

    /**
     * Inizializza le informazioni utente dell' UniversiboCommand
     *
     * @private
     */
    public function _setUpUserUniversibo()
    {
        if (!$this->sessionUserExists()) {
            $this->sessionUser = new User(0, User::OSPITE);
            $this->setSessionIdUtente(0);
        } elseif ($this->getSessionIdUtente() >= 0) {
            $this->sessionUser = User::selectUser($this->getSessionIdUtente());
            //			echo $this->sessionUser->getUsername();
        } else
            Error::throwError(_ERROR_CRITICAL, array('id_utente' => $this->sessionUser->getIdUser(), 'msg' => 'id_utente registrato nella sessione non valido', 'file' => __FILE__, 'line' => __LINE__));
        //		var_dump($this->sessionUser);
    }

    /**
     * Inizializza le informazioni comuni del template dell' UniversiboCommand
     * esegue distizione tra pagine con indice completo e popup
     *
     * @private
     */
    public function _initTemplateUniversibo()
    {
        $template = $this->frontController->getTemplateEngine();
        $krono = $this->frontController->getKrono();
        //var_dump($template);

        if ($this->isPopup()) {
            $template->assign('common_pageType', 'popup');
            $template->assign('common_pageTypeExt', 'pageType=popup&');
            $this->_initTemplatePopupUniversibo();
        } else {
            $template->assign('common_pageType', 'index');
            $template->assign('common_pageTypeExt', '');
            $this->_initTemplateIndexUniversibo();
        }

        //riferimenti per ottimizzare gli accessi
        $templateInfo = & $this->frontController->templateInfo;
        $fc = $this->getFrontController();

        $template->assign('common_templateBaseDir', $templateInfo['web_dir'] . $templateInfo['styles'][$templateInfo['template_name']]);

        $tpsettings = $this->frontController->getTemplateEngineSettings();
        $temp_template_list = $tpsettings['styles'];
        $template_list = array();
        $i = 0;
        foreach ($temp_template_list as $key => $value) {
            $template_list[$i] = array('uri' => 'index.php?setTemplate=' . $key, 'label' => $key);
            $i++;
        }

        $template->assign('common_templateList', $template_list);

        $request_protocol = (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

        // http | https
        $template->assign('common_protocol', $request_protocol);
        // www.universibo.unibo.it
        $template->assign('common_hostName', ( array_key_exists('HTTP_HOST', $_SERVER) ) ? $_SERVER['HTTP_HOST'] : '');
        // https://www.universibo.unibo.it/path_universibo2/
        @ $template->assign('common_rootUrl', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $fc->getRootPath());
        // https://www.universibo.unibo.it/path_universibo2/receiver.php
        @ $template->assign('common_receiverUrl', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
        // https://www.universibo.unibo.it/path_universibo2/receiver.php?do=SomeCommand
        @ $template->assign('common_requestUri', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        // /path_universibo2/receiver.php?do=SomeCommand
        @ $template->assign('common_shortUri', $_SERVER['REQUEST_URI']);

        $template->assign('common_homepage', 'Homepage');
        $template->assign('common_homepageUri', '/?do=ShowHome');

        $forum = $this->getContainer()->get('universibo_legacy.forum.api');
        $template->assign('common_forum', 'Forum');
        $template->assign('common_forumDir', $forum->getPath());
        $template->assign('common_forumUri', $forum->getMainUri());

        $template->assign('common_homepage', 'Homepage');
        $template->assign('common_homepageUri', '/?do=ShowHome');

        $template->assign('common_rootEmail', $fc->getAppSetting('rootEmail'));
        $template->assign('common_infoEmail', $fc->getAppSetting('infoEmail'));
        $template->assign('common_staffEmail', $fc->getAppSetting('staffEmail'));
        $template->assign('common_alert', $fc->getAppSetting('alertMessage'));

        //generali
        $template->assign('common_universibo', 'UniversiBO');
        $template->assignUnicode('common_metaKeywords', 'universibo, università, facoltà, studenti, bologna, professori, lezioni, materiale didattico, didattica, corsi, studio, studi, novità, appunti, dispense, lucidi, esercizi, esami, temi d\'esame, orari lezione, ingegneria, economia, ateneo');
        $template->assign('common_metaDescription', 'Il portale dedicato agli studenti universitari di Bologna');
        $template->assignUnicode('common_title', 'UniversiBO: la community degli studenti dell\'Università di Bologna');
        $template->assign('common_langNewWindow', 'apre una nuova finestra');

        //krono
        $template->assign('common_veryLongDate', $krono->k_date());
        $template->assign('common_longDate', $krono->k_date('%j %F %Y'));
        $template->assign('common_shortDate', $krono->k_date('%j/%m/%Y'));
        $template->assign('common_time', $krono->k_date('%H:%i'));
    }

    /**
     * Inizializza le variabili del template per le pagine con indice completo
     *
     * @private
     */
    public function _initTemplateIndexUniversibo()
    {
    }

    /**
     * Inizializza le variabili del template per le pagine popup
     *
     * @private
     * @todo implementare
     */
    public function _initTemplatePopupUniversibo()
    {
    }

    /**
     * Inizializza le variabili del template per le pagine con indice completo
     *
     * @private
     */
    public function _shutdownTemplateIndexUniversibo()
    {
        $template = $this->frontController->getTemplateEngine();
        $krono = $this->frontController->getKrono();

        $session_user = $this->getSessionUser();

        //informazioni del MyUniversiBO
        $attivaMyUniversibo = false;
        //		var_dump($session_user);

        if (!$session_user->isOspite()) {
            $attivaMyUniversibo = true;
            $arrayCanali = array();
            $arrayRuoli = $session_user->getRuoli();
            //var_dump($session_user);
            $keys = array_keys($arrayRuoli);
            foreach ($keys as $key) {
                $ruolo = & $arrayRuoli[$key];
                if ($ruolo->isMyUniversibo()) {
                    //$attivaMyUniversibo = true;

                    $canale = Canale::retrieveCanale($ruolo->getIdCanale());
                    $myCanali = array();
                    $myCanali['uri'] = $canale->showMe();
                    $myCanali['tipo'] = $canale->getTipoCanale();
                    $myCanali['label'] = ($ruolo->getNome() != '') ? $ruolo->getNome() : $canale->getNomeMyUniversiBO();
                    $myCanali['new'] = ($canale->getUltimaModifica() > $ruolo->getUltimoAccesso()) ? 'true' : 'false';
                    $myCanali['ruolo'] = ($ruolo->isReferente()) ? 'R' : (($ruolo->isModeratore()) ? 'M' : 'none');
                    //var_dump($ruolo);
                    $arrayCanali[] = $myCanali;
                }
            }
            //ordina $arrayCanali
            usort($arrayCanali, array($this, '_compareMyUniversiBO'));
        }

        //assegna al template
        if ($attivaMyUniversibo) {
            $template->assign('common_myLinksAvailable', 'true');
            $template->assign('common_langMyUniversibo', 'My UniversiBO');
            $template->assignUnicode('common_myLinks', $arrayCanali);
        } else {
            $template->assign('common_myLinksAvailable', 'false');
        }

        //solo nella pagine index
        $curr_mday = date("j");  //inizializzo giorno corrente
        $curr_mese = date("n");  //inizializzo mese corrente
        $curr_anno = date("Y");  //inizializzo anno corrente
        $logoType = 'default';
        if ($curr_mese == 8)
            $logoType = 'estate';
        elseif ($curr_mday == 8 && $curr_mese == 3)
        $logoType = '8marzo';
        elseif ($curr_mday == 31 && $curr_mese == 10)
        $logoType = 'halloween';
        elseif ($curr_mday == 14 && $curr_mese == 2)
        $logoType = 'svalentino';
        elseif (($curr_mese == 12 && $curr_mday >= 8) || ($curr_mese == 1 && $curr_mday <= 7))
        $logoType = 'natale';
        elseif ((easter_date($curr_anno) == mktime(0, 0, 0, $curr_mese, $curr_mday, $curr_anno) ) || (easter_date($curr_anno) == mktime(0, 0, 0, $curr_mese, $curr_mday - 1, $curr_anno) ))
        $logoType = 'pasqua';
        elseif (false)
        $logoType = 'carnevale';  //cambiare questa riga a carnevale o trovare il modo per calcolarlo

        $template->assign('common_logoType', $logoType); //estate/natale/8marzo/pasqua/carnevale/svalentino/halloween/ecc...
        $template->assign('common_logo', 'Logo UniversiBO');

        $template->assign('common_setHomepage', 'Imposta Homepage');
        $template->assign('common_addBookmarks', 'Aggiungi ai preferiti');

        $session_user = $this->getSessionUser();
        if ($session_user->isOspite()) {
            $template->assign('common_userLoggedIn', 'false');
        } else {
            $template->assign('common_userLoggedIn', 'true');
            $template->assign('common_userUsername', $session_user->getUsername());
            //$livelli = User::groupsNames();
            $template->assign('common_userLivello', $session_user->getUserGroupsNames());

            $template->assign('common_langWelcomeMsg', 'Benvenuto');
            $template->assignUnicode('common_langUserLivello', 'Il tuo livello di utenza è');
        }

        $template->assign('common_settings', 'Impostazioni personali');
        $template->assign('common_settingsUri', '/?do=ShowSettings');

        $template->assign('common_myUniversiBO', 'ShowMyUniversiBO');
        $template->assign('common_myUniversiBOUri', '/?do=ShowMyUniversiBO');

        $template->assignUnicode('common_fac', 'Facoltà');
        $elenco_facolta = Facolta::selectFacoltaElenco();
        //var_dump($elenco_facolta);

        $num_facolta = count($elenco_facolta);
        $i = 0;
        $session_user = $this->getSessionUser();
        $session_user_groups = $session_user->getGroups();
        $common_facLinks = array();
        for ($i = 0; $i < $num_facolta; $i++) {
            if ($elenco_facolta[$i]->isGroupAllowed($session_user_groups)) {
                $common_facLinks[$i] = array();
                $common_facLinks[$i]['uri'] = '/?do=ShowFacolta&id_canale=' . $elenco_facolta[$i]->getIdCanale();
                $common_facLinks[$i]['label'] = $elenco_facolta[$i]->getNome();
            }
        }
        $template->assign('common_facLinks', $common_facLinks);

        $template->assign('common_services', 'Servizi');
        $common_servicesLinks = array();

        // servizi per i quali l'utente ha i diritti di accesso
        $list_id_canali = Canale::selectCanaliTipo(CANALE_DEFAULT);
        $list_canali = Canale::selectCanali($list_id_canali);
        $keys = array_keys($list_canali);
        foreach ($keys as $key) {
            $my_canale = $list_canali[$key];
            if ($my_canale->isGroupAllowed($session_user_groups)) {

                $myCanali['uri'] = $my_canale->showMe();
                $myCanali['tipo'] = $my_canale->getTipoCanale();
                $myCanali['label'] = $my_canale->getNome();
                //var_dump($ruolo);
                $common_servicesLinks[] = $myCanali;
            }
        }
        if ($session_user->isAdmin() || $session_user->isCollaboratore()) {
            $common_servicesLinks[] = array('uri' => '/?do=ShowContattiDocenti', 'tipo' => '', 'label' => 'Contatto dei docenti');
            $common_servicesLinks[] = array('uri' => '/?do=ShowStatistiche', 'tipo' => '', 'label' => 'Statistiche');
        }

        usort($common_servicesLinks, array($this, '_compareServices'));
        $template->assignUnicode('common_servicesLinks', $common_servicesLinks);


        $template->assign('common_info', 'Informazioni');
        $template->assign('common_help', 'Help');
        $template->assign('common_helpUri', '/?do=ShowHelp');
        $template->assign('common_helpByTopic', 'Help per argomenti');
        $template->assign('common_helpByTopicUri', '/?do=ShowHelpTopic');
        $template->assign('common_rules', 'Regolamento');
        $template->assign('common_rulesUri', '/?do=ShowRules');
        $template->assign('common_contacts', 'Chi siamo');
        $template->assign('common_contactsUri', '/?do=ShowContacts');
        $template->assign('common_contribute', 'Collabora');
        $template->assign('common_contributeUri', '/?do=ShowContribute');
        $template->assign('common_credits', 'Credits');
        $template->assign('common_creditsUri', '/?do=ShowCredits');
        $template->assignUnicode('common_accessibility', 'Accessibilità');
        $template->assign('common_accessibilityUri', '/?do=ShowAccessibility');

        $template->assign('common_manifesto', 'Manifesto');
        $template->assign('common_manifestoUri', '/?do=ShowManifesto');

        $template->assign('common_docSf', 'Documentazione');
        $template->assign('common_docSfUri', 'https://wiki.universibo.unibo.it/');
        //$template->assign('common_project', 'UniversiBO Open Source Project');
        //$template->assign('common_projectUri', 'http://universibo.sourceforge.net/');


        $template->assignUnicode('common_disclaimer', array('Le informazioni contenute nel sito non hanno carattere di ufficialità.',
                'I contenuti sono mantenuti in maniera volontaria dai partecipanti alla comunità di studenti e docenti di UniversiBO. L\'Università di Bologna - Alma Mater Studiorum non può essere considerata legalmente responsabile di alcun contenuto di questo sito.',
                'Ogni marchio citato in queste pagine appartiene al legittimo proprietario.' .
                'Con il contenuto delle pagine appartenenti a questo sito non si è voluto ledere i diritti di nessuno, quindi nel malaugurato caso che questo possa essere avvenuto, vi invitiamo a contattarci affinché le parti in discussione vengano eliminate o chiarite.'));

        $template->assign('common_isSetVisite', 'N');

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
                elseif ($this->_isFestivo($conta_mday, $curr_mese, $curr_anno))
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
        $template->assign('common_calendarWeekDays', array('L', 'M', 'M', 'G', 'V', 'S', 'D'));
        $template->assign('common_calendar', $tpl_mese);
        $template->assign('common_langCalendar', 'Calendario');
        $common_calendarLink = array('label' => $krono->k_date('%F'), 'uri' => '/?do=ShowCalendar&month=' . $krono->k_date('%n'));
        $template->assign('common_calendarLink', $common_calendarLink);
        $template->assign('common_version', $this->frontController->getAppSetting('version'));
    }

    /**
     * Inizializza le variabili del template per le pagine popup
     *
     * @private
     * @todo implementare
     */
    public function _shutdownTemplatePopupUniversibo()
    {
    }

    /**
     * Restituisce se un giorno ? festivo o no
     *
     * @static
     * @private
     * @return boolean
     */
    public function _isFestivo($mday, $mese, $anno)
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

    /**
     * Ordina la struttura del MyUniversiBO
     *
     */
    protected static function _compareMyUniversiBO($a, $b)
    {
        if ($a['tipo'] < $b['tipo'])
            return +1;
        if ($a['tipo'] > $b['tipo'])
            return -1;
        if ($a['label'] < $b['label'])
            return -1;
        if ($a['label'] > $b['label'])
            return +1;
        if ($a['ruolo'] < $b['ruolo'])
            return -1;
        if ($a['ruolo'] > $b['ruolo'])
            return +1;
    }

    /**
     * Ordina la struttura del MyUniversiBO
     */
    protected static function _compareServices($a, $b)
    {
        if ($a['label'] < $b['label'])
            return -1;
        if ($a['label'] > $b['label'])
            return +1;
    }
}
