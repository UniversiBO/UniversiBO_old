<?php

use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

require_once ('Files/FileItemStudenti'.PHP_EXTENSION);

/**
 * ShowAllFilesStudentiTitoli e` un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * 
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ShowAllFilesStudentiTitoli extends PluginCommand {
	
	
	/**
	 * Esegue il plugin
	 *
	 * @param array $param nessu parametro  
	 */
	function execute($param)
	{
		$elenco_file = $param['files'];
		$bc        = $this->getBaseCommand();
		$user      = $bc->getSessionUser();
		$fc        = $bc->getFrontController();
		$template  = $fc->getTemplateEngine();
		$krono     = $fc->getKrono();

		$personalizza   = false;
		$referente      = false;
		$moderatore     = false;
		$personalizza_not_admin = false;
		$ultimo_accesso = $user->getUltimoLogin();

		$canale_files = count($elenco_file);
		
		if ( $canale_files == 0 )
		{
			$template->assign('showAllFilesStudentiTitoli_langFileAvailable', 'Non ci sono files da visualizzare');
			$template->assign('showAllFilesStudentiTitoli_langFileAvailableFlag', 'false');
		}
		else
		{
			$template->assign('showAllFilesStudentiTitoli_langFileAvailable', 'Ci sono '.$canale_files.' notizie');
			$template->assign('showAllFilesStudentiTitoli_langFileAvailableFlag', 'true');
		}

//		usort($elenco_file, array('ShowMyFileTitoli','_compareFile'));
		
		//$elenco_categorie_file_tpl = array();
		$categorie_tpl = array();
		$file_tpl = array();
			
		
		if ($elenco_file ==! false )
		{
			$ret_file = count($elenco_file);
			
			for ($i = 0; $i < $ret_file; $i++)
			{
				
				$file = $elenco_file[$i];
				//var_dump($file);
				$this_moderatore = ($user->isAdmin() || ($moderatore && $file->getIdUtente()==$user->getIdUser()));
		
				$permessi_lettura = $file->getPermessiVisualizza();
				if ($user->isGroupAllowed($permessi_lettura))
				{
								
					$file_tpl[$i]['titolo']       = $file->getTitolo();
					//$file_tpl['notizia']      = $file->getNotizia();
					$file_tpl[$i]['data']         = $krono->k_date('%j/%m/%Y', $file->getDataInserimento());
					//echo $personalizza,"-" ,$ultimo_accesso,"-", $file->getUltimaModifica()," -- ";
					//$file_tpl['nuova']        = ($flag_chkDiritti && $personalizza_not_admin && $ultimo_accesso < $file->getUltimaModifica()) ? 'true' : 'false'; 
					$file_tpl[$i]['nuova']        = ($personalizza_not_admin && $ultimo_accesso < $file->getDataModifica()) ? 'true' : 'false';
					$file_tpl[$i]['autore']       = $file->getUsername();
					$file_tpl[$i]['autore_link']  = 'index.php?do=ShowUser&id_utente='.$file->getIdUtente();
					$file_tpl[$i]['id_autore']    = $file->getIdUtente();
					$file_tpl[$i]['dimensione'] = $file->getDimensione();
					$file_tpl[$i]['voto_medio'] = round($file->getVoto($file->getIdFile()),1);
//	tolto controllo: Il link download va mostrato sempre, il controllo ? effettuato successivamente 
//					$file_tpl['download_uri'] = '';
//					$permessi_download = $file->getPermessiDownload();
//					if ($user->isGroupAllowed($permessi_download))
					$canali = $file->getIdCanali();
					$num_canali =  count($canali);
					$elenco_canali_tpl = array();
					for ($j = 0; $j < $num_canali; $j++)
					{
						$canale = Canale::retrieveCanale($canali[$j]);
						if ($canale->isGroupAllowed($user->getGroups()))
						{
							$canale_tpl = array();
//							$canale_tpl['titolo'] = $canale->getNome();
//							$canale_tpl['link'] = $canale->showMe();
							$file_tpl[$i]['canaleTitolo'] = $canale->getNome();
							$file_tpl[$i]['canaleLink'] = $canale->showMe();
							$file_tpl[$i]['download_uri'] = 'index.php?do=FileDownload&id_file='.$file->getIdFile().'&id_canale='.$canali[$j];
							$file_tpl[$i]['show_info_uri'] = 'index.php?do=FileShowInfo&id_file='.$file->getIdFile().'&id_canale='.$canali[$j];
							$file_tpl[$i]['canali'][] = $canale_tpl;
						}			
					}
					
					$file_tpl[$i]['categoria'] = $file->getCategoriaDesc();
					$file_tpl[$i]['modifica']     = '';
					$file_tpl[$i]['modifica_link']= '';
					$file_tpl[$i]['elimina']      = '';
					$file_tpl[$i]['elimina_link'] = '';
					$file_tpl[$i]['desc'] = $file->getCategoriaDesc();
					//roba mia
					
				}
			}
		}
		$template->assign('showAllFilesStudentiTitoli_fileList', $file_tpl);
		
		
	}
	
	
	/**
	 * Ordina la struttura dei file
	 * 
	 * @static
	 * @private
	 */
	function _compareFile($a, $b)
	{
//		if ($a->getIdCategoria() > $b->getIdCategoria()) return +1;
//		if ($a->getIdCategoria() < $b->getIdCategoria()) return -1;
//		if ($a->getDataInserimento() < $b->getDataInserimento()) return +1;
//		if ($a->getDataInserimento() > $b->getDataInserimento()) return -1;
	}
	
	
	
}

?>
