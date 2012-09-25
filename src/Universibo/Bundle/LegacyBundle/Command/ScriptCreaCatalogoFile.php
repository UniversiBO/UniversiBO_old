<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\WebsiteBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

/**
 * ScriptCreaCatalogoFile is an extension of UniversiboCommand class.
 *
 * Si occupa della creazione del catalogo dei file per la condivisione con altre community
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto evaimitico@gmail.com
 * @author magoviz
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ScriptCreaCatalogoFile extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();
        $db = $fc->getDbConnection('main');

        $catalogoFilename = $fc->getAppSetting('catalogoFileName');
        $iterationStep = 300;
        // TODO far sì che urlfile prenda valori da appsetting
        $urlFile="https://www.universibo.unibo.it/?do=FileDownload&id_file=";
        $faculty='FACOLTA';
        $university='UNIVERSITA';

        // BUG: controllare la condizione
        if (!file_exists($catalogoFilename) || filemtime($catalogoFilename) < strtotime("-3 day")) {

            echo "vecchio" ;
            echo " \n" ;
            $res = $db->query('SELECT id_file FROM file WHERE password IS NULL AND permessi_download = '.$db->quote(User::ALL).' AND eliminato !='.$db->quote(FILE_ELIMINATO).' ORDER BY id_file ASC');
            //			echo 'SELECT id_file FROM file WHERE password IS NULL AND permessi_download = '.$db->quote(User::ALL).' AND eliminato !='.$db->quote(FILE_ELIMINATO).' ORDER BY id_file ASC';
            if (DB::isError($res))
                Error :: throwError(_ERROR_DEFAULT, array ('msg' => "Errori nel recupero dei file esistenti", 'file' => __FILE__, 'line' => __LINE__));

            $num = $res->numRows();
            //			var_dump($num);

            //			$doc = new_xmldoc('1.0');
            //			$root = $doc->add_root('catalog');
            //			$root->new_child('university',UNIVERSITY_NAME);
            //			$root->new_child('faculty',UNIVERSITY_FACULTY);

            // cancello il file vecchio
            $fp=@fopen('catalog.xml','w');
            fwrite($fp,'');
            fclose($fp);

            $fp = @fopen('catalog.xml','a');
            fwrite($fp,'<?xml version="1.0"?>');
            fwrite($fp,"\n");
            fwrite($fp,'<catalog>');
            fwrite($fp,"\n");
            fwrite($fp,'<university>'.$university.'</university>');
            fwrite($fp,"\n");
            fwrite($fp,'<faculty>'.$faculty.'</faculty>');
            fwrite($fp,"\n");

            while($res->fetchInto($row))
                $listaIdFiles[] = $row[0];
            $res->free();

            for ($x = 0; $x <= $num; $x+=$iterationStep) {
                $files = FileItem::selectFileItems(array_slice($listaIdFiles,$x,$iterationStep));
                //				var_dump($files);
                if ($x+$iterationStep>$num)
                    $x=$num;

                if ($files === false)
                    Error :: throwError(_ERROR_DEFAULT, array ('msg' => "Errori nel recupero dei file esistenti", 'file' => __FILE__, 'line' => __LINE__));

                foreach ($files as $file) {
                    fwrite($fp,'<download>');
                    fwrite($fp,'<id>'.$file->getIdFile().'</id>');
                    fwrite($fp,'<subject>'.htmlentities($file->getCategoriaDesc()).'</subject>');
                    fwrite($fp,'<title>'.htmlentities($file->getTitolo()).'</title>');
                    fwrite($fp,'<description>'.htmlentities($file->getDescrizione()).'</description>');
                    fwrite($fp,'<url>'.htmlentities($urlFile.$file->getIdFile()).'</url>');
                    fwrite($fp,'</download>');
                    fwrite($fp,"\n");
                    //$download = $root->new_child('download','');
                    //$download->new_child('id',$file->getIdFile());
                    //$download->new_child('subject',$file->getCategoriaDesc());
                    //$download->new_child('title',$file->getTitolo());
                    //$download->new_child('description',$file->getDescrizione());
                    //$download->new_child('url',$urlFile.$file->getIdFile());
                }
                unset($files);
            }
            fwrite($fp,'</catalog>');
            fclose($fp);
        }
    }
}
