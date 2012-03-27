<?php

namespace UniversiBO\Legacy\App;

/**
 * User repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBUserRepository
{
    /**
     * @var \DB_common
     */
    private $db;

    /**
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db)
    {
        $this->db = $db;
    }

    /**
     * Tells if a username exists
     * @param string $username
     * @return boolean
     */
    public function usernameExists($username)
    {
        $username = trim($username);
        $db = $this->db;

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
        $db = $this->db;

        $query = 'SELECT id_utente FROM utente WHERE ad_username = '.$db->quote($adUsername);
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
        $db = $this->db;
        
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
}