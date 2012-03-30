<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\App\Canale;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\App\Files\FileItem;
use UniversiBO\Bundle\LegacyBundle\App\News\NewsItem;

/**
 * ShowMyUniversiBO is an extension of UniversiboCommand class.
 *
 * Mostra la MyUniversiBO dell'utente loggato, con le ultime 5 notizie e 
 * gli ultimi 5 files presenti nei canali da lui aggiunti...
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles 
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowMyUniversiBO extends UniversiboCommand 
{
	function execute()
	{
		
		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		$utente = $this->getSessionUser();
		
		//procedure per ricavare e mostrare le ultime 5 notizie dei canali a cui si ? iscritto...
		
		if($utente->isOspite())
			Error :: throwError(_ERROR_DEFAULT, array('id_utente' => $utente->getIdUser(), 'msg' => 'Non esiste una pagina MyUniversiBO per utenti ospite.
																									 Se sei uno studente registrati cliccando su Registrazione Studenti nel menu di destra.
																									 La sessione potrebbe essere scaduta verifica di aver abilitato i cookie.', 'file' => __FILE__, 'line' => __LINE__));
		
		$arrayIdCanaliNews = array();
		$arrayIdCanaliFiles = array();
		$arrayCanali = array();
		$arrayRuoli = $utente->getRuoli();
		$keys = array_keys($arrayRuoli);
		foreach ($keys as $key)
		{
			$ruolo = $arrayRuoli[$key];
			if ($ruolo->isMyUniversibo())
			{
							
				$canale = Canale::retrieveCanale($ruolo->getIdCanale());
				$arrayCanali[] = $key;
				if ($canale->getServizioNews())
				{
					$id_canale = $canale->getIdCanale();
					$arrayIdCanaliNews[] = $id_canale;
				}
				if ($canale->getServizioFiles())
				{    
					$id_canale = $canale->getIdCanale();
					$arrayIdCanaliFiles[] = $id_canale;
				}
			}
		}
		
		$arrayNewsItems = $this->getLatestNewsCanale(5,$arrayIdCanaliNews);

		$this->executePlugin('ShowMyNews', array('id_notizie'=>$arrayNewsItems,'chk_diritti'=>false));

		$arrayFilesItems = $this->getLatestFileCanale(5,$arrayIdCanaliFiles);
		
		$this->executePlugin('ShowMyFileTitoli', array('files'=>$arrayFilesItems,'chk_diritti'=>false));
		
		$template->assign('showMyScheda','index.php?do=ShowUser&id_utente='.$utente->getIdUser());
//		var_dump($arrayFilesItems);
//		die();
			
	}
	
	/**
	 * Preleva da database il numero di files del canale $id_canale
	 *
	 * @static
	 * @param int $id_canale identificativo su database del canale
	 * @return int $res numero files
	 */
	 
	function getNumFilesCanale($id_canale)
	{
		$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT count(A.id_file) FROM file A, file_canale B 
					WHERE A.id_file = B.id_file AND eliminato!='.$db->quote(FileItem::ELIMINATO).
					'AND B.id_canale = '.$db->quote($id_canale).'';
		$res = $db->getOne($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
		
		return $res;
	}
	
	/**
	 * Preleva da database gli ultimi $num files del canale $id_canale
	 *
	 * @static
	 * @param int $num numero files da prelevare 
	 * @param int $id_canale identificativo su database del canale
	 * @return array elenco FileItem , false se non ci sono notizie
	 */
	
	function getLatestFileCanale($num, $id_canali)
	{ 
		if ( count($id_canali) == 1 ) 
			$values = $id_canali[0];
		elseif ( count($id_canali) == 0 )
		{
			$ret = array();
			return $ret;			
		}
		else 
			$values = implode(',',$id_canali);
	 	
	 	$db = FrontController::getDbConnection('main');
		$query = 'SELECT A.id_file FROM file A, file_canale B 
					WHERE A.id_file = B.id_file AND eliminato!='.$db->quote( FileItem::ELIMINATO ).
					'AND B.id_canale IN ('.$values.')
					ORDER BY A.data_inserimento DESC';
		$res = $db->limitQuery($query, 0 , $num);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		if( $rows = 0) return false;
		
		$id_news_list = array();
	
		while ( $res->fetchInto($row) )
		{
			$id_news_list[]= $row[0];
		}
		
		$res->free();
		$files = FileItem::selectFileItems($id_news_list);
		return $files;
		
	}
	
	/**
	 * Preleva da database le ultime $num notizie non scadute dai canali $id_canali
	 *
	 * @static
	 * @param int $num numero notize da prelevare 
	 * @param array $id_canali contenente gli id_canali, identificativi su database del canale
	 * @return array elenco NewsItem , false se non ci sono notizie
	 */
	
	function getLatestNewsCanale($num, $id_canali)
	{
		if ( count($id_canali) == 1 ) 
			$values = $id_canali[0];
		elseif ( count($id_canali) == 0 )
		{
			$ret = array();
			return $ret;			
		}
		else 
			$values = implode(',',$id_canali);
	 	
	 	$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT A.id_news FROM news A, news_canale B 
					WHERE A.id_news = B.id_news AND eliminata!='.$db->quote( NewsItem::ELIMINATA ).
					'AND ( data_scadenza IS NULL OR \''.time().'\' < data_scadenza ) AND B.id_canale IN ('.$values.') 
					ORDER BY A.data_inserimento DESC';
		$res = $db->limitQuery($query, 0 , $num);
//		var_dump($res);
//		die();
		if (DB::isError($res)) 
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		if( $rows = 0) return false;
		
		$id_news_list = array();
	
		while ( $res->fetchInto($row) )
		{
			$id_news_list[]= $row[0];
		}
		
		$res->free();
		
		return $id_news_list;
		
	}
	
	
}

?>
