<?php 
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

/**
 * RuoliAdminEdit: modifica il ruolo di un utente in un canale 
 * 
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class RuoliAdminEdit extends UniversiboCommand {


	function execute() {
		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();

		$user = $this->getSessionUser();
		
		$referente = false;
		
		$user_ruoli = $user->getRuoli();
		$ruoli = array();
		$arrayPublicUsers = array();
		
							
		if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'id del canale richiesto non e` valido', 'file' => __FILE__, 'line' => __LINE__));
			
		$canale = Canale::retrieveCanale($_GET['id_canale']);
		$id_canale = $canale->getIdCanale();
		
		if (!array_key_exists('id_utente', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_utente']))
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'id dell\'utente richiesto non e` valido', 'file' => __FILE__, 'line' => __LINE__));
		
		$target_user = User::selectUser($_GET['id_utente']);
		$target_username = $target_user->getUsername(); 
		$target_userUri = 'index.php?do=ShowUser&id_utente='.$target_user->getIdUser(); 
		
		$template->assign('common_canaleURI', $canale->showMe());
		$template->assign('common_langCanaleNome', 'a '.$canale->getTitolo());
		
		if (array_key_exists($id_canale, $user_ruoli)) 
		{
			$ruolo = & $user_ruoli[$id_canale];
			$referente = $ruolo->isReferente();
		}
		
		if (!$user->isAdmin() && !$referente )
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => "Non hai i diritti per modificare i diritti degli utenti su questa pagina.\nLa sessione potrebbe essere scaduta.", 'file' => __FILE__, 'line' => __LINE__));
		
		if (!$user->isAdmin() && $user->getIdUser() == $target_user->getIdUser() )
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'Non � permesso modificare i propri diritti in una pagina', 'file' => __FILE__, 'line' => __LINE__));
		
		$target_ruoli = $target_user->getRuoli(); 
		if (!array_key_exists($id_canale, $target_ruoli))
			$target_ruolo = null;
		else
			$target_ruolo = $target_ruoli[$id_canale];

		
		$success = false;
		$f17_accept = false;
		//postback
		if (array_key_exists('f17_submit', $_POST)  )
		{
			
			if (!array_key_exists('f17_livello', $_POST) )
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il form inviato non � valido', 'file' => __FILE__, 'line' => __LINE__));
			
			if ($_POST['f17_livello'] != 'none' && $_POST['f17_livello'] != 'M' && $_POST['f17_livello'] != 'R' )
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'Il form inviato non � valido', 'file' => __FILE__, 'line' => __LINE__));
			
			if ($target_ruolo == null)
			{
				$nascosto = false;
				$ruolo = new Ruolo($target_user->getIdUser(), $id_canale, '' , time(), $_POST['f17_livello'] == 'M', $_POST['f17_livello'] == 'R', true, NOTIFICA_ALL, $nascosto);
				$ruolo->insertRuolo();
				
				$success = true;
			}
			else
			{
				$target_ruolo->updateSetModeratore($_POST['f17_livello'] == 'M');
				$target_ruolo->updateSetReferente($_POST['f17_livello'] == 'R');
				$target_ruolo->setMyUniversiBO(true);
				$target_ruolo->updateRuolo();
				
				$success = true;
			}
			
		}
		
		
		if ($target_ruolo == null)
			$tpl_livello = 'none';
		else
			$tpl_livello = ($target_ruolo->isReferente()) ? 'R' :  (($target_ruolo->isModeratore()) ? 'M' : 'none');
		
		$template->assign('ruoliAdminEdit_userLivello', $tpl_livello);

		$template->assign('ruoliAdminEdit_username', $target_username);
		$template->assign('ruoliAdminEdit_userUri', $target_userUri);
		
		$template->assign('ruoliAdminEdit_langAction', "Modifica i diritti nella pagina\n".$canale->getTitolo());
		$template->assign('ruoliAdminEdit_langSuccess', '');
		if ($success == true)
			$template->assign('ruoliAdminEdit_langSuccess', 'Modifica eseguita con successo');
		
		$this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));
		
		return 'default';
	}

}
