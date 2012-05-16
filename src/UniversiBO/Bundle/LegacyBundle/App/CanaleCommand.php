<?php
namespace UniversiBO\Bundle\LegacyBundle\App;


use UniversiBO\Bundle\LegacyBundle\Entity\User;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
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
     * @private
     */
    var $requestCanale;

    /**
     * Restituisce l'id_canale corrente, se non ? specificato nella richiesta HTTP-GET si considera
     * default l'homepage id_canale = 1
     *
     * @static
     * @return int
     */
    function getRequestIdCanale()
    {
        if (!array_key_exists('id_canale', $_GET ) )
        {
            if ($this->frontController->getCommandRequest() == 'ShowHome') return 1;
            else Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>'il parametro id_canale non � specificato nella richiesta','file'=>__FILE__,'line'=>__LINE__));
        }

        if (!preg_match('/^([0-9]+)$/', $_GET['id_canale'] ) )
        {
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>'il parametro id_canale � sintatticamente non valido','file'=>__FILE__,'line'=>__LINE__));
        }

        return intval($_GET['id_canale']);
    }


    /**
     * Restituisce l'oggetto canale della richiesta web corrente
     *
     * @return Canale
     */
    function getRequestCanale()
    {
        return $this->requestCanale;
    }


    /**
     * Inizializza il CanaleCommand ridefinisce l'init() dell'UniversiboCommand.
     */
    function initCommand(FrontController $frontController)
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
    function _setUpCanaleCanale()
    {

        $this->requestCanale = Canale::retrieveCanale($this->getRequestIdCanale());

        //$this->requestCanale = $class_name::factoryCanale( $this->getRequestIdCanale() );

        if ( $this->requestCanale === false )
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>'Il canale richiesto non � presente','file'=>__FILE__,'line'=>__LINE__));

        $canale = $this->getRequestCanale();
        $user = $this->getSessionUser();

        if ( ! $canale->isGroupAllowed( $user->getGroups() ) )
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>'Non ti � permesso l\'accesso al canale selezionato, la sessione potrebbe essere scaduta','file'=>__FILE__,'line'=>__LINE__ ) );

        $canale->addVisite();

    }



    /**
     * Inizializza le informazioni del canale di CanaleCommand
     *
     * @private
     */
    function _setUpTemplateCanale()
    {

        $template = $this->frontController->getTemplateEngine();
        //var_dump($template);
        $canale = $this->getRequestCanale();
        $id_canale = $this->getRequestIdCanale();
        $user = $this->getSessionUser();

        $template->assign( 'common_canaleMyUniversiBO', '');
        if(!$user->isOspite())
        {
            $user_ruoli = $user->getRuoli();
            if (array_key_exists($id_canale, $user_ruoli) && $user_ruoli[$id_canale]->isMyUniversiBO())
            {
                $template->assign( 'common_canaleMyUniversiBO', 'remove');
                $template->assign( 'common_langCanaleMyUniversiBO', 'Rimuovi questa pagina da MyUniversiBO');
                $template->assign( 'common_canaleMyUniversiBOUri', 'v2.php?do=MyUniversiBORemove&id_canale='.$canale->getIdCanale());
            }
            else
            {
                $template->assign( 'common_canaleMyUniversiBO', 'add');
                $template->assign( 'common_langCanaleMyUniversiBO', 'Aggiungi questa pagina a MyUniversiBO');
                $template->assign( 'common_canaleMyUniversiBOUri', 'v2.php?do=MyUniversiBOAdd&id_canale='.$canale->getIdCanale());
            }
        }
        else
            $template->assign( 'common_langCanaleMyUniversiBO', '');

        $template->assign( 'common_isSetVisite', 'true' );
        $template->assign( 'common_visite', $canale->getVisite() );
        $template->assign( 'common_langCanaleNome', $canale->getNome());
        $template->assign( 'common_canaleURI', $canale->showMe());

        if($canale->getTipoCanale() != CANALE_HOME)
            $template->assign('common_title', 'UniversiBO: '.$canale->getTitolo());

    }


    /**
     * Imposta l'ultimo accesso dell'utente al canale
     *
     * @return boolean true se avvenuta con successo
     */
    function updateUltimoAccesso()
    {
        $id_canale = $this->getRequestIdCanale();
        $user = $this->getSessionUser();
        $user_ruoli = $user->getRuoli();

        if (array_key_exists($id_canale, $user_ruoli))
        {
            $user_ruoli[$id_canale]->updateUltimoAccesso(time(), true);
        }
    }


    /**
     * Chiude il CanaleCommand ridefinisce lo shutdownCommand() dell'UniversiboCommand.
     */
    function shutdownCommand()
    {


        if (!$this->isPopup())
        {
            $template = $this->frontController->getTemplateEngine();
            $canale = $this->getRequestCanale();
            $user = $this->getSessionUser();

            //informazioni del menu contatti
            $attivaContatti = $user->isAdmin();

            $attivaModificaDiritti = $user->isAdmin();

            $arrayPublicUsers = array();
            $arrayRuoli = $canale->getRuoli();
            //var_dump($arrayRuoli);
            $keys = array_keys($arrayRuoli);
            foreach ($keys as $key)
            {
                $ruolo = $arrayRuoli[$key];
                //var_dump($ruolo);
                if ($ruolo->isReferente() || $ruolo->isModeratore())
                {
                    $attivaContatti = true;

                    if ($ruolo->isReferente() && $ruolo->getIdUser() == $user->getIdUser())
                        $attivaModificaDiritti = true;

                    $user_temp = User::selectUser($ruolo->getIdUser());
                    //var_dump($user);
                    $contactUser = array();
                    $contactUser['utente_link']  = 'v2.php?do=ShowUser&id_utente='.$user_temp->getIdUser();
                    $contactUser['nome']  = $user_temp->getUserPublicGroupName();
                    $contactUser['label'] = $user_temp->getUsername();
                    $contactUser['ruolo'] = ($ruolo->isReferente()) ? 'R' :  (($ruolo->isModeratore()) ? 'M' : 'none');
                    //var_dump($ruolo);
                    //$arrayUsers[] = $contactUser;
                    $arrayPublicUsers[$user_temp->getUserPublicGroupName(false)][] = $contactUser;
                }
            }
            //ordina $arrayCanali
            //usort($arrayUsers, array('CanaleCommand','_compareMyUniversiBO'));

            //assegna al template
            if ($attivaContatti)
            {
                //var_dump($arrayPublicUsers);
                uksort($arrayPublicUsers, array($this,'_compareContattiKeys'));
                //var_dump($arrayPublicUsers);

                $template->assign('common_contactsCanaleAvailable', 'true');
                $template->assign('common_langContactsCanale', 'Contatti');
                //$template->assign('common_contactsCanale', $arrayUsers);
                $template->assign('common_contactsCanale', $arrayPublicUsers);
                $template->assign('common_contactsEdit', array('label' => 'Modifica diritti', 'uri' => 'v2.php?do=RuoliAdminSearch&id_canale='.$canale->getIdCanale() ) ) ;
                $template->assign('common_contactsEditAvailable', ($attivaModificaDiritti) ? 'true' : 'false');

                $template->assign('common_langLinksCanale', 'Links');
            }


            //$template->assign('common_contactsCanaleAvailable', 'false');

            // elenco post nuovi contestuale al canale
            if ($this->requestCanale->getServizioForum())
            {
                //				$newposts = 'false';
                $list_post		=	array();
                if (!$user->isOspite())
                {
                    $fa = new ForumApi();
                    $id_posts_list 	=  $fa->getLastPostsForum($user, $canale->getForumForumId());

                    if ($id_posts_list != false)
                    {
                        //						$newposts = 'true';
                        foreach ($id_posts_list as $curr_post)
                        {
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




    function _compareContattiKeys($b, $a)
    {
        //		$theArrayOrder = array ('Docenti'=>'','Personale'=>'','Tutor'=>'','Studenti'=>'');
        //		$posA = CanaleCommand::_keyPosInArray($a,$theArrayOrder);
        //		$posB = CanaleCommand::_keyPosInArray($b,$theArrayOrder);
        //		if ($posA==$posB) return 0;
        //		return ($posA > $posB) ? 1 : -1;
        switch ($a)
        {
            case 'Docenti': $posA = 0; break;
            case 'Personale': $posA = 1; break;
            case 'Tutor': $posA = 2; break;
            case 'Studenti': $posA = 3; break;
        }
        switch ($b)
        {
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
    //		while(current($array)){
    //			$i++;
    //			if(key($array) == $key){
    //				return $i;
    //			}
    //			next($array);
    //		}
    //		return $i + 1;
    //	}
}
