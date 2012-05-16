<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \Error;
use \Canale;
use \ContattoDocente;
use \Docente;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ContattoDocenteAdd is an extension of UniversiboCommand class.
 *
 * permette di aggiungere un contatto docente, se non presente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.2.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ContattoDocenteAdd extends UniversiboCommand {

    function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->getSessionUser();

        if (!array_key_exists('cod_doc',$_GET) && !preg_match( '/^([0-9]{1,10})$/' , $_GET['cod_doc'] ) )
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'utente cercato non e` valido','file'=>__FILE__,'line'=>__LINE__));

        if (!$user->isCollaboratore() && !$user->isAdmin())
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Non hai i diritti necessari per visualizzare la pagina','file'=>__FILE__,'line'=>__LINE__));

        $docente = Docente::selectDocenteFromCod($_GET['cod_doc']);

        if (!$docente)
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'utente cercato non e` un docente','file'=>__FILE__,'line'=>__LINE__));

        //echo 'qui';
        $contatto = new ContattoDocente($docente->getCodDoc(),1, null,null,'');
        //		var_dump($contatto); die;
        //		echo "qui\n";
        $esito = $contatto->insertContattoDocente();
        //		echo "qui\n";
        $template->assign('common_canaleURI', array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '' );
        $template->assign('common_langCanaleNome', 'indietro');
        //		var_dump($_SERVER); die;
        if (array_key_exists('id_canale',$_GET) && preg_match( '/^([0-9]{1,9})$/' , $_GET['id_canale'] ) )
        {
            $canale = & Canale::retrieveCanale($_GET['id_canale']);
            if($canale)
            {
                $id_canale = $canale->getIdCanale();
                $template->assign('common_canaleURI', $canale->showMe());
                $template->assign('common_langCanaleNome', 'a '.$canale->getTitolo());
            }
        }
        $template->assignUnicode('ContattoDocenteAdd_esito', ($esito) ? ' Il contatto del docente è stato inserito con successo' : 'Il contatto del docente non è stato inserito');
        $template->assign('ContattoDocenteAdd_titolo', ' Aggiungi un contatto docente');

        return 'default';
    }
}
