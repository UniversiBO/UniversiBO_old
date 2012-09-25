<?php

namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;
use Universibo\Bundle\LegacyBundle\Entity\Docente;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

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
        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user->hasRole('ROLE_COLLABORATOR') && !$this->get('security.context')->isGranted('ROLE_ADMIN'))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => 'Non hai i diritti necessari per visualizzare la pagina',
                            'file' => __FILE__, 'line' => __LINE__,
                            'template_engine' => &$template));

        $lista_contatti = ContattoDocente::getAllContattoDocente();

        $elenco = array();

        if ($lista_contatti) {

            foreach ($lista_contatti as $contatto) {
                $doc = Docente::selectDocenteFromCod($contatto->getCodDoc());
                //				if (!$doc) {var_dump($contatto); die;}
                $elenco[] = array('nome' => $doc->getNomeDoc(),
                        'URI' => '/?do=ShowContattoDocente&cod_doc='
                                . $doc->getCodDoc(),
                        'stato' => $contatto->getStatoDesc(),
                        'codStato' => $contatto->getStato());
            }
        }
        usort($elenco, array($this, '_compareDocenti'));
        //		var_dump($elenco);
        $template->assign('ShowContattiDocenti_contatti', $elenco);
        $template
                ->assignUnicode('ShowContattiDocenti_titolo',
                        'Docenti assegnati per l\'attività offline');
        //		$template->assign('ShowContattiDocenti_addContatto', 'Aggiungi un docente da assegnare');
        //		$template->assign('ShowContattiDocenti_addContattoURI', '/?do=ContattoDocenteAdd');
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
