<?php

namespace UniversiBO\Bundle\LegacyBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\User\UserProviderInterface;
/**
 * User repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBUserRepository extends DBRepository implements UserProviderInterface
{
    /**
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db)
    {
        parent::__construct($db);
    }

    /**
     * Tells if a username exists
     * @param string $username
     * @return boolean
     */
    public function usernameExists($username)
    {
        $username = trim($username);
        $db = $this->getDb();

        $query = 'SELECT id_utente FROM utente WHERE LOWER(username) = '.$db->quote(strtolower($username));

        $res = $db->query($query);
        if (\DB::isError($res)) {
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->numRows();

        return $rows > 0;
    }

    /**
     * Tells if an active directory username (email) exists
     *
     * @param string $adUsername
     * @return boolean
     */
    public function activeDirectoryUsernameExists($adUsername)
    {
        $db = $this->getDb();

        $query = 'SELECT id_utente FROM utente WHERE sospeso = '.$db->quote('N').' AND ad_username = '.$db->quote($adUsername);
        $res = $db->query($query);

        if (\DB::isError($res)) {
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->numRows();

        if( $rows == 0) return false;
        elseif( $rows == 1) return true;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    /**
     * @param int $id
     * @return string
     */
    public function getUsernameFromId($id)
    {
        $db = $this->getDb();

        $query = 'SELECT username FROM utente WHERE id_utente= '.$db->quote($id);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $res->numRows();
        if( $rows == 0)
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Non esiste un utente con questo id_user: '.$id_user,'file'=>__FILE__,'line'=>__LINE__));
        $res->fetchInto($row);
        $res->free();
        return $row[0];
    }

    public function getIdFromADUsername($adUsername)
    {
        $db = $this->getDb();

        $query = 'SELECT id_utente FROM utente WHERE ad_username = '.$db->quote($adUsername);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $res->numRows();

        if( $rows == 0) return false;
        elseif( $rows == 1)
        {
            $row = $res->fetchRow();
            return $row[0];
        }
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
    }

    /**
     * Insert a user
     *
     * @param User $user
     * @return boolean
     */
    public function insertUser(User $user)
    {
        $db = $this->getDb();
        ignore_user_abort(1);
        $db->autoCommit(false);

        $query = 'SELECT id_utente FROM utente WHERE username = '.$db->quote($user->getUsername());
        $res = $db->query($query);
        $rows = $res->numRows();

        if( $rows > 0)
        {
            $return = false;
        }
        else
        {
            $user->setIdUser($db->nextID('utente_id_utente'));
            $utente_ban = ( $user->isBanned() ) ? 'S' : 'N';
            $utente_eliminato = ( $user->isEliminato() ) ? User::ELIMINATO : User::NOT_ELIMINATO;

            $query = 'INSERT INTO utente (id_utente, username, password, email, notifica, ultimo_login, ad_username, groups, ban, phone, sospeso, default_style, algoritmo, salt) VALUES '.
                    '( '.$db->quote($user->getIdUser()).' , '.
                    $db->quote($user->getUsername()).' , '.
                    $db->quote($user->getPasswordHash()).' , '.
                    $db->quote($user->getEmail()).' , '.
                    $db->quote($user->getLivelloNotifica()).' , '.
                    $db->quote($user->getUltimoLogin()).' , '.
                    $db->quote($user->getADUsername()).' , '.
                    $db->quote($user->getGroups()).' , '.
                    $db->quote($utente_ban).' , '.
                    $db->quote($user->getPhone()).' , '.
                    $db->quote($utente_eliminato).' , '.
                    $db->quote($user->getDefaultStyle()).' , '.
                    $db->quote($user->getAlgoritmo()).' , '.
                    $db->quote($user->getSalt()).' )';
            $res = $db->query($query);

            if (\DB::isError($res))
            {
                $db->rollback();
                \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
            }
            $db->commit();

            $return = true;
        }

        $db->autoCommit(true);
        ignore_user_abort(0);

        return $return;
    }

    public function updateUser(User $user)
    {
        $db = $this->getDb();

        $utente_ban = ( $user->isBanned() ) ? 'S' : 'N';
        $utente_eliminato = ( $user->isEliminato() ) ? User::ELIMINATO : User::NOT_ELIMINATO;

        $query = 'UPDATE utente SET username = '.$db->quote($user->getUsername()).
        ', password = '.$db->quote($user->getPasswordHash()).
        ', email = '.$db->quote($user->getEmail()).
        ', notifica = '.$db->quote($user->getLivelloNotifica()).
        ', ultimo_login = '.$db->quote($user->getUltimoLogin()).
        ', ad_username = '.$db->quote($user->getADUsername()).
        ', groups = '.$db->quote($user->getGroups()).
        ', phone = '.$db->quote($user->getPhone()).
        ', default_style = '.$db->quote($user->getDefaultStyle()).
        ', sospeso = '.$db->quote($utente_eliminato).
        ', ban = '.$db->quote($utente_ban).
        ', algoritmo = '.$db->quote($user->getAlgoritmo()).
        ', salt = '.$db->quote($user->getSalt()).
        ' WHERE id_utente = '.$db->quote($user->getIdUser());

        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
    }

    public function updateEmail(User $user)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente SET email = '.$db->quote($user->getEmail()).' WHERE id_utente = '.$db->quote($user->getIdUser());
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updateUltimoLogin(User $user)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente SET ultimo_login = '.$db->quote($user->getUltimoLogin()).' WHERE id_utente = '.$db->quote($user->getIdUser());
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updateADUsername(User $user)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente SET ad_username = '.$db->quote($user->getADUsername()).' WHERE id_utente = '.$db->quote($user->getIdUser());
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updatePassword(User $user)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente SET password = '.$db->quote($user->getPassword()).
        ', salt = '.$db->quote($user->getSalt()).
        ', algoritmo = '.$db->quote($user->getAlgoritmo()).
        ' WHERE id_utente = '.$db->quote($user->getIdUser());
         
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
    }

    public function findCollaboratori()
    {
        $db = $this->getDb();
        $query = 'SELECT id_utente, groups FROM utente WHERE groups > 2 AND groups!= 8 AND groups != 16 AND groups!= 32 AND sospeso = '.$db->quote(User::NOT_ELIMINATO);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();

        $collaboratori = array();

        while($row = $res->fetchRow())
        {
            $collaboratori[] = new User($row[0], $row[1]);
        }

        return $collaboratori;
    }

    public function find($id)
    {
        $db = $this->getDb();

        $query = 'SELECT username, password, email, ultimo_login, ad_username, groups, notifica, phone, default_style, sospeso, algoritmo, salt  FROM utente WHERE id_utente = '.$db->quote($id);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if( $rows > 1) \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        if( $rows == 0) {
            return false;
        }

        $row = $res->fetchRow();
        $user = new User($id, $row[5], $row[0], $row[1], $row[2], $row[6], $row[3], $row[4], $row[7], $row[8], NULL, $row[9], true);
        $user->setAlgoritmo($row[10]);
        $user->setSalt($row[11]);
        return $user;
    }

    public function findByUsername($username)
    {
        $db = $this->getDb();

        $query = 'SELECT id_utente, password, email, ultimo_login, ad_username, groups, notifica, phone, default_style, sospeso, algoritmo, salt  FROM utente WHERE username = '.$db->quote($username);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if( $rows > 1) \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        if( $rows == 0) {
            $false=false;
            return $false;
        }

        $row = $res->fetchRow();
        $user = new User($row[0], $row[5], $username, $row[1], $row[2], $row[6], $row[3], $row[4], $row[7], $row[8], NULL, $row[9], true);
        $user->setAlgoritmo($row[10]);
        $user->setSalt($row[11]);
        return $user;
    }

    public function getIdUsersFromDesiredGroups(array $arrayWithDesiredGroupsConstant)
    {
        if(count($arrayWithDesiredGroupsConstant) === 0)
            return array();

        $db = $this->getDb();

        $groups = implode(', ', $arrayWithDesiredGroupsConstant);
        $query = 'SELECT id_utente, groups FROM utente WHERE groups IN '.$db->quote($groups);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        while ($row = $res->fetchRow())
            $ret[$row[1]][] = $row[0];

        return $ret;
    }

    public function updateGroups(User $user)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente SET groups = '.$db->quote($user->getGroups()).' WHERE id_utente = '.$db->quote($user->getIdUser());
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else \Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database utenti: username non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function findLike($username = '%', $email = '%')
    {
        $db = $this->getDb();

        $query = 'SELECT id_utente, password, email, ultimo_login, ad_username, groups, notifica, username, phone, default_style, sospeso, algoritmo, salt  FROM utente WHERE username LIKE '.$db->quote($username) .' AND email LIKE '.$db->quote($email);
        $res = $db->query($query);
        if (\DB::isError($res))
            \Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $users = array();

        while($row = $res->fetchRow())
        {
            $users[] = new User($row[0], $row[5], $row[7], $row[1], $row[2], $row[6], $row[3], $row[4], $row[8], $row[9], NULL, $row[10], true);
        }

        return $users;
    }

    public function loadUserByUsername($username)
    {
        return $this->findByUsername($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }
    
    public function delete(User $user)
    {
        $db = $this->getDb();
        
        $query = 'UPDATE utente SET sospeso ='.$db->quote(User::ELIMINATO).' WHERE id_utente ='.$db->quote($user->getIdUser());
        
        $res = $db->query($query);
        if (\DB::isError($res))
        	\Error::throwError(_ERROR_CRITICAL,array('msg'=>\DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        
        $user->setEliminato(true);
    }

    public function supportsClass($class)
    {
        return 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\User' === $class;
    }
}