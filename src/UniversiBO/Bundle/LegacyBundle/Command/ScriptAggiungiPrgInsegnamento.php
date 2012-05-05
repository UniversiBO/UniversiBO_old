<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;


/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ScriptAggiungiPrgInsegnamento extends UniversiboCommand 
{
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $fc->getTemplateEngine();
		$db = $fc->getDbConnection('main');
		
		$query = 'begin';
		$res = $db->query($query);
		if (DB::isError($res)) die($query); 

		$anno_accademico = 2011;


		$query = 'SELECT anno_accademico, cod_corso, cod_ind, cod_ori, cod_materia, 
				anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_modulo, 
				cod_doc, flag_titolare_modulo, tipo_ciclo, cod_ate, anno_corso_universibo
				FROM 
				input_esami_attivi 
				WHERE 1=1';
		
		$res = $db->query($query);
		if (DB::isError($res)) die($query); 
		
		echo $num_rows = $res->numRows() ,"\n\n";
		
		while ( $res->fetchInto($row) )
		{
	        echo "---------------","\n";
	
	        $query3 = 'SELECT * FROM prg_insegnamento  WHERE 
	        anno_accademico = '.$db->quote($row[0]).' AND anno_corso = '.$db->quote($row[5]).'
	        AND anno_corso_ins='.$db->quote($row[7]).' AND cod_corso='.$db->quote($row[1]).' AND cod_doc='.$db->quote($row[10]).'
	        AND cod_ind='.$db->quote($row[2]).' AND cod_materia='.$db->quote($row[4]).' 
	        AND cod_materia_ins='.$db->quote($row[6]).' AND cod_modulo='.$db->quote($row[9]).'
			AND cod_ori='.$db->quote($row[3]).' AND cod_ril='.$db->quote($row[8]).';';
	        
	        
			$res3= $db->query($query3);
	        if (DB::isError($res3)) die($query3); 
	        
			echo $num_rows3 = $res3->numRows();
	        if ($num_rows3 == 0)
	        {
				
				$id_canale = $db->nextId('canale_id_canale');
				echo "$id_canale \n";
				
				$query4 = 'INSERT INTO canale(id_canale,tipo_canale,nome_canale,immagine,visite,ultima_modifica,permessi_groups,files_attivo,news_attivo
				,forum_attivo,id_forum,group_id,links_attivo,files_studenti_attivo) VALUES ( '.$id_canale.',5,\'\',\'\',0,'.time().',127,\'S\',\'S\',\'N\',0,0,\'S\',\'S\');';
				
				$res4= $db->query($query4);
				if (DB::isError($res4)) die($query4);
				
				$query5 = 'INSERT INTO prg_insegnamento (anno_accademico,cod_corso,cod_ind,cod_ori,cod_materia,anno_corso,cod_materia_ins,
				anno_corso_ins,cod_ril,cod_modulo,cod_doc,flag_titolare_modulo,id_canale,cod_orario,tipo_ciclo,cod_ate,anno_corso_universibo)
				VALUES('.$db->quote($row[0]).', '.$db->quote($row[1]).', '.$db->quote($row[2]).', '.$db->quote($row[3]).', '.$db->quote($row[4]).', 
				'.$db->quote($row[5]).', '.$db->quote($row[6]).', '.$db->quote($row[7]).', '.$db->quote($row[8]).', '.$db->quote($row[9]).', 
				'.$db->quote($row[10]).', '.$db->quote($row[11]).', '.$db->quote($id_canale).', NULL , '.$db->quote($row[12]).', 
				'.$db->quote($row[13]).', '.$db->quote($row[14]).');'; 
				
				$res5= $db->query($query5);
				if (DB::isError($res5)) die($query5);
				echo "\n";
				
				$query7 = 'INSERT INTO info_didattica (id_canale) VALUES ( '.$id_canale.' );';
				$res7= $db->query($query7);
				if (DB::isError($res7)) die($query7);
			}
		}
		
		$query = 'commit';
		$res = $db->query($query);
		if (DB::isError($res)) die($query); 
	}
}
