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
 
class MyUniversiBOEdit extends UniversiboCommand 
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
		if( !array_key_exists($id_canale, $ruoli) )
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il ruolo richiesto non è presente', 'file' => __FILE__, 'line' => __LINE__));
		
		$ruolo = $ruoli[$id_canale];
		$this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));
		
		if(array_key_exists($id_canale, $ruoli))
		{
			$f19_livelli_notifica = Ruolo::getLivelliNotifica();
			$f19_livello_notifica = $ruolo->getTipoNotifica();
			$f19_nome = $ruolo->getNome();
			
			$f19_accept = false;
			if (array_key_exists('f19_submit', $_POST))
			{
				
				$f19_accept = true;
				if(!array_key_exists('f19_nome', $_POST) || !array_key_exists('f19_livello_notifica', $_POST) )
				{
					Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il form inviato non è valido', 'file' => __FILE__, 'line' => __LINE__));
					$f19_accept = false;
				}	
				
				if(!array_key_exists($_POST['f19_livello_notifica'], $f19_livelli_notifica) )
				{
					Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il livello di notifica scelto non è valido', 'file' => __FILE__, 'line' => __LINE__));
					$f19_accept = false;
				}
				else
					$f19_livello_notifica = $_POST['f19_livello_notifica'];
				
				if(strlen($_POST['f19_nome']) > 60 )
				{
					Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Il nome scelto deve essere inferiore ai 60 caratteri', 'file' => __FILE__, 'line' => __LINE__));
					$f19_accept = false;
				}	
				else 
					$f19_nome = $_POST['f19_nome'];
				
				
				if($f19_accept == true)
				{
					//$nascosto = false;
					//$ruolo = new Ruolo($utente->getIdUser(), $id_canale,  , time(), false, false, true, $f19_livello_notifica, $nascosto);
					
					$ruolo->updateNome($f19_nome);
					$ruolo->updateTipoNotifica($f19_livello_notifica);
					
					$ruolo->updateRuolo();
					$canale = Canale::retrieveCanale($id_canale);
					$template->assign('showUser','index.php?do=ShowUser&id_utente='.$utente->getIdUser());
					if($canale->getTipoCanale() == CANALE_INSEGNAMENTO)
					{
						//troverò un modo per ottenere il cdl! lo giuro!!!
					}
					return 'success';
				}
				
			}
			
			
			$template->assign('f19_nome', $f19_nome);
			$template->assign('f19_livelli_notifica', $f19_livelli_notifica);
			$template->assign('f19_livello_notifica', $f19_livello_notifica);
			
			return 'default';
	
		}
		else
		{
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'Questa pagina non è inserita nel tuo MyUniversiBO', 'file' => __FILE__, 'line' => __LINE__));
		}
		
	}
	
}
