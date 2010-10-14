<?php
/**
 * Classe per recuperare le informazioni memorizzate nelle interazioni con gli utenti
 */
class StoredInteractionInformationRetriever
{
	/**
	 * @author Pinto
	 * @static
	 * @param boolean $groupedByCallbackName se true, ritorna un array a due livelli array (callbackName => array(param_name => param_value)); se false array(param_name => param_value)
	 * @return array contiene i valori memorizzati
	 */
	function getInfoFromIdUtente ($idUtente, $nomeInteractiveCommand, $groupedByCallbackName = false) 
	{	
		$db =& FrontController::getDbConnection('main');
		
		$query = 'select id_step from step_log where id_utente = '. $db->quote($idUtente).
				' and nome_classe = '. $db->quote($nomeInteractiveCommand).
				 ' and esito_positivo = '. $db->quote('S'). ' order by data_ultima_interazione desc';
//		var_dump($query); die;
		$res =& $db->query($query);
		if (DB::isError($res))
		{
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
		
		$rows = $res->numRows();		
		if( $rows = 0) return array();
		
		// VERIFY è possibile che la select dia più valori, ovvero che l'utente abbia fatto più volte lo stesso InteractiveCommand? si, se supponiamo 
		// per esempio che un utente debba approvare più informative per la privacy. 
		$row = $res->fetchRow();
		$idStep = $row[0];
		$res->free();
		
		$query = 'select param_name, param_value, callback_name from step_parametri where id_step = '. $db->quote($idStep);
		$res =& $db->query($query);
		if (DB::isError($res))
		{
			Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
		}
		
		$rows = $res->numRows();	
		$list = array();	
		if( $rows = 0) return array();
		
		if ($groupedByCallbackName)
			while($row = $res->fetchRow())
				$list[$row[2]][$row[0]] = $row[1];
		else 
			while($row = $res->fetchRow())
				$list[$row[0]] = $row[1];
		return $list;
	}
	
	/**
	 * @author Pinto
	 * @static
	 * @param boolean $groupedByCallbackName se true, ritorna un array a due livelli array (callbackName => array(param_name => param_value)); se false array(param_name => param_value)
	 * @return array contiene i valori memorizzati
	 */
	function getInfoFromUsernameUtente ($username, $nomeInteractiveCommand, $groupedByCallbackName = false) 
	{	
		$user =& User::selectUserUsername($username);
		return StoredInteractionInformationRetriever::getInfoFromIdUtente($user->getIdUser(), $nomeInteractiveCommand, $groupedByCallbackName);
	}
	
}
?>
