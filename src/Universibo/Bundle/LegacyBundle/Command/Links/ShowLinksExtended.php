<?php
namespace Universibo\Bundle\LegacyBundle\Command\Links;

use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Links\Link;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowLinksExtended è un'implementazione di PluginCommand.
 *
 * Mostra i link
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di UniversiBOCommand.
 * Nel parametro di ingresso del deve essere specificato l'id del canale da cui viene invocato il plugin.
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @author Roberto Floris
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowLinksExtended extends ShowLinksCommon
{
    /**
     * Esegue il plugin
     *
     * @param array $param deve contenere:
     *  - 'id_canale' l'id del canale
     *	  es: array('num'=>5)
     */
    public function execute($param = array())
    {
        $id_canale  =  $param['id_canale'];

        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $canale    = Canale::retrieveCanale($id_canale);
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        //BUG strano: se passo per riferimento l'array dei ruoli, si modifica il session user di universibo_command
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $router = $this->get('router');

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
            $ultimo_accesso = $user->getLastLogin()->getTimestamp();
        }

        $lista_links = Link::selectCanaleLinks($id_canale);

        $ret_links = count($lista_links);
        $elenco_links_tpl = array();

        for ($i = 0; $i < $ret_links; $i++) {
            $links = $lista_links[$i];

            $elenco_links_tpl[$i]['uri']       		= $links->getUri();
            $elenco_links_tpl[$i]['label']      	= $links->getLabel();
            $elenco_links_tpl[$i]['description']    = $links->getDescription();
            $elenco_links_tpl[$i]['userlink']    = $router->generate('universibo_legacy_user', array('id_utente' => $links->getIdUtente()));
            $elenco_links_tpl[$i]['user']    = $links->getUsername();

            $elenco_links_tpl[$i]['tipo'] = ($this->isInternalLink($links)) ? "interno" : "esterno";

            if (($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || ($moderatore && $links->getIdUtente()==$user->getId()))) {
                $elenco_links_tpl[$i]['modifica']="Modifica";
                $elenco_links_tpl[$i]['modifica_link_uri'] = $router->generate('universibo_legacy_link_edit', array('id_link' => $links->getIdLink(), 'id_canale' => $links->getIdCanale()));
                $elenco_links_tpl[$i]['elimina']="Cancella";
                $elenco_links_tpl[$i]['elimina_link_uri'] = $router->generate('universibo_legacy_link_delete', array('id_link' => $links->getIdLink(), 'id_canale' => $links->getIdCanale()));
            }
        }

        $template->assign('showLinksExtended_linksList', $elenco_links_tpl);
        $template->assign('showLinksExtended_linksListAvailable', ((count($elenco_links_tpl) > 0) || $personalizza));
        //var_dump($user);
    }
}
