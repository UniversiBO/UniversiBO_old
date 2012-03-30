<?php    
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

use UniversiBO\Bundle\LegacyBundle\App\CanaleCommand;

require_once 'News/NewsItem'.PHP_EXTENSION;

/**
 * NewsAdd: si occupa dell'inserimento di una news in un canale
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class NewsShowCanale extends CanaleCommand {

	function execute() {
		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
				
		$user = & $this->getSessionUser();
		$canale = & $this->getRequestCanale();
		$user_ruoli = & $user->getRuoli();
		$id_canale = $canale->getIdCanale();

		if (!array_key_exists('inizio', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['inizio'] ) || !array_key_exists('qta', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['qta'] ))
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Parametri non validi','file'=>__FILE__,'line'=>__LINE__ ));
		}
		
		$template->assign('NewsShowCanale_addNewsFlag', 'false');
		if (array_key_exists($id_canale, $user_ruoli) || $user->isAdmin())
		{
			if (array_key_exists($id_canale, $user_ruoli))
			{
				$ruolo = $user_ruoli[$id_canale];
				$referente      = $ruolo->isReferente();
				$moderatore     = $ruolo->isModeratore();			
			}
			
			if ( $user->isAdmin() || $referente || $moderatore )
			{
				$template->assign('NewsShowCanale_addNewsFlag', 'true');
				$template->assign('NewsShowCanale_addNews', 'Scrivi nuova notizia');
				$template->assign('NewsShowCanale_addNewsUri', 'index.php?do=NewsAdd&id_canale='.$id_canale);
			}
		}
		
		
		$num_news_canale = $this->getNumNewsCanale($id_canale);
		$quantita = $_GET['qta'];
		$template->assign('NewsShowCanale_numPagineFlag', 'false');
		if ($num_news_canale > $quantita)
		{
			$pages_flag = 'true';
			$num_pagine = ceil($num_news_canale / $quantita);
			$n_pag_list =  array();
			$start = 0;
			for ($i = 1; $i <= $num_pagine; $i++)
			{  
				$n_pag_list[$i] = array('URI' => 'index.php?do=NewsShowCanale&id_canale='.$id_canale.'&inizio='.$start.'&qta='.$quantita, 'current' => ($_GET['inizio'] == $start) ? 'true' : 'false');
				$start 	= $start + $quantita;
			}
			$template->assign('NewsShowCanale_numPagine', $n_pag_list);
			$template->assign('NewsShowCanale_numPagineFlag', 'true');
		}  
		
		$lista_notizie = & $this->getLatestNewsCanale($_GET['inizio'],$quantita,$id_canale);
		$param = array('id_notizie'=> $lista_notizie, 'chk_diritti' => true);
		$this->executePlugin('ShowNews', $param );
		
		$this->executePlugin('ShowTopic', array('reference' => 'newsutenti'));		

		return 'default';
	}
	
	/**
	 * Preleva da database le ultime $num notizie non scadute del canale $id_canale
	 *
	 * @static
	 * @param int $num numero notize da prelevare 
	 * @param int $id_canale identificativo su database del canale
	 * @return array elenco NewsItem , false se non ci sono notizie
	 */
	function getLatestNewsCanale($startNum, $qta, $id_canale)
	{
	 	$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT A.id_news FROM news A, news_canale B 
					WHERE A.id_news = B.id_news AND eliminata!='.$db->quote( NEWS_ELIMINATA ).
					'AND ( data_scadenza IS NULL OR \''.time().'\' < data_scadenza ) AND B.id_canale = '.$db->quote($id_canale).' 
					ORDER BY A.data_inserimento DESC';
		$res = $db->limitQuery($query, $startNum , $qta);
		
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
		//var_dump($id_news_list);
		return $id_news_list;
	}
	
	
	/**
	 * Preleva da database il numero di notizie non scadute del canale $id_canale
	 *
	 * @static
	 * @param int $id_canale identificativo su database del canale
	 * @return int numero notizie
	 */
	function getNumNewsCanale($id_canale)
	{
	 	$db = FrontController::getDbConnection('main');
		
		$query = 'SELECT count(A.id_news) FROM news A, news_canale B 
					WHERE A.id_news = B.id_news AND eliminata!='.$db->quote(NEWS_ELIMINATA).
					'AND ( data_scadenza IS NULL OR \''.time().'\' < data_scadenza ) AND B.id_canale = '.$db->quote($id_canale).'';
		$res = $db->getOne($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_CRITICAL,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
		
		return $res;
	}
}
