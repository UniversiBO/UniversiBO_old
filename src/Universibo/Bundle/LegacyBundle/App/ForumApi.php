<?php
namespace Universibo\Bundle\LegacyBundle\App;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

use \DB;
use \Error;
use \Krono;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Forum\ForumApi as ForumApiInterface;

/**
 * La classe Forum fornisce un'API esterna per le operazioni sul forum PHPBB 2.0.x
 *
 * Rispettando le interfacce messe a disposizione si possono creare
 * API per altri tipi di forum.
 * La classe deve essere instanziata per ovviare al problema delle varibili statiche.
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2003
 */

class ForumApi extends DoctrineRepository implements ForumApiInterface
{

    /**
     * Identificativo di connessione al database da utilizzare
     */
    private $database = 'main';

    /**
     * Prefisso del nome delle tabelle del database
     */
    private $table_prefix = 'phpbb_';

    /**
     * Cartella percorso dell'url del forum
     */
    private $forumPath = 'forum/';

    /**
     * Stile del forum di default - implica la modifica anche nella tabella di config di phpbb
     * @access private
     */
    private $defaultUserStyle = array('unibo' => 1, 'black' => 7);

    /**
     * Ranks e livelli da assegnare agli utenti inizialmente
     */
    private $defaultRanks = array('ROLE_STUDENT' => 0,
            'ROLE_COLLABORATOR' => 9, 'ROLE_TUTOR' => 10,
            'ROLE_PROFESSOR' => 11, 'ROLE_STAFF' => 12, 'ROLE_ADMIN' => 1);

    /**
     * esegue la codifica esadecimale di un ipv4 nel formato separato da punti
     * es: '127.0.0.1' -> '7f000001'
     *
     * @param string codifica separata da punti di un numero ip
     * @return string codifica esadecimale del numero ip
     */
    private static function _encodeIp($dotquad_ip)
    {
        $ip_sep = explode('.', $dotquad_ip);

        return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2],
                $ip_sep[3]);
    }

    /**
     * @return string: id di sessione del forum 'sid=f454e54ea75ae45aef75920b02751ac' altrimenti false
     */
    public function getSidForUri()
    {
        //echo $_SESSION['phpbb_sid'];
        if (session_id() !== '' && array_key_exists('phpbb_sid', $_SESSION)
                && $_SESSION['phpbb_sid'] != '')

            return 'sid=' . $_SESSION['phpbb_sid'];
        return '';
    }

    /**
     * @return string: id di sessione del forum 'sid=f454e54ea75ae45aef75920b02751ac' altrimenti false
     */
    public function getOnlySid()
    {
        $sid = $_SESSION['phpbb_sid'];

        return $sid;
    }

    /**
     * @return string path della cartella in cui si trova il forum
     */
    public function getPath()
    {
        return $this->forumPath;
    }

    /**
     * Esegue il login sul forum, si suppone che la password sia gi? stata controllata.
     * Inserisce le informazioni di sessione e cookie per mantenere traccia dell'utente
     * Se l'opreazione avviene con successo viene impostata nella sessione la variabile 'phpbb_sid'
     *
     * @static
     * @param User Oggetto User che deve effettuare l'accesso al forum
     */
    public function login(User $user)
    {

        //mappa informazioni salvate nei cookie da phpbb2

        //var_dump(unserialize(stripslashes($_COOKIE['phpbb2_data'])));
        // array(2) { ["autologinid"]=> string(0) "" ["userid"]=> string(2) "81" }

        $db = $this->getConnection();

        $query = 'SELECT config_name, config_value FROM ' . $this->table_prefix
                . 'config WHERE config_name IN (' . $db->quote('cookie_path')
                . ',' . $db->quote('cookie_secure') . ','
                . $db->quote('cookie_domain') . ',' . $db->quote('cookie_name')
                . ')';
        $res = $db->query($query);
        $rows = $res->rowCount();
        if ($rows != 4)
            Error::throwError(_ERROR_DEFAULT,
                    array(
                            'msg' => 'Impossibile trovare le informazioni di configurazione del forum',
                            'file' => __FILE__, 'line' => __LINE__));
        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            ${$row[0]} = $row[1];
        }
        //echo $cookie_domain;
        //echo $cookie_path;
        //echo $cookie_secure;

        $phpbb2_cookie = array();
        $phpbb2_cookie['autologinid'] = '';
        $phpbb2_cookie['userid'] = (string) $user->getId();
        $cookie_value = serialize($phpbb2_cookie);

        setcookie($cookie_name . '_data', $cookie_value, time() + 3600,
                $cookie_path, $cookie_domain, $cookie_secure);

        $sid = md5(uniqid(rand(), 1));

        setcookie($cookie_name . '_sid', $sid, time() + 3600, $cookie_path,
                $cookie_domain, $cookie_secure);

        $query = 'INSERT INTO ' . $this->table_prefix
                . 'sessions (session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in) VALUES ('
                . $db->quote($sid) . ', ' . $user->getId() . ', ' . time()
                . ', ' . time() . ', '
                . $db->quote($this->_encodeIp($_SERVER['REMOTE_ADDR']))
                . ', 0, 1)';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $query = 'SELECT user_session_time FROM ' . $this->table_prefix
                . 'users WHERE user_id = ' . $user->getId();
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        $query = 'UPDATE ' . $this->table_prefix
                . 'users SET user_lastvisit = ' . $row[0] . ' WHERE user_id = '
                . $user->getId();
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $_SESSION['phpbb_sid'] = $sid;

    }

    /**
     * Esegue il logout dal forum.
     * Distrugge la sessione e il cookie
     *
     * @static
     */
    public function logout()
    {

        $db = $this->getConnection();

        $query = 'SELECT config_name, config_value FROM ' . $this->table_prefix
                . 'config WHERE config_name IN (' . $db->quote('cookie_path')
                . ',' . $db->quote('cookie_secure') . ','
                . $db->quote('cookie_domain') . ',' . $db->quote('cookie_name')
                . ')';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $rows = $res->rowCount();
        if ($rows != 4)
            Error::throwError(_ERROR_DEFAULT,
                    array(
                            'msg' => 'Impossibile trovare le informazioni di configurazione del forum',
                            'file' => __FILE__, 'line' => __LINE__));
        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            ${$row[0]} = $row[1];
        }

        $cookie_value = '';

        //bug qui: errore nome cookie
        setcookie($cookie_name . '_data', $cookie_value, time() + 3600,
                $cookie_path, $cookie_domain, $cookie_secure);

        setcookie($cookie_name . '_sid', '', time() - 3600, $cookie_path,
                $cookie_domain, $cookie_secure);

        $query = 'DELETE FROM ' . $this->table_prefix
                . 'sessions WHERE session_id = '
                . $db->quote(self::getOnlySid()) . ';';
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $_SESSION['phpbb_sid'] = '';

    }

    /**
     * Crea un nuovo utente sul database del forum dato uno User
     *
     * @todo renderla funzionante anche per utenti che appartengono a pi? gruppi
     * @static
     */
    public function insertUser(User $user, $password = null)
    {

        $db = $this->getConnection();

        $groups = $user->getLegacyGroups();
        if ($groups != User::OSPITE && $groups != 'ROLE_STUDENT'
                && $groups != 'ROLE_COLLABORATOR' && $groups != 'ROLE_TUTOR'
                && $groups != 'ROLE_PROFESSOR' && $groups != 'ROLE_STAFF'
                && $groups != 'ROLE_ADMIN')

            return;
        // @todo renderla funzionante anche per utenti che appartengono a pi? gruppi

        $user_style = $this->defaultUserStyle['unibo'];
        $user_rank = $this->defaultRanks[$groups];
        $user_level = ($this->get('security.context')->isGranted('ROLE_ADMIN')
                == true) ? 1
                : ($user->hasRole('ROLE_COLLABORATOR') == true) ? 2 : 0;

        $krono = new Krono();
        $user_timezone = ($krono->_is_daylight(time()) == true) ? 2 : 1;

        if ($user->hasRole('ROLE_PROFESSOR') || $user->hasRole('ROLE_TUTOR')) {
            $user_notify_pm = 0;
            $user_popup_pm = 0;
        } else {
            $user_notify_pm = 1;
            $user_popup_pm = 1;
        }

        $query = 'INSERT INTO ' . $this->table_prefix
                . 'users (user_id, user_active, username, user_regdate, user_password, user_session_time, user_session_page, user_lastvisit, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_sig_bbcode_uid, user_style, user_aim, user_yim, user_msnm, user_posts, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_emailtime, user_viewemail, user_attachsig, user_allowhtml, user_allowbbcode, user_allowsmile, user_allow_pm, user_allowavatar, user_allow_viewonline, user_rank, user_avatar, user_avatar_type, user_level, user_lang, user_timezone, user_dateformat, user_notify_pm, user_popup_pm, user_notify, user_actkey, user_newpasswd)
    VALUES(' . $db->quote($user->getId()) . ', 1, '
                . $db->quote($user->getUsername()) . ', ' . $db->quote(time())
                . ','
                . $db
                        ->quote(
                                is_null($password) ? $user->getPasswordHash()
                                        : md5($password)) . ', 0, 0, 0,'
                . $db->quote($user->getEmail())
                . ', \'\', \'\', \'\', \'\', \'\', \'\', \'          \', '
                . $user_style
                . ', \'\', \'\', \'\', 0, 0, 0, 0, NULL, 0, 1, 0, 1, 1, 1, 1, 1, '
                . $user_rank . ', \'\', 0, ' . $user_level . ', '
                . $db->quote('italian') . ', ' . $user_timezone . ', '
                . $db->quote('D d M Y G:i') . ', ' . $user_notify_pm . ', '
                . $user_popup_pm . ', 0, \'\', \'\')';

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

    }

    /**
     * Modifca lo stile di un utente sul database del forum dato uno User
     */
    public function updateUserStyle(User $user)
    {

        $db = $this->getConnection();

        $user_style = $this->defaultUserStyle['unibo'];

        $query = 'UPDATE ' . $this->table_prefix . 'users SET user_style = '
                . $db->quote($user_style) . ' WHERE user_id = '
                . $db->quote($user->getId());

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

    }

    public function updatePassword(User $user, $password)
    {
        $db = $this->getConnection();

        $query = 'UPDATE ' . $this->table_prefix . 'users SET user_password = '
                . $db->quote(md5($password)) . ' WHERE user_id = '
                . $db->quote($user->getId());

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
    }

    /**
     * Modifica la email di un utente sul database del forum dato uno User
     *
     * @static
     */
    public function updateUserEmail(User $user)
    {

        $db = $this->getConnection();

        $query = 'UPDATE ' . $this->table_prefix . 'users SET user_email = '
                . $db->quote($user->getEmail()) . ' WHERE user_id = '
                . $db->quote($user->getId());

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

    }

    /**
     * Aggiunge un utente ad un gruppo di moderazione sul database del forum
     *
     * @static
     */
    public function addUserGroup($userId, $group)
    {
        $db = $this->getConnection();

        $query = 'SELECT * FROM ' . $this->table_prefix
                . 'user_group WHERE group_id = ' . $db->quote($group)
                . ' AND user_id = ' . $db->quote($userId);
        $res = $db->query($query);

        if ($res->rowCount() > 0)
            return;

        $query = 'INSERT INTO ' . $this->table_prefix
                . 'user_group (group_id, user_id, user_pending) VALUES ('
                . $db->quote($group) . ', ' . $db->quote($userId) . ', 0)';

        $res = $db->query($query);
    }

    /**
     * Remove un utente ad un gruppo di moderazione sul database del forum
     *
     * @static
     */
    public function removeUserGroup($userId, $group)
    {

        $db = $this->getConnection();

        $query = 'DELETE FROM ' . $this->table_prefix
                . 'user_group WHERE group_id = ?'
                . ' AND user_id = ?';

        $db->executeUpdate($query, array($group, $userId));
    }

    /**
     * @return mixed string: id di sessione del forum 'sid=f454e54ea75ae45aef75920b02751ac' altrimenti false
     */
    public function getMainUri()
    {
        return $this->getPath() . 'index.php?' . self::getSidForUri();
    }

    /**
     * @param  int   $id_forum
     * @return mixed string: id di sessione del forum 'sid=f454e54ea75ae45aef75920b02751ac' altrimenti false
     */
    public function getForumUri($id_forum)
    {
        return $this->getPath() . 'viewforum.php?f=' . $id_forum . '&'
                . self::getSidForUri();
    }

    /**
     *
     * @return
     */
    public function addForumCategory($cat_title, $cat_order)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO ' . $this->table_prefix
                . 'categories ( cat_title, cat_order)
    VALUES ( ' . $db->quote($cat_title) . ', ' . $cat_order
                . ' )';

        $db->executeUpdate($query);

        return $db->lastInsertId($this->table_prefix . 'categories_id_seq');
    }

    /**
     *
     * @return
     */
    public function addForum($title, $desc, $cat_id)
    {
        $db = $this->getConnection();

        $next_id = $this->getMaxForumId() + 1;

        $query = 'INSERT INTO "' . $this->table_prefix
                . 'forums" ("forum_id", "cat_id", "forum_name", "forum_desc", "forum_status", "forum_order",
    "forum_posts", "forum_topics", "forum_last_post_id", "prune_enable", "prune_next", "auth_view", "auth_read",
    "auth_post", "auth_reply", "auth_edit", "auth_delete", "auth_announce", "auth_sticky", "auth_pollcreate",
    "auth_vote", "auth_attachments")
    VALUES (' . $next_id . ',' . $cat_id . ' ,' . $db->quote($title) . ','
                . $db->quote($desc)
                . ', 0, 1, 0, 0, 0, 0, NULL, 0, 0,
    1, 1, 1, 1, 3, 3, 3, 1, 0);';

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        return $next_id;
    }

    /**
     *
     * @return int
     */
    public function addGroup($title, $desc, $id_owner)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO "' . $this->table_prefix
                . 'groups" ( "group_name", "group_type", "group_description", "group_moderator", "group_single_user")
    VALUES (' . $db->quote($title) . ',2 ,'
                . $db->quote($desc) . ' ,' . $id_owner . ' , 0)';

        $res = $db->query($query);

        return $db->lastInsertId($this->table_prefix . 'groups_id_seq');
    }

    /**
     * @return null
     */
    public function addGroupForumPrivilegies($forum_id, $group_id)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO "' . $this->table_prefix
                . 'auth_access" ("forum_id", "auth_view", "auth_read", "auth_post", "auth_reply",
    "auth_edit", "auth_delete", "auth_announce", "auth_sticky", "auth_pollcreate", "auth_attachments", "auth_vote", "auth_mod")
    VALUES (' . $forum_id
                . ', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1)';

        $db->executeUpdate($query);

        return $db->lastInsertId($this->table_prefix . 'groups_id_seq');
    }

    /**
     * Ritorna il massimo id_fourm dal database ...succedaneo dell'utilizzo delle sequenze
     *
     * @return int massimo id_fourm dal database
     */
    public function getMaxForumId()
    {
        $db = $this->getConnection();

        $query = 'SELECT MAX(forum_id) as forum_id FROM ' . $this->table_prefix
                . 'forums';

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        if ($res->rowCount() != 1)
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => 'query max fourm_id non valida',
                            'file' => __FILE__, 'line' => __LINE__));

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        return $row[0];

    }

    /**
     * @return string nuovo nome
     */
    public function addForumInsegnamentoNewYear($forum_id, $anno_accademico)
    {
        $db = $this->getConnection();

        $query = 'SELECT forum_name FROM "' . $this->table_prefix
                . 'forums" WHERE forum_id = ' . $forum_id;

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        $vecchio_nome = $row[0];
        $search = ($anno_accademico - 1) . '/' . $anno_accademico . ' ';
        $replace = ($anno_accademico - 1) . '/' . $anno_accademico . '/'
                . ($anno_accademico + 1) . ' ';
        $nuovo_nome = str_replace($search, $replace, $vecchio_nome);

        $query = 'UPDATE ' . $this->table_prefix . 'forums SET forum_name = '
                . $db->quote($nuovo_nome) . '  WHERE forum_id = ' . $forum_id;

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        return $nuovo_nome;

    }

    /**
     * @param  int   $id_post
     * @return mixed string: id di sessione del forum 'sid=f454e54ea75ae45aef75920b02751ac' altrimenti false
     */
    public function getPostUri($id_post)
    {
        return $this->getPath() . 'viewtopic.php?p=' . $id_post . '&'
                . self::getSidForUri() . '#' . $id_post;
    }

    /**
     *
     * @param user $user
     * @param	int   id del forum di cui controllare i messaggi nuovi. se passato 0 vengono
     * cercati gli ultimi post su tutto il forum a cui l'utente ha diritto di accesso
     * @return mixed array di array( id degli ultimi messaggi del forum, nome topic) , false se nessun messaggio nuovo
     */
    public function getLastPostsForum(User $user, $id_forum, $num = 10)
    {
        // teoricamente se uno ha accesso al canale ha anche accesso al forum
        // controllo post più recenti dell'ultimo accesso

        $db = $this->getConnection();

        $ultimo_login = ($user->getLastLogin()->getTimestamp() == null
                || $user->getLastLogin()->getTimestamp() == '') ? 0
                : $user->getLastLogin()->getTimestamp();

        $qb = $db->createQueryBuilder();

        $query = $qb
            ->select('t.topic_title, MIN(p.post_id)')
            ->from($this->table_prefix.'posts', 'p')
            ->from($this->table_prefix.'topics', 't')
            ->andWhere('t.topic_id = p.topic_id')
            ->andWhere('p.forum_id = ?')
            ->andWhere('p.post_id IN ('.
                $qb->select('pp.post_id')
                   ->from($this->table_prefix.'posts', 'pp')
                   ->where('t.topic_id = pp.topic_id').')'
            )
            ->groupBy('t.topic_title')
            ->orderBy('MAX(p.post_id)', 'DESC')
            ->setMaxResults($num)
        ;


        $res = $db->executeQuery($query, array($id_forum));
        $rows = $res->rowCount();

        if ($rows == 0) {
            $false = false;

            return $false;
        }

        $id_post_list = array();

        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            $id_post_list[] = array('id' => $row[1], 'name' => $row[0]);
        }

        $res->free();

        return $id_post_list;
    }

    //getLastNPostsForum ?
    //getLastPosts ?
    //getLastNPosts ?

    /**
     *
     * @author Pinto
     *
     */

}
