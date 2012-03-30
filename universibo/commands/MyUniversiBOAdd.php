<?php
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

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
 
class MyUniversiBOAdd extends UniversiboCommand 
{
	function execute()
	{
		
		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		$utente = $this->getSessionUser();
		
		
		if($utente->isOspite())
			Error::throwError(_ERROR_DEFAULT, array('id_utente' => $utente->getIdUser(), 'msg' => "Non è permesso ad utenti non registrati eseguire questa operazione.\n La sessione potrebbe essere scaduta", 'file' => __FILE__, 'line' => __LINE__));

		if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
		{
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'L\'id del canale richiesto non è valido', 'file' => __FILE__, 'line' => __LINE__));
		}
		$id_canale = $_GET['id_canale'];
		$canale = & Canale::retrieveCanale($id_canale);
		$template->assign('common_canaleURI', $canale->showMe());
		$template->assign('common_langCanaleNome', $canale->getNome());
		
		$ruoli = $utente->getRuoli();
		$this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));
		
//		if()
//		{
//			
//			return 'success';
//		}
//		else
//		{
			
		$f15_livelli_notifica = Ruolo::getLivelliNotifica();
		$f15_livello_notifica = $utente->getLivelloNotifica();
		$f15_nome = (array_key_exists($id_canale, $ruoli) ) ? $ruoli[$id_canale]->getNome() : '';
		
		$f15_accept = false;
		if (array_key_exists('f15_submit', $_POST))
		{
			
			$f15_accept = true;
			if(!array_key_exists('f15_nome', $_POST) || !array_key_exists('f15_livello_notifica', $_POST) )
			{
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il form inviato non è valido', 'file' => __FILE__, 'line' => __LINE__));
				$f15_accept = false;
			}	
			
			if(!array_key_exists($_POST['f15_livello_notifica'], $f15_livelli_notifica) )
			{
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il livello di notifica scelto non è valido', 'file' => __FILE__, 'line' => __LINE__));
				$f15_accept = false;
			}
			else
				$f15_livello_notifica = $_POST['f15_livello_notifica'];
			
			if(strlen($_POST['f15_nome']) > 60 )
			{
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il nome scelto deve essere inferiore ai 60 caratteri', 'file' => __FILE__, 'line' => __LINE__));
				$f15_accept = false;
			}	
			else 
				$f15_nome = $_POST['f15_nome'];
			
			
			if($f15_accept == true)
			{
				
				if( array_key_exists($id_canale, $ruoli) )
				{
					$ruolo = $ruoli[$id_canale];
					$ruolo->updateNome($f15_nome);
					$ruolo->updateTipoNotifica($f15_livello_notifica);
					$ruolo->setMyUniversiBO(true);
					
					$ruolo->updateRuolo();
				}
				else
				{
					$nascosto = false;
					$ruolo = new Ruolo($utente->getIdUser(), $id_canale, $f15_nome , time(), false, false, true, $f15_livello_notifica, $nascosto);
					$ruolo->insertRuolo();
				}
				
				if($canale->getTipoCanale() == CANALE_INSEGNAMENTO)
				{
					//trover? un modo per ottenere il cdl! lo giuro!!!
				}
				
				return 'success';
			}
			
		}
		
			
		$template->assign('f15_nome', $f15_nome);
		$template->assign('f15_livelli_notifica', $f15_livelli_notifica);
		//var_dump($f15_livello_notifica);
		$template->assign('f15_livello_notifica', $f15_livello_notifica);
		
		return 'default';
	}
}
