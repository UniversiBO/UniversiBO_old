<?php
namespace Universibo\Bundle\LegacyBundle\App;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Facolta;
use Universibo\Bundle\LegacyBundle\Framework\BaseCommand;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\WebsiteBundle\UniversiboWebsiteBundle;
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
     * @var Canale
     */
    private $requestCanale;

    /**
     * Inizializza l' UniversiboCommand ridefinisce l'init() del BaseCommand.
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand($frontController);

        $template = $frontController->getTemplateEngine();
        $template->assign('error_notice_present', 'false');

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
        $fc = $this->getFrontController();

        $request_protocol = (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

        // http | https
        $template->assign('common_protocol', $request_protocol);
        // www.universibo.unibo.it
        $template->assign('common_hostName', ( array_key_exists('HTTP_HOST', $_SERVER) ) ? $_SERVER['HTTP_HOST'] : '');
        // https://www.universibo.unibo.it/path_universibo2/
        @ $template->assign('common_rootUrl', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $fc->getRootPath());
        // https://www.universibo.unibo.it/path_universibo2/receiver.php
        @ $template->assign('common_receiverUrl', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
        // https://www.universibo.unibo.it/path_universibo2/receiver.php?du=SomeCommand
        @ $template->assign('common_requestUri', $request_protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        // /path_universibo2/receiver.php?du=SomeCommand
        @ $template->assign('common_shortUri', $_SERVER['REQUEST_URI']);

        $container = $this->getContainer();

        $router = $container->get('router');
        $forumRouter = $container->get('universibo_forum.router');

        $template->assign('common_forum', 'Forum');
        $template->assign('common_forumDir', 'forum/');
        $template->assign('common_forumUri', $forumRouter->getIndexUri());

        $template->assign('common_homepage', 'Homepage');
        $template->assign('common_homepageUri', $router->generate('universibo_legacy_home'));

        $template->assign('common_rootEmail', $fc->getAppSetting('rootEmail'));
        $template->assign('common_infoEmail', $fc->getAppSetting('infoEmail'));
        $template->assign('common_staffEmail', $fc->getAppSetting('staffEmail'));
        $template->assign('common_alert', $container->getParameter('alert_message'));

        //generali
        $template->assign('common_universibo', 'UniversiBO');
        $template->assign('common_metaKeywords', 'universibo, università, facoltà, studenti, bologna, professori, lezioni, materiale didattico, didattica, corsi, studio, studi, novità, appunti, dispense, lucidi, esercizi, esami, temi d\'esame, orari lezione, ingegneria, economia, ateneo');
        $template->assign('common_metaDescription', 'Il portale dedicato agli studenti universitari di Bologna');
        $template->assign('common_title', 'UniversiBO: la community degli studenti dell\'Università di Bologna');
        $template->assign('common_langNewWindow', 'apre una nuova finestra');

        //krono
        $template->assign('common_veryLongDate', $krono->k_date());
        $template->assign('common_longDate', $krono->k_date('%j %F %Y'));
        $template->assign('common_shortDate', $krono->k_date('%j/%m/%Y'));
        $template->assign('common_time', $krono->k_date('%H:%i'));

        $template->assign('common_basePath', $this->getRequest()->getBasePath());
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
        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        $router = $this->get('router');

        $session_user = $this->get('security.context')->getToken()->getUser();

        //informazioni del MyUniversiBO
        $attivaMyUniversibo = false;
        //		var_dump($session_user);

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $attivaMyUniversibo = true;
            $arrayCanali = array();
            $arrayRuoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($session_user->getId());
            //var_dump($session_user);
            $keys = array_keys($arrayRuoli);
            foreach ($keys as $key) {
                $ruolo = $arrayRuoli[$key];
                if ($ruolo->isMyUniversibo()) {
                    //$attivaMyUniversibo = true;

                    $canale = $channelRepo->find($ruolo->getIdCanale());
                    $myCanali = array();
                    $myCanali['uri'] = $channelRouter->generate($canale);
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
            $template->assign('common_myLinks', $arrayCanali);
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

        $headerResponse = $this->forward('UniversiboWebsiteBundle:Common:header');
        $template->assign('common_header', $headerResponse->getContent());

        $template->assign('common_logoType', $logoType); //estate/natale/8marzo/pasqua/carnevale/svalentino/halloween/ecc...
        $template->assign('common_logo', 'Logo UniversiBO');

        $template->assign('common_setHomepage', 'Imposta Homepage');
        $template->assign('common_addBookmarks', 'Aggiungi ai preferiti');

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $template->assign('common_userLoggedIn', 'false');
        } else {
            $template->assign('common_userLoggedIn', 'true');
            $template->assign('common_userUsername', $session_user->getUsername());
            //$livelli = User::groupsNames();

            $role = $this->get('universibo_legacy.translator.role_name')->translate($session_user->getRoles());
            $template->assign('common_userLivello', $role);

            $template->assign('common_langWelcomeMsg', 'Benvenuto');
            $template->assign('common_langUserLivello', 'Il tuo livello di utenza è');
        }

        $template->assign('common_settings', 'Impostazioni personali');
        $template->assign('common_settingsUri', $router->generate('universibo_legacy_settings'));

        $template->assign('common_myUniversiBO', 'ShowMyUniversiBO');
        $template->assign('common_myUniversiBOUri', $router->generate('universibo_legacy_myuniversibo'));

        $template->assign('common_fac', 'Facoltà');
        $elenco_facolta = Facolta::selectFacoltaElenco();
        //var_dump($elenco_facolta);

        $num_facolta = count($elenco_facolta);
        $i = 0;
        $session_user_groups = $session_user instanceof User ? $session_user->getLegacyGroups() : 1;
        $common_facLinks = array();
        for ($i = 0; $i < $num_facolta; $i++) {
            if ($elenco_facolta[$i]->isGroupAllowed($session_user_groups)) {
                $common_facLinks[$i] = array();
                $common_facLinks[$i]['uri'] = $channelRouter->generate($elenco_facolta[$i]);
                $common_facLinks[$i]['label'] = $elenco_facolta[$i]->getNome();
            }
        }
        $template->assign('common_facLinks', $common_facLinks);

        $template->assign('common_services', 'Servizi');
        $common_servicesLinks = array();

        // servizi per i quali l'utente ha i diritti di accesso
        $list_canali = $channelRepo->findManyByType(Canale::CDEFAULT);
        $keys = array_keys($list_canali);
        foreach ($keys as $key) {
            $my_canale = $list_canali[$key];
            if ($my_canale->isGroupAllowed($session_user_groups)) {

                $myCanali['uri'] = $channelRouter->generate($my_canale);
                $myCanali['tipo'] = $my_canale->getTipoCanale();
                $myCanali['label'] = $my_canale->getNome();
                //var_dump($ruolo);
                $common_servicesLinks[] = $myCanali;
            }
        }

        $context = $this->get('security.context');

        if ($context->isGranted('ROLE_ADMIN') || $context->isGranted('ROLE_MODERATOR')) {
            $router = $this->get('router');

            $contactUri = $router->generate('universibo_legacy_contact_professors');
            $statUri = $router->generate('universibo_legacy_stats');

            $common_servicesLinks[] = array('uri' => $contactUri, 'tipo' => '', 'label' => 'Contatto dei docenti');
            $common_servicesLinks[] = array('uri' => $statUri, 'tipo' => '', 'label' => 'Statistiche');
        }

        usort($common_servicesLinks, array($this, '_compareServices'));
        $template->assign('common_servicesLinks', $common_servicesLinks);


        $template->assign('common_info', 'Informazioni');
        $template->assign('common_help', 'Help');
        $template->assign('common_helpUri', $router->generate('universibo_legacy_help'));
        $template->assign('common_helpByTopic', 'Help per argomenti');
        $template->assign('common_helpByTopicUri', $router->generate('universibo_legacy_help_topic'));
        $template->assign('common_rules', 'Regolamento');
        $template->assign('common_rulesUri', $router->generate('universibo_website_rules'));
        $template->assign('common_contacts', 'Chi siamo');
        $template->assign('common_contactsUri', $router->generate('universibo_legacy_contacts'));
        $template->assign('common_contribute', 'Collabora');
        $template->assign('common_contributeUri', $router->generate('universibo_legacy_contribute'));
        $template->assign('common_credits', 'Credits');
        $template->assign('common_creditsUri', $router->generate('universibo_legacy_credits'));
        $template->assign('common_accessibility', 'Accessibilità');
        $template->assign('common_accessibilityUri', $router->generate('universibo_legacy_accessibility'));

        $template->assign('common_manifesto', 'Manifesto');
        $template->assign('common_manifestoUri', $router->generate('universibo_legacy_manifesto'));

        $template->assign('common_docSf', 'Documentazione');
        $template->assign('common_docSfUri', 'https://wiki.universibo.unibo.it/');

        $loginResponse = $this->forward('_universibo_sso.controller.userbox:indexAction');

        $template->assign('common_loginBox', $loginResponse->getContent());
        //$template->assign('common_project', 'UniversiBO Open Source Project');
        //$template->assign('common_projectUri', 'http://universibo.sourceforge.net/');


        $template->assign('common_disclaimer', array('Le informazioni contenute nel sito non hanno carattere di ufficialità.',
                'I contenuti sono mantenuti in maniera volontaria dai partecipanti alla comunità di studenti e docenti di UniversiBO. L\'Università di Bologna - Alma Mater Studiorum non può essere considerata legalmente responsabile di alcun contenuto di questo sito.',
                'Ogni marchio citato in queste pagine appartiene al legittimo proprietario.' .
                'Con il contenuto delle pagine appartenenti a questo sito non si è voluto ledere i diritti di nessuno, quindi nel malaugurato caso che questo possa essere avvenuto, vi invitiamo a contattarci affinché le parti in discussione vengano eliminate o chiarite.'));

        $template->assign('common_isSetVisite', 'N');

        //calendario
        $calendarResponse = $this->forward('UniversiboWebsiteBundle:Common:calendar');
        $template->assign('common_calendarBox', $calendarResponse->getContent());

        $assetsResponse = $this->forward('UniversiboWebsiteBundle:Common:assets');
        $template->assign('common_assets', $assetsResponse->getContent());

        if ('prod' === $this->container->getParameter('kernel.environment')) {
            $analyticsResponse = $this->forward('UniversiboWebsiteBundle:Common:analytics');
            $template->assign('common_analytics', $analyticsResponse->getContent());
        } else {
            $template->assign('common_analytics', '');
        }

        $template->assign('common_version', UniversiboWebsiteBundle::VERSION);
        $template->assign('common_showGoogle', $this->get('kernel')->getEnvironment() === 'prod');
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
     * @return boolean
     */
    private static function _isFestivo($mday, $mese, $anno)
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

    /**
     * Ensures channel
     *
     * @return Canale
     * @throws NotFoundHttpException
     */
    public function getRequestCanale($force = true)
    {
        if (null === $this->requestCanale) {
            $channelId = $this->getRequest()->get('id_canale');

            if ($channelId === null) {
                if ($force) {
                    throw new NotFoundHttpException('No channel ID');
                }

                return null;
            }

            $channelRepo = $this->get('universibo_legacy.repository.canale2');
            $canale = $channelRepo->find($channelId);

            if (!$canale instanceof Canale) {
                throw new NotFoundHttpException('Channel not found');
            }

            $this->requestCanale = $canale;
        }

        return $this->requestCanale;
    }

    protected function throwUnauthorized($message = null)
    {
        throw new AccessDeniedHttpException($message);
    }
}
