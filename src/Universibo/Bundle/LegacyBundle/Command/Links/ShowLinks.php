<?php
namespace Universibo\Bundle\LegacyBundle\Command\Links;

use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Links\Link;

/**
 * ShowLinks è un'implementazione di PluginCommand.
 *
 * Mostra i link
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato il numero dei link da visualizzare e il canale da cui il plugin viene invocato.
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowLinks extends ShowLinksCommon
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
        $num_links  =  $param['num'];

        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $canale    = $bc->getRequestCanale();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $router    = $this->get('router');
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();

        $id_canale = $canale->getIdCanale();
        $ultima_modifica_canale =  $canale->getUltimaModifica();

        $template->assign('showLinks_adminLinksFlag', 'false');

        $referente      = false;
        $moderatore     = false;
        $ultimo_accesso = $user instanceof User ? $user->getLastLogin()->getTimestamp() : 0;

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];
            $referente      = $ruolo->isReferente();
            $moderatore     = $ruolo->isModeratore();
            $ultimo_accesso = $ruolo->getUltimoAccesso();
        }

        $personalizza = ($referente || $moderatore || $this->get('security.context')->isGranted('ROLE_ADMIN'));

        $lista_links = Link::selectCanaleLinks($id_canale);

        $ret_links = count($lista_links);
        $elenco_links_tpl = array();

        for ($i = 0; $i < $ret_links; $i++) {
            $link = $lista_links[$i];

            $elenco_links_tpl[$i]['uri']       		= $link->getUri();
            $elenco_links_tpl[$i]['label']      	= $link->getLabel();
            if ($this->isInternalLink($link))
                $elenco_links_tpl[$i]['tipo'] = "interno";
            else
                $elenco_links_tpl[$i]['tipo'] = "esterno";
        }

        $template->assign('showLinks_linksList', $elenco_links_tpl);
        $template->assign('showLinks_linksListAvailable', 'true');
        $template->assign('showLinks_linksAdminUri', $router->generate('universibo_legacy_link_admin', array('id_canale' => $id_canale)));
        $template->assign('showLinks_linksAdminLabel', 'Gestione links');
        $template->assign('showLinks_linksPersonalizza', ($personalizza) ? 'true' : 'false');
    }
}
