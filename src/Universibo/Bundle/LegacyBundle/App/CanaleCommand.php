<?php
namespace Universibo\Bundle\LegacyBundle\App;

use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;

use Universibo\Bundle\WebsiteBundle\Entity\User;

use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use \Error;
/**
 * CanaleCommand ? la superclasse astratta di tutti i command che utilizzando un oggetto Canale
 *
 * Un Canale ? una pagina dinamica con a disposizione il collegamento
 * verso i vari servizi tramite un indentificativo, gestisce i diritti di
 * accesso per i diversi gruppi e diritti particolari 'ruoli' per alcuni utenti,
 * fornisce sistemi di notifica e per assegnare un nome ad un canale
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 * @copyright CopyLeft UniversiBO 2001-2003
 */
abstract class CanaleCommand extends UniversiboCommand
{
    /**
     * @var Canale
     */
    private $requestCanale;

    /**
     * Restituisce l'id_canale corrente, se non ? specificato nella richiesta HTTP-GET si considera
     * default l'homepage id_canale = 1
     *
     * @static
     * @return int
     */
    public function getRequestIdCanale()
    {
        return intval($this->getRequest()->attributes->get('id_canale', 1));
    }

    /**
     * Restituisce l'oggetto canale della richiesta web corrente
     *
     * @return Canale
     */
    public function getRequestCanale()
    {
        return $this->requestCanale;
    }


    /**
     * Inizializza il CanaleCommand ridefinisce l'init() dell'UniversiboCommand.
     */
    public function initCommand(FrontController $frontController)
    {
        parent::initCommand( $frontController );

        $this->_setUpCanaleCanale();

        $this->_setUpTemplateCanale();
    }


    /**
     * Inizializza le informazioni del canale di CanaleCommand
     * Esegue il dispatch inizializzndo il corretto sottotipo di 'canale'
     *
     * @private
     */
    public function _setUpCanaleCanale()
    {

        $this->requestCanale = Canale::retrieveCanale($this->getRequestIdCanale());

        //$this->requestCanale = $class_name::factoryCanale( $this->getRequestIdCanale() );

        if ( $this->requestCanale === false )
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getId(), 'msg'=>'Il canale richiesto non è presente','file'=>__FILE__,'line'=>__LINE__));

        $canale = $this->getRequestCanale();
        $user = $this->get('security.context')->getToken()->getUser();
        $groups  = $user instanceof User ? $user->getLegacyGroups(): 1;

        if (!$canale->isGroupAllowed($groups)) {
            throw new \Exception('Not allowed, '.$canale->getIdCanale());
        }

        $canale->addVisite();
    }

    /**
     * Inizializza le informazioni del canale di CanaleCommand
     *
     * @private
     */
    public function _setUpTemplateCanale()
    {

        $template = $this->frontController->getTemplateEngine();
        $router = $this->get('router');

        //var_dump($template);
        $canale = $this->getRequestCanale();
        $id_canale = $this->getRequestIdCanale();
        $user = $this->get('security.context')->getToken()->getUser();

        $template->assign( 'common_canaleMyUniversiBO', '');
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user_ruoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId());

            if (array_key_exists($id_canale, $user_ruoli) && $user_ruoli[$id_canale]->isMyUniversiBO()) {
                $template->assign( 'common_canaleMyUniversiBO', 'remove');
                $template->assign( 'common_langCanaleMyUniversiBO', 'Rimuovi questa pagina da MyUniversiBO');
                $template->assign( 'common_canaleMyUniversiBOUri', $router->generate('universibo_legacy_myuniversibo_remove', array('id_canale' => $canale->getIdCanale())));
            } else {
                $template->assign( 'common_canaleMyUniversiBO', 'add');
                $template->assign( 'common_langCanaleMyUniversiBO', 'Aggiungi questa pagina a MyUniversiBO');
                $template->assign( 'common_canaleMyUniversiBOUri', $router->generate('universibo_legacy_myuniversibo_add', array('id_canale' => $canale->getIdCanale())));
            }
        } else
            $template->assign( 'common_langCanaleMyUniversiBO', '');

        $template->assign( 'common_isSetVisite', 'true' );
        $template->assign( 'common_visite', $canale->getVisite() );
        $template->assign( 'common_langCanaleNome', $canale->getNome());
        $template->assign( 'common_canaleURI', $canale->showMe($router));

        if ($canale->getTipoCanale() != CANALE_HOME) {
            $template->assign('common_title', 'UniversiBO: '.$canale->getTitolo());
        }

    }

    /**
     * Imposta l'ultimo accesso dell'utente al canale
     *
     * @return boolean true se avvenuta con successo
     */
    public function updateUltimoAccesso()
    {
        $id_canale = $this->getRequestIdCanale();
        $user = $this->get('security.context')->getToken()->getUser();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        if (array_key_exists($id_canale, $user_ruoli)) {
            $user_ruoli[$id_canale]->updateUltimoAccesso(time(), true);
        }
    }

    /**
     * Chiude il CanaleCommand ridefinisce lo shutdownCommand() dell'UniversiboCommand.
     */
    public function shutdownCommand()
    {
        $router = $this->get('router');

        if (!$this->isPopup()) {
            $template = $this->frontController->getTemplateEngine();
            $canale = $this->getRequestCanale();
            $user = $this->get('security.context')->getToken()->getUser();
            $userId = $user instanceof User ? $user->getId() : 0;

            //informazioni del menu contatti
            $attivaContatti = $this->get('security.context')->isGranted('ROLE_ADMIN');

            $attivaModificaDiritti = $this->get('security.context')->isGranted('ROLE_ADMIN');

            $arrayPublicUsers = array();
            $arrayRuoli = $canale->getRuoli();
            //var_dump($arrayRuoli);
            $keys = array_keys($arrayRuoli);
            foreach ($keys as $key) {
                $ruolo = $arrayRuoli[$key];
                //var_dump($ruolo);
                if ($ruolo->isReferente() || $ruolo->isModeratore()) {
                    $attivaContatti = true;

                    if ($ruolo->isReferente() && $ruolo->getId() == $userId)
                        $attivaModificaDiritti = true;

                    $user_temp = $this->get('universibo_website.repository.user')->find($ruolo->getId());
                    //var_dump($user);
                    $contactUser = array();

                    $contactUser['utente_link']  = $router->generate('universibo_legacy_user', array('id_utente'=>$user_temp->getId()));
                    $contactUser['nome']  = LegacyRoles::$map['plural'][$user_temp->getLegacyGroups()];
                    $contactUser['label'] = $user_temp->getUsername();
                    $contactUser['ruolo'] = ($ruolo->isReferente()) ? 'R' :  (($ruolo->isModeratore()) ? 'M' : 'none');
                    //var_dump($ruolo);
                    //$arrayUsers[] = $contactUser;
                    $arrayPublicUsers[$contactUser['nome']][] = $contactUser;
                }
            }
            //ordina $arrayCanali
            //usort($arrayUsers, array('CanaleCommand','_compareMyUniversiBO'));

            //assegna al template
            if ($attivaContatti) {
                //var_dump($arrayPublicUsers);
                uksort($arrayPublicUsers, array($this,'_compareContattiKeys'));
                //var_dump($arrayPublicUsers);

                $template->assign('common_contactsCanaleAvailable', 'true');
                $template->assign('common_langContactsCanale', 'Contatti');
                //$template->assign('common_contactsCanale', $arrayUsers);
                $template->assign('common_contactsCanale', $arrayPublicUsers);
                $contactsEditUri = $router->generate('universibo_legacy_role_admin_search', array('id_canale' => $canale->getIdCanale()));
                $template->assign('common_contactsEdit', array('label' => 'Modifica diritti', 'uri' => $contactsEditUri));
                $template->assign('common_contactsEditAvailable', ($attivaModificaDiritti) ? 'true' : 'false');

                $template->assign('common_langLinksCanale', 'Links');
            }

            //$template->assign('common_contactsCanaleAvailable', 'false');

            // elenco post nuovi contestuale al canale
            if ($this->requestCanale->getServizioForum()) {
                //				$newposts = 'false';
                $list_post		=	array();
                if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
                    $fa = $this->getContainer()->get('universibo_legacy.forum.api');
                    $id_posts_list 	=  $fa->getLastPostsForum($user, $canale->getForumForumId());

                    if ($id_posts_list != false) {
                        //						$newposts = 'true';
                        foreach ($id_posts_list as $curr_post) {
                            $list_post[]= array('URI' => $fa->getPostUri($curr_post['id']), 'desc' => $curr_post['name']);
                        }
                    }
                }
                //				$template->assign( 'common_newPostsAvailable', $newposts);
                $template->assign( 'common_newPostsAvailable', 'true');
                $template->assign( 'common_newPostsList', $list_post);
            }
        }

        $this->updateUltimoAccesso();

        parent::shutdownCommand();
    }

    public function _compareContattiKeys($b, $a)
    {
        //		$theArrayOrder = array ('Docenti'=>'','Personale'=>'','Tutor'=>'','Studenti'=>'');
        //		$posA = CanaleCommand::_keyPosInArray($a,$theArrayOrder);
        //		$posB = CanaleCommand::_keyPosInArray($b,$theArrayOrder);
        //		if ($posA==$posB) return 0;
        //		return ($posA > $posB) ? 1 : -1;
        switch ($a) {
            case 'Docenti': $posA = 0; break;
            case 'Personale': $posA = 1; break;
            case 'Tutor': $posA = 2; break;
            case 'Studenti': $posA = 3; break;
        }
        switch ($b) {
            case 'Docenti': $posB = 0; break;
            case 'Personale': $posB = 1; break;
            case 'Tutor': $posB = 2; break;
            case 'Studenti': $posB = 3; break;
        }
        if ($posA==$posB)
            return strcmp($b["label"], $a["label"]);
        return ($posA < $posB) ? 1 : -1;
    }

    //where is my key in my array
    //	function _keyPosInArray($key,$array)
    //	{
    //		$i=0;
    //		reset($array);
    //		while (current($array)) {
    //			$i++;
    //			if (key($array) == $key) {
    //				return $i;
    //			}
    //			next($array);
    //		}
    //		return $i + 1;
    //	}
}
