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
    
    public function findByCanale($id)
    {
        $db = $this->getDb();
        
        $sql = '';
        $sql .= 'SELECT n.titolo, ';
        $sql .= '       n.notizia, ';
        $sql .= '       n.data_inserimento, ';
        $sql .= '       n.data_scadenza, ';
        $sql .= '       n.flag_urgente, ';
        $sql .= '       n.eliminata, ';
        $sql .= '       u.username, ';
        $sql .= '       n.id_news, ';
        $sql .= '       n.data_modifica, ';
        $sql .= '       n.id_utente ';
        $sql .= '    FROM news n';
        $sql .= '    INNER JOIN news_canale nc';
        $sql .= '        ON nc.id_news = n.id_news';
        $sql .= '    INNER JOIN utente u';
        $sql .= '        ON u.id_utente = n.id_utente';
        $sql .= '    WHERE nc.id_canale = '.$db->quote($id);
        $sql .= '        AND n.eliminata = '.$db->quote(NewsItem::NOT_ELIMINATA);
        $sql .= '        AND n.data_inserimento <= '.$db->quote(time());
        $sql .= '    ORDER BY data_inserimento DESC';
        
        $res = $db->query($sql);
        
        if(DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }
        
        $news = array();
        while($res->fetchInto($row)) {
            $userRepository = new DBUserRepository($db);

            $news[] = new NewsItem($row[7],$row[0],$row[1],$row[2],$row[3],$row[8],($row[4] == NewsItem::URGENTE),($row[5] == NewsItem::ELIMINATA),$row[6], $row[9]);
        }
        
        $res->free();
        
        return $news;
    }
}
