<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;

/**
 * Ruolo repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBRuoloRepository extends DBRepository
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

    public function delete(Ruolo $ruolo)
    {
        $db = $this->getDb();
        $query = 'DELETE FROM utente_canale WHERE id_utente = '.$db->quote($ruolo->getIdUtente()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        return true;
    }

    public function insert(Ruolo $ruolo)
    {
        $db = $this->getDb();

        $campo_ruolo = ($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0 + ($ruolo->isReferente()) ? Ruolo::REFERENTE : 0;
        $my_universibo = ($ruolo->isMyUniversibo()) ? 'S' : 'N';
        $nascosto = ($ruolo->isNascosto()) ? 'S' : 'N';

        $query = 'INSERT INTO utente_canale(id_utente, id_canale, ultimo_accesso, ruolo, my_universibo, notifica, nome, nascosto) VALUES ( '.
                $db->quote($ruolo->id_utente).' , '.
                $db->quote($ruolo->id_canale).' , '.
                $db->quote($ruolo->ultimoAccesso).' , '.
                $db->quote($campo_ruolo).' , '.
                $db->quote($my_universibo).' , '.
                $db->quote($ruolo->getTipoNotifica()).' , '.
                $db->quote($ruolo->getNome()).' , '.
                $db->quote($nascosto).' )';

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        return true;
    }

    public function updateNome(Ruolo $ruolo)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente_canale SET nome = '.$db->quote($ruolo->getNome()).' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updateUltimoAccesso(Ruolo $ruolo)
    {
        $db = $this->getDb();
         
        $query = 'UPDATE utente_canale SET ultimo_accesso = '.$db->quote($ruolo->getUltimoAccesso()).' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();
         
        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updateTipoNotifica(Ruolo $ruolo)
    {
        $db = $this->getDb();

        $query = 'UPDATE utente_canale SET tipo_notifica = '.$db->quote($ruolo->getTipoNotifica()).' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function updateModeratore(Ruolo $ruolo)
    {
        $db = $this->getDb();

        $campo_ruolo = ($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0 + ($ruolo->isReferente()) ? Ruolo::REFERENTE : 0;
        $query = 'UPDATE utente_canale SET ruolo = '.$campo_ruolo.' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }
    
    public function updateReferente(Ruolo $ruolo)
    {
        $db = $this->getDb();
        
        $campo_ruolo = (($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0) + (($ruolo->isReferente()) ? Ruolo::REFERENTE : 0);
        $query = 'UPDATE utente_canale SET ruolo = '.$campo_ruolo.' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();
        
        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }
    
    public function updateMyUniversibo(Ruolo $ruolo)
    {
        $db = $this->getDb();
        
        $my_universibo = ($ruolo->isMyUniversibo()) ? 'S' : 'N';
         
        $query = 'UPDATE utente_canale SET my_universibo = '.$db->quote($my_universibo).' WHERE id_utente = '.$db->quote($ruolo->getIdUser()).' AND id_canale = '.$db->quote($ruolo->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();
        
        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: ruolo non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }
}