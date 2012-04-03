<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCollaboratoreRepository extends DBRepository
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
    
    public function find($id)
    {
        $db = $this->getDb();
        
        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore WHERE id_utente = '.$db->quote($id);
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        
        $rows = $res->numRows();
        if( $rows == 0) return false;
        
        $row = $res->fetchRow();
        $collaboratore = new Collaboratore($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
        
        return $collaboratore;
    }
    
    public function findAll()
    {
        $db = $this->getDb();
        
        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore';
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        
        $rows = $res->numRows();
        
        $collaboratori = array();
        
        while($row = $res->fetchRow())
        {
        	$collaboratori[] = new Collaboratore($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
        }
        
        return $collaboratori;
    }
    
    public function insert(Collaboratore $collaboratore)
    {
        ignore_user_abort(1);
        $db->autoCommit(false);
        $return = true;
        
        //TODO fare inserimento solo se non già presente
        $query = 'INSERT INTO collaboratore (id_utente, intro, recapito, obiettivi, foto, ruolo) VALUES '.
        		'( '.
        		$db->quote($collaboratore->getIdUtente()).' , '.
        		$db->quote($collaboratore->getIntro()).' , '.
        		$db->quote($collaboratore->getRecapito()).' , '.
        		$db->quote($collaboratore->getObiettivi()).' , '.
        		$db->quote($collaboratore->getFotoFilename()).' , '.
        		$db->quote($collaboratore->getRuolo()).' )';
        
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)){
        	$db->rollback();
        	Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }
        
        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);
    }
}
