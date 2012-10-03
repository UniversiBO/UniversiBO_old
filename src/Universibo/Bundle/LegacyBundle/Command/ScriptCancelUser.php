<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \DB;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\InteractiveCommand\StoredInteractionInformationRetriever;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 *
 * Si occupa della cancellazione di un utente in accordo alla informativa da lui approvata
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptCancelUser extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();

        if (!isset($_GET['username'])) {echo 'devi specificare lo username dell\'utente da cancellare'; die; }
        $nick  = $_GET['username'];
        if (!User::usernameExists($nick)) {echo 'username inesistente'; die; }
        $user = User::selectUserUsername($nick);
        $idUtente = $user->getId();
        $values = StoredInteractionInformationRetriever::getInfoFromIdUtente($idUtente, 'InformativaPrivacyInteractiveCommand', false);

//		// TODO verificare quale informativa ha approvato
//		if (!isset($values['id_info']))
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'impossibile dedurre quale informativa per la privacy ha approvato l\'utente','file'=>__FILE__,'line'=>__LINE__));
        //$idInfo = (isset($values['id_info'])) ? $values['id_info'] : 1;

         echo "al momento cancello bene solo secondo la nuova informativa, per la vecchia son da testare!! commentami per eseguire!\n"; die;
        $idInfo = (isset($values['id_info'])) ? $values['id_info'] : 2; // di default la nuova informativa

        $cancelObj  = new CancellazioneUtente($idInfo);
        $esito = false;
        $esito = $cancelObj->cancellaUtente($idUtente);

        if ($esito) {
            $mail = $fc->getMail();

            $mail->AddAddress($user->getEmail());

            $mail->Subject = "Cancellazione iscrizione UniversiBO";
            $mail->Body = "Ciao \"".$user->getUsername()."\"!!\n".
                 "Il tuo account su UniversiBO e' stato cancellato.\n".
                 "Nel caso volessi effettuare nuovamente l'iscrizione, non sara' possibile\n".
                 "farlo attraverso la normale procedura sul sito, ma puoi contattarci all'indirizzo\n".
                 $fc->getAppSetting('infoEmail')."\n".
                 "Grazie per aver usato UniversiBO!\n\n";
            if (!$mail->Send()) {echo 'Errore Mail'; Error::throwError(_ERROR_DEFAULT,array('msg'=>'Mail non inviata!', 'file'=>__FILE__, 'line'=>__LINE__));}
            echo 'Successo';
        } else
            echo 'Errore';

    }
}

// NB il valore di questa costante deve essere uguale a quella omonima del forum..
define('DELETED', -1);

class CancellazioneUtente
{
    public $db;
    public $valuesDispatch = array(
                '1' => array('sospendiUtente', 'anonimizeForumUser', 'anonimizeUserPosts', 'clearUserTopicWatches', 'clearUserFromBanlist', 'reassignGroupsModeratedByUser' ),
                '2' => array('sospendiUtente')
            );
    public $idInformativa;

    // costruttore
    public function __construct($idInformativa)
    {
        $this->idInformativa = $idInformativa;
        $this->db = FrontController::getDbConnection('main');
    }

    public function cancellaUtente( $idUtente)
    {

        $db = FrontController::getDbConnection('main');
        ignore_user_abort(1);
        $db->beginTransaction();

        foreach ($this->valuesDispatch[$this->idInformativa] as $method) {
            $ret = $this->$method($idUtente);
            if ($ret['esito'] === false) {
                $db->rollback();
                Error::throwError(_ERROR_CRITICAL,array('msg'=>'Si e` verificato un errore: ' . $ret['msg'] ."\n".'Ripristino della situazione iniziale','file'=>__FILE__,'line'=>__LINE__));

                return false;
            }
        }
        $db->commit();
        ignore_user_abort(0);

        return true;
    }

    /* ================ private method ====================*/
    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function sospendiUtente ($idUtente)
    {
        $user = User::selectUser($idUtente);
           if ( $user->hasRole('ROLE_PROFESSOR') || $user->hasRole('ROLE_TUTOR') || $user->hasRole('ROLE_COLLABORATOR') || $user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_STAFF')  ) {
//			$this->db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Spiacente, lo script cancella solo gli studenti','file'=>__FILE__,'line'=>__LINE__));
            return array( 'esito' => false, 'msg' => 'Spiacente, lo script cancella solo gli studenti');
        }
           $user->setEliminato();
           $user->updateUser();

        $query = 'UPDATE utente_canale SET notifica=0 WHERE id_utente='.$this->db->quote($idUtente);
        $res = $this->db->query($query);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));
           return array( 'esito' => true);
    }

    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function anonimizeForumUser ($idUtente)
    {
        // TODO valutare bene cosa settare per user_level e per user_rank
        $query = 'UPDATE phpbb_users SET username = '.$this->db->quote(User::NICK_ELIMINATO).', user_email = \'\', user_icq = \'\', ' .
                'user_occ = \'\', user_from = \'\', user_interests = \'\', user_aim = \'\', user_yim = \'\', user_msnm = \'\' WHERE user_id = '. $this->db->quote($idUtente);
        $res = $this->db->query($query);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));
        return array( 'esito' => true);
    }

    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function anonimizeUserPosts($idUtente)
    {
        $sql = 'UPDATE phpbb_posts
            SET post_username = '.$this->db->quote(User::NICK_ELIMINATO).'
            WHERE poster_id = '.$this->db->quote($idUtente);
        $res = $this->db->query($sql);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));
        return array( 'esito' => true);
    }

//	/**
//	 * @access private
//	 * @return boolean true if ok, false if error
//	 */
//	function anonimizeUserTopic ($idUtente)
//	{
//		$sql = 'UPDATE phpbb_topics '.
//			'SET topic_poster = '.$this->db->quote(User::NICK_ELIMINATO).'   // WARNING!! topic_poster è un id_utente, non uno username!!
//			WHERE topic_poster = '.$this->db->quote($idUtente);
//		$res = $this->db->query($sql);
//		return (  !DB::isError($res) );
//	}

    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function reassignGroupsModeratedByUser($idUtente)
    {
        $sql = 'SELECT group_id, group_name' .
                ' FROM phpbb_groups' .
                ' WHERE group_moderator = '.$this->db->quote($idUtente);
        $res = $this->db->query($sql);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));

        $group_moderator = null;
        while ( false !== ($row_group = $res->fetch())) {
            $group_moderator[$row_group['group_name']] = $row_group['group_id'];
        }

        unset($res);

        if ( count($group_moderator) > 0 )
            foreach ($group_moderator as $groupName => $groupId) {
                $update_moderator_id = implode(', ', $group_moderator);
                $id_user = $this->_getFirstAdminInGroup($groupId);
                $sql = 'UPDATE phpbb_groups' .
                        ' SET group_moderator = ' . $this->db->quote($id_user) .   /* TODO chi metto come moderatore di default?*/
                        ' WHERE group_id = '.  $this->db->quote($groupId);
                $res = $this->db->query($sql);
                if( DB::isError($res) )

                    return array( 'esito' => false, 'msg' => DB::errorMessage($res));

                $res->free();
                echo "\n". 'Nuovo moderatore del gruppo '.$groupName.': '.User::getUsernameFromId($id_user);
            }

        return array( 'esito' => true);
    }

    /**
     * @return integer restituisce l'id di un admin, possibilmente appartenente al gruppo
     */
    public function _getFirstAdminInGroup ($groupId)
    {
        $list = User::getIdsFromDesiredGroups(array('ROLE_ADMIN'));
        $sql = 'SELECT user_id' .
                ' FROM phpbb_user_group' .
                ' WHERE user_id IN '.$this->db->quote(implode(', ', $list['ROLE_ADMIN'])) .
                ' AND group_id ='. $this->db->quote($groupId);
        $res = $this->db->query($sql);

        if ($res->rowCount() == 0) {
            return $list['ROLE_ADMIN'][0];
        }

        return $res->fetchColumn();
    }

    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function clearUserTopicWatches($idUtente)
    {
        $sql = 'DELETE FROM phpbb_topics_watch' .
            ' WHERE user_id = '.$this->db->quote($idUtente);

        $res = $this->db->query($sql);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));
        return array( 'esito' => true);
    }

    /**
     * @access private
     * @return array 'esito' -> esito dell'operazione, 'msg' -> eventuale messaggio di errore
     */
    public function clearUserFromBanlist($idUtente)
    {
        $sql = 'DELETE FROM phpbb_banlist
            WHERE ban_userid = '.$this->db->quote($idUtente);
        $res = $this->db->query($sql);
        if( DB::isError($res) )

            return array( 'esito' => false, 'msg' => DB::errorMessage($res));
        return array( 'esito' => true);
    }


}

//class Informativa1 extends CancellazioneUtenteAbstract
//{
//	// costruttore
//	function Informativa1()
//	{
//
//	}
//
//	// i figli devono fare l'override di questo metodo
//	function cancellaUtente( $idUtente)
//	{
//		$db = FrontController::getDbConnection('main');
//		ignore_user_abort(1);
//        $db->beginTransaction();

/*=============== sito==================================================*/

           // cancellazione dell'utente per quanto riguarda il sito.
//      	$user = User::selectUser($idUtente);
//       	if( $user->hasRole('ROLE_PROFESSOR') || $user->hasRole('ROLE_TUTOR') || $user->hasRole('ROLE_COLLABORATOR') || $this->get('security.context')->isGranted('ROLE_ADMIN') || $user->hasRole('ROLE_STAFF')  )
//		{
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Spiacente, lo script cancella solo gli studenti','file'=>__FILE__,'line'=>__LINE__));
//		}
//       	$user->setEliminato();
//       	$user->updateUser();

/*=============== FORUM==================================================*/

//		// TODO valutare bene cosa settare per user_level e per user_rank
//		$query = 'UPDATE phpbb_users SET username = '.$db->quote(User::NICK_ELIMINATO).', user_email = \'\', user_icq = \'\', ' .
//				'user_occ = \'\', user_from = \'\', user_interests = \'\', user_aim = \'\', user_yim = \'\', user_msnm = \'\' WHERE user_id = '. $db->quote($idUtente);
//		$res = $db->query($query);
//		if(  DB::isError($res) )
//		{
////			var_dump($query); die;
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
//		}

// 		non abbiamo utenti che costituiscono gruppo a sè
//			$sql = 'SELECT g.group_id
//				FROM phpbb_user_group ug, phpbb_groups g
//				WHERE ug.user_id = '.$db->quote($idUtente).'
//					AND g.group_id = ug.group_id
//					AND g.group_single_user = 1';

//		// elimino il nome utente dai post
//		$sql = 'UPDATE phpbb_posts
//			SET post_username = '.$db->quote(User::NICK_ELIMINATO).'
//			WHERE poster_id = '.$db->quote($idUtente);
//		$res = $db->query($sql);
//		if(  DB::isError($res) )
//		{
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not update posts for this user','file'=>__FILE__,'line'=>__LINE__));
//		}

//		// elimino il nome utente dai topic
//			$sql = "UPDATE phpbb_topics
//				SET topic_poster = " . DELETED . "
//				WHERE topic_poster = $user_id";
//			if( !$db->query($sql) )
//			{
//				Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not update topics for this user','file'=>__FILE__,'line'=>__LINE__));
//			}

//			$sql = UPDATE  phpbb_vote_voters . "
//				SET vote_user_id = " . DELETED . "
//				WHERE vote_user_id = $user_id";
//			if( !$db->query($sql) )
//			{
//				Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not update votes for this user','file'=>__FILE__,'line'=>__LINE__));
//			}

//		$sql = 'SELECT group_id' .
//				' FROM phpbb_groups' .
//				' WHERE group_moderator = '.$db->quote($idUtente);
//		$res = $db->query($sql);
//		if( DB::isError($res) )
//		{
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
//		}
//		$group_moderator = null;
//		while ( $row_group = $res->fetch() )
//		{
//			$group_moderator[] = $row_group['group_id'];
//		}
//		$res->free();
//
//		if ( count($group_moderator) )
//		{
//			$update_moderator_id = implode(', ', $group_moderator);
//
//			$sql = 'UPDATE phpbb_groups' .
//					' SET group_moderator = ' . $db->quote('609') .   /* TODO chi metto come moderatore di default?*/
//					' WHERE group_moderator IN ($update_moderator_id)';
//			$res = $db->query($sql);
//			if(  DB::isError($res) )
//			{
//				$db->rollback();
//				Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not update group moderators','file'=>__FILE__,'line'=>__LINE__));
//			}
//		}

//			$sql = "DELETE FROM " . GROUPS_TABLE . "
//				WHERE group_id = " . $row['group_id'];
//			if( !$db->query($sql) )
//			{
//				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
//			}

//			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
//				WHERE group_id = " . $row['group_id'];
//			if( !$db->query($sql) )
//			{
//				message_die(GENERAL_ERROR, 'Could not delete group for this user', '', __LINE__, __FILE__, $sql);
//			}

//		$sql = 'DELETE FROM phpbb_topics_watch' .
//				' WHERE user_id = '.$db->quote($idUtente);
//		$res = $db->query($sql);
//		if (  DB::isError($res) )
//		{
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not delete user from topic watch table','file'=>__FILE__,'line'=>__LINE__));
//		}

//		$sql = 'DELETE FROM phpbb_banlist
//			WHERE ban_userid = '.$db->quote($idUtente);
//		$res = $db->query($sql);
//		if (  DB::isError($res) )
//		{
//			$db->rollback();
//			Error::throwError(_ERROR_CRITICAL,array('msg'=>'Could not delete user from banlist table','file'=>__FILE__,'line'=>__LINE__));
//		}

        //TODO se cancellare i messaggi privati o meno
//			$sql = "SELECT privmsgs_id
//				FROM " . PRIVMSGS_TABLE . "
//				WHERE privmsgs_from_userid = $user_id
//					OR privmsgs_to_userid = $user_id";
//			if ( !($result = $db->query($sql)) )
//			{
//				message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
//			}
//
//			// This little bit of code directly from the private messaging section.
//			while ( $row_privmsgs = $result->fetch() )
//			{
//				$mark_list[] = $row_privmsgs['privmsgs_id'];
//			}
//
//			if ( count($mark_list) )
//			{
//				$delete_sql_id = implode(', ', $mark_list);
//
//				$delete_text_sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
//					WHERE privmsgs_text_id IN ($delete_sql_id)";
//				$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
//					WHERE privmsgs_id IN ($delete_sql_id)";
//
//				if ( !$db->query($delete_sql) )
//				{
//					message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
//				}
//
//				if ( !$db->query($delete_text_sql) )
//				{
//					message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
//				}
//			}

//
//		$db->commit();
//		ignore_user_abort(0);
//
//	}
//}
