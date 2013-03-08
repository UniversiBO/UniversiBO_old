<?php

namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;
use Universibo\Bundle\LegacyBundle\Entity\Docente;

/**
 * ShowContacts is an extension of UniversiboCommand class.
 *
 * It shows Contacts page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.2.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowContattiDocenti extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $professorRepo = $this->get('universibo_legacy.repository.docente');

        if (!$this->get('security.context')->isGranted('ROLE_MODERATOR')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $lista_contatti = ContattoDocente::getAllContattoDocente();

        $elenco = array();

        if ($lista_contatti) {

            foreach ($lista_contatti as $contatto) {
                $doc = $professorRepo->find($contatto->getCodDoc());
                if ($doc instanceof Docente) {
                    $elenco[] = array('nome' => $doc->getNomeDoc(),
                            'URI' => $router->generate('universibo_legacy_contact_professor', array('cod_doc' => $doc->getCodDoc())),
                            'stato' => $contatto->getStatoDesc(),
                            'codStato' => $contatto->getStato());
                }
            }
        }
        usort($elenco, array($this, '_compareDocenti'));
        //		var_dump($elenco);
        $template->assign('ShowContattiDocenti_contatti', $elenco);
        $template
                ->assign('ShowContattiDocenti_titolo',
                        'Docenti assegnati per l\'attività offline');

        return 'default';
    }

    public function _compareDocenti($a, $b)
    {
        if (strnatcmp($a['nome'], $b['nome']) > 0)
            return +1;
        else
            return -1;
    }

}
