<?php
namespace UniversiBO\Bundle\LegacyBundle\Command\Links;

use UniversiBO\Bundle\LegacyBundle\App\Links\Link;
use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowLinks è un'implementazione di PluginCommand.
 *
 * Mostra i link
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 * Nel parametro di ingresso del plugin deve essere specificato il numero dei link da visualizzare e il canale da cui il plugin viene invocato.
 *
 * @package universibo
 * @subpackage Links
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowLinks extends PluginCommand {


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
        $user      = $bc->getSessionUser();
        $canale    = $bc->getRequestCanale();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $user_ruoli = $user->getRuoli();

        $id_canale = $canale->getIdCanale();
        $ultima_modifica_canale =  $canale->getUltimaModifica();

        $template->assign('showLinks_adminLinksFlag', 'false');


        $referente      = false;
        $moderatore     = false;
        $ultimo_accesso = $user->getUltimoLogin();

        if (array_key_exists($id_canale, $user_ruoli))
        {
            $ruolo = $user_ruoli[$id_canale];
            $referente      = $ruolo->isReferente();
            $moderatore     = $ruolo->isModeratore();
            $ultimo_accesso = $ruolo->getUltimoAccesso();
        }

        $personalizza = ($referente || $moderatore || $user->isAdmin());


        $lista_links = Link::selectCanaleLinks($id_canale);

        $ret_links = count($lista_links);
        $elenco_links_tpl = array();

        for ($i = 0; $i < $ret_links; $i++)
        {
            $link = $lista_links[$i];

            $elenco_links_tpl[$i]['uri']       		= $link->getUri();
            $elenco_links_tpl[$i]['label']      	= $link->getLabel();
            if ($link->isInternalLink())
                $elenco_links_tpl[$i]['tipo'] = "interno";

            else
                $elenco_links_tpl[$i]['tipo'] = "esterno";

        }

        $template->assign('showLinks_linksList', $elenco_links_tpl);
        $template->assign('showLinks_linksListAvailable', 'true');
        $template->assign('showLinks_linksAdminUri', 'v2.php?do=LinksAdmin&id_canale='.$id_canale);
        $template->assign('showLinks_linksAdminLabel', 'Gestione links');
        $template->assign('showLinks_linksPersonalizza', ($personalizza) ? 'true' : 'false');
    }
}
