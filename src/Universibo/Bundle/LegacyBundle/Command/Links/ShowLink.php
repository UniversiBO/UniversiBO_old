<?php
namespace Universibo\Bundle\LegacyBundle\Command\Links;

use Universibo\Bundle\LegacyBundle\Entity\Links\Link;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowLink è un'implementazione di PluginCommand.
 *
 * Mostra i link
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Il parametro di ingresso deve essere l'id del link da visualizzare.
 *
 * @package universibo
 * @subpackage Links
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowLink extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  - 'num' il numero di link da visualizzare
     *	  es: array('num'=>5)
     */
    public function execute($param = array())
    {

//		$id_canale  =  $param['id_canale'];
        $id_link = $param['id_link'];

        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $canale    = $bc->getRequestCanale();
//		$canale    = Canale::retrieveCanale($id_canale);
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();


        $id_canale = $canale->getIdCanale();
        $ultima_modifica_canale =  $canale->getUltimaModifica();

        $template->assign('showLinks_adminLinksFlag', 'false');
        if (array_key_exists($id_canale, $user_ruoli) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $personalizza = true;

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $referente      = $ruolo->isReferente();
                $moderatore     = $ruolo->isModeratore();
                $ultimo_accesso = $ruolo->getUltimoAccesso();
            }

        } else {
            $personalizza   = false;
            $referente      = false;
            $moderatore     = false;
            $ultimo_accesso = $user->getUltimoLogin();
        }

        $link = Link::selectLink($id_link);

        $link_tpl['uri']       		= $link->getUri();
        $link_tpl['label']      	= $link->getLabel();
        $link_tpl['description']    = $link->getDescription();
        $link_tpl['userlink']    = '/?do=ShowUser&id_utente='.$link->getIdUtente();
        $link_tpl['user']    = $link->getUsername();


        $template->assign('showSingleLink', $link_tpl);
    }
}
