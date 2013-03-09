<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Collaboratore;
use Universibo\Bundle\LegacyBundle\Framework\Error;

/**
 * CollaboratoreProfiloAdd: si occupa dell'inserimento del profilo di un collaboratore
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Cristina Valent
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class CollaboratoreProfiloAdd extends UniversiboCommand
{

    public function execute()
    {
        $request = $this->getRequest();
        $context = $this->get('security.context');
        $user = $context->getToken()->getUser();
        $id_coll = $this->getRequest()->get('id_coll');

        $userRepo = $this->get('universibo_core.repository.user');
        $collabUser = $userRepo->find($id_coll);

        if (!$collabUser instanceof User) {
            throw new NotFoundHttpException('User not found');
        }

        $collabRepo = $this->get('universibo_legacy.repository.collaboratore');
        if ($collabRepo->findOneByUser($collabUser) instanceof Collaboratore) {
            return $this->redirectProfile($collabUser);
        }

        if ($id_coll != $user->getId()) {
            if (!$context->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedHttpException('Not admin nor same user');
            }
        } else {
            $collabUser = $user;
        }

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        // valori default form
        $f36_foto = '';
        $f36_ruolo = '';
        $f36_email = '';
        $f36_recapito = '';
        $f36_intro = '';
        $f36_obiettivi = '';

        /*
         //come fo a prendere l'uri dove si trova l'utente?

        $template->assign('back_command', $id_canale);
        $template->assign('back_id_canale', $id_canale);
         */

        //		$num_canali = count($elenco_canali);
        //		for ($i = 0; $i<$num_canali; $i++)
        //		{
        //			$id_current_canale = $elenco_canali[$i];
        //			$current_canale = Canale::retrieveCanale($id_current_canale);
        //			$nome_current_canale = $current_canale->getTitolo();
        //			$spunta = ($id_canale == $id_current_canale ) ? 'true' :'false';
        //			$f7_canale[] = array ('id_canale'=> $id_current_canale, 'nome_canale'=> $nome_current_canale, 'spunta'=> $spunta);
        //		}

        $f36_accept = false;

        if (array_key_exists('f36_submit', $_POST)) {
            $f36_accept = true;

            if (!array_key_exists('f36_ruolo', $_POST)
                    || !array_key_exists('f36_intro', $_POST)
                    || !array_key_exists('f36_obiettivi', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f36_accept = false;
            }

            //ruolo
            if (strlen($_POST['f36_ruolo']) > 150) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il ruolo deve essere inferiore ai 150 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f36_accept = false;
            } elseif ($_POST['f36_ruolo'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il ruolo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f36_accept = false;
            } else
                $f36_ruolo = $_POST['f36_ruolo'];

            //intro
            if ($_POST['f36_intro'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'L\'intro del profilo deve essere inserito obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f36_accept = false;
            } else
                $f36_intro = $_POST['f36_intro'];

            //obiettivi
            if ($_POST['f36_obiettivi'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Gli obiettivi del profilo devono essere inseriti obbligatoriamente',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false,
                                'template_engine' => &$template));
                $f36_accept = false;
            } else
                $f36_obiettivi = $_POST['f36_obiettivi'];

            $photo = $request->files->get('f36_foto');

            //email
            if (array_key_exists('f36_email', $_POST)) {
                $f36_email = $_POST['f36_email'];
            }

            //recapito
            if (array_key_exists('f36_recapito', $_POST)) {
                $f36_recapito = $_POST['f36_recapito'];
            }

            //esecuzione operazioni accettazione del form
            if ($f36_accept == true) {
                //id_news = 0 per inserimento, $id_canali array dei canali in cui inserire
                $collaboratore = new Collaboratore();
                $collaboratore->setUser($collabUser);
                $collaboratore->setIntro($f36_intro);
                $collaboratore->setRecapito($f36_recapito);
                $collaboratore->setObiettivi($f36_obiettivi);

                if (null !== $photo) {
                    $dir = $path = $this->get('kernel')->getRootDir() . '/../web/img/contacts/';
                    $collaboratore->setFotoFilename($collabUser->getUsername() .'.png');

                    $imagine = new Imagine();
                    $size    = new Box(60, 80);
                    $mode    = ImageInterface::THUMBNAIL_INSET;

                    $imagine
                        ->open($photo->getPathname())
                        ->thumbnail($size, $mode)
                        ->save($dir . $collaboratore->getFotoFilename())
                    ;
                }

                $collaboratore->setRuolo($f36_ruolo);
                $collabRepo->insert($collaboratore);

                return $this->redirectProfile($collabUser);
            } //end if (array_key_exists('f7_submit', $_POST))
        }
        $template->assign('f36_foto', $f36_foto);
        $template->assign('f36_ruolo', $f36_ruolo);
        $template->assign('f36_email', $f36_email);
        $template->assign('f36_recapito', $f36_recapito);
        $template->assign('f36_intro', $f36_intro);
        $template->assign('f36_obiettivi', $f36_obiettivi);
        //$template->assign('f36_canale', $f36_canale);

        //$topics[] =
        //$this->executePlugin('ShowTopic', array('reference' => 'newscollabs'));
        return 'default';
    }

    private function redirectProfile(User $user)
    {
       $url = $this->generateUrl('universibo_legacy_collaborator',
                        array('username' => $user->getUsername()));

       return $this->redirect($url);
    }
}
