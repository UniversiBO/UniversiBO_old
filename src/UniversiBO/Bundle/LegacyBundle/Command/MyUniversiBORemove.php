<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\App\ForumApi;
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

class MyUniversiBORemove extends UniversiboCommand
{
    function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $utente = $this->getSessionUser();


        if($utente->isOspite())
            Error::throwError(_ERROR_DEFAULT, array('id_utente' => $utente->getIdUser(), 'msg' => "Non � permesso ad utenti non registrati eseguire questa operazione.\n La sessione potrebbe essere scaduta", 'file' => __FILE__, 'line' => __LINE__));

        if (!array_key_exists('id_canale', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
        {
            Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'L\'id del canale richiesto non � valido', 'file' => __FILE__, 'line' => __LINE__));
        }
        $id_canale = $_GET['id_canale'];
        $canale = & Canale::retrieveCanale($id_canale);
        $template->assign('common_canaleURI', $canale->showMe());
        $template->assign('common_langCanaleNome', $canale->getNome());
        $template->assign('showUser','index.php?do=ShowUser&id_utente='.$utente->getIdUser());

        $ruoli = $utente->getRuoli();
        $this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));
        if(array_key_exists($id_canale, $ruoli))
        {
            $ruolo = $ruoli[$id_canale];
            $ruolo->setMyUniversiBO(false, true);

            $forum = new ForumApi();
            $forum->removeUserGroup($canale->getForumGroupId(), $utente->getIdUser());


            return 'success';
        }
        else
        {
            Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $utente->getIdUser(), 'msg' => 'E\' impossibile trovare la pagina nel tuo elenco di MyUniversiBO', 'file' => __FILE__, 'line' => __LINE__));
        }
    }
}
