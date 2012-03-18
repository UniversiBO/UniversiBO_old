<?php

require_once('User'.PHP_EXTENSION);
require_once('Docente'.PHP_EXTENSION);
require_once('UniversiboCommand'.PHP_EXTENSION);

/**
 *Questa classe consente la visualizzazione e la possibile modifica
 *dei dati di un utente.
 *@author Daniele Tiles
 */

class ShowUser extends UniversiboCommand{

	function execute()
	{
		$frontcontroller 	= $this->getFrontController();
		$template			= $frontcontroller->getTemplateEngine();
		$current_user		= $this->getSessionUser();
		
		if (!array_key_exists('id_utente', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_utente'] )  )
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'id dell\'utente richiesto non è valido','file'=>__FILE__,'line'=>__LINE__ ));
		}
		$id_user			=  $_GET['id_utente'];
		$user				= User::selectUser($id_user);
		
		if($current_user->isOspite())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $current_user->getIdUser(), 'msg'=>'Le schede degli utenti sono visualizzabili solo se si è registrati','file'=>__FILE__,'line'=>__LINE__));
		}
		
		if(!$user || $user->isEliminato())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $current_user->getIdUser(), 'msg'=>'L\'utente cercato non è valido','file'=>__FILE__,'line'=>__LINE__));
		}
		
		
		if(!$current_user->isAdmin() && !$user->isDocente() && !$user->isTutor()  && $current_user->getIdUser() != $user->getIdUser())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $current_user->getIdUser(), 'msg'=>'Non ti è permesso visualizzare la scheda dell\'utente','file'=>__FILE__,'line'=>__LINE__));
		}
		
		$arrayRuoli	= $user->getRuoli();
		$canali = array();
		$arrayCanali = array();
		$keys = array_keys($arrayRuoli);
		foreach ($keys as $key)
		{
			$ruolo = $arrayRuoli[$key];
			if ($ruolo->isMyUniversibo())
			{
				$canale = Canale::retrieveCanale($ruolo->getIdCanale());
				if ($canale->isGroupAllowed($current_user->getGroups()))
				{
					$canali = array();
					$canali['uri']   = $canale->showMe();
					$canali['tipo']  = $canale->getTipoCanale();
					$canali['label'] = ($canale->getNome() != '') ? $canale->getNome() : $canale->getNomeMyUniversibo();
					$canali['ruolo'] = ($ruolo->isReferente()) ? 'R' :  (($ruolo->isModeratore()) ? 'M' : 'none');
					$canali['modifica']	= 'index.php?do=MyUniversiBOEdit&id_canale='.$ruolo->getIdCanale();
					$canali['rimuovi']	= 'index.php?do=MyUniversiBORemove&id_canale='.$ruolo->getIdCanale();
					$arrayCanali[] = $canali;
				}
			}
		}
		usort($arrayCanali, array('UniversiboCommand','_compareMyUniversiBO'));
		$email = $user->getEmail();
		$template->assign('showUserLivelli', implode(', ',$user->getUserGroupsNames()));
						
		$template->assign('showUserNickname',$user->getUsername());
		$template->assign('showUserEmail',$email);
		$pos = strpos($email,'@');
		$firstPart = substr($email,0,$pos);
		$secondPart = substr($email,$pos+1,strlen($email)-$pos);
		$template->assign('showEmailFirstPart',$firstPart);
		$template->assign('showEmailSecondPart',$secondPart);
		$template->assign('showCanali',$arrayCanali);
		$stessi = false;
		if($current_user->getIdUser() == $id_user)
		{
			$stessi = true;
		}
		$template->assign('showDiritti',$stessi);
		
		$template->assign('showUser_UserHomepage','');
		if ($user->isDocente())
		{
			$doc = Docente::selectDocente($user->getIdUser());
			$template->assign('showUser_UserHomepage',$doc->getHomepageDocente());
		}
		$template->assign('showSettings','index.php?do=ShowSettings');
		return 'default';
	}

}
