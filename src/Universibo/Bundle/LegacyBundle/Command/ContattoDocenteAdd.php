<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;
use Universibo\Bundle\LegacyBundle\Entity\Docente;

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

class ContattoDocenteAdd extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $context = $this->get('security.context');
        $user = $context->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;
        
        if(!$context->isGranted('ROLE_COLLABORATOR') && 
                !$context->isGranted('ROLE_ADMIN')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => 'Non hai i diritti necessari per visualizzare la pagina',
                            'file' => __FILE__, 'line' => __LINE__));
        }
        
        $request = $this->getRequest();
        $codDoc = $request->get('cod_doc');
        $docenteRepo = $this->get('universibo_legacy.repository.docente');
        $docente = $docenteRepo->find($codDoc);
        
        if (!$docente instanceof Docente) {
            throw new NotFoundHttpException('Docente not found');
        }
        
        $contattoRepo = $this->get('universibo_legacy.repository.contatto_docente');
        $contatto = $contattoRepo->findOneByCodDoc($codDoc);
        
        if(!$contatto instanceof ContattoDocente) {
            $contatto = new ContattoDocente($codDoc, 1, null, null,'');
            $esito = $contattoRepo->insert($contatto);
        } else {
            $esito = false;
        }

        $this->assignBacklink($request, $template);
        
        $template->assign('ContattoDocenteAdd_esito',
                        ($esito) ? ' Il contatto del docente è stato inserito con successo'
                                : 'Il contatto del docente non è stato inserito');
        $template->assign('ContattoDocenteAdd_titolo','Aggiungi un contatto docente');

        return 'default';
    }
    
    private function assignBacklink(Request $request, $template)
    {
        $channelId = $request->get('id_canale', '');
        if(preg_match('/^([0-9]{1,9})$/', $channelId)) {
            $channelRepo = $this->get('universibo_legacy.repository.canale2');
            $channel = $channelRepo->find($channelId);
            
            if($channel instanceof Canale) {
                $channelRouter = $this->get('universibo_legacy.routing.channel');
                $template->assign('common_canaleURI',$channelRouter->generate($channel));
                $template->assign('common_langCanaleNome', $channel->getTitolo());
                
                return;
            }
        }
        
        $template->assign('common_canaleURI', $request->server->get('HTTP_REFERER'));
        $template->assign('common_langCanaleNome', 'indietro');
    }
}
