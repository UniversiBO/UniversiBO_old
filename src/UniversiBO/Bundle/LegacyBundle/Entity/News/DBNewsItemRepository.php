<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\News;

use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;

use UniversiBO\Bundle\LegacyBundle\Entity\User;

use UniversiBO\Bundle\LegacyBundle\Entity\DBRepository;

use \DB;
use \Error;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBNewsItemRepository extends DBRepository
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
    
    public function findMany(array $ids)
    {
        if(count($ids) === 0) {
            return array();
        }
        
        $db = $this->getDb();
        
        array_walk($ids, 'intval');
        $values = implode(',',$ids);
        
        //		$query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, username, data_modifica FROM news A, utente B WHERE A.id_utente = B.id_utente AND id_news IN ('.$values.') AND eliminata!='.$db->quote(NewsItem::ELIMINATA);
        $query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, data_modifica FROM news A WHERE id_news IN ('.$values.') AND eliminata='.$db->quote(NewsItem::NOT_ELIMINATA) . ' ORDER BY data_inserimento DESC';
        //var_dump($query);
        $res = $db->query($query);
        
        if (DB::isError($res)) {
        	$this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }
        
        $rows = $res->numRows();
        
        if( $rows == 0) return false;
        $news_list = array();
        
        while ($res->fetchInto($row)) {
            $userRepository = new DBUserRepository($db);
        	$username = $userRepository->getUsernameFromId($row[6]);
        	
        	$news_list[] = new NewsItem($row[7],$row[0],$row[1],$row[2],$row[3],$row[8],($row[4] == NewsItem::URGENTE),($row[5] == NewsItem::ELIMINATA),$row[6], $username );
        }
        
        $res->free();
        
        return $news_list;
    }
}