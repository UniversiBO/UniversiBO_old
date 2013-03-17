<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use DateTime;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;
use Universibo\Bundle\LegacyBundle\Entity\Docente;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;
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

class ShowContattoDocente extends UniversiboCommand
{

    public function execute()
    {
        $context = $this->get('security.context');
        if (!$context->isGranted('ROLE_MODERATOR')) {
            throw new AccessDeniedHttpException('Collaborators only');
        }

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $user = $context->getToken()->getUser();
        $router = $this->get('router');

        $docenteRepo = $this->get('universibo_legacy.repository.docente');
        $codDoc = $this->getRequest()->attributes->get('cod_doc');
        $docente = $docenteRepo->find($codDoc);

        if (!$docente instanceof Docente) {
            throw new NotFoundHttpException('Professor not found');
        }

        $contattoRepo = $this->get('universibo_legacy.repository.contatto_docente');
        $contatto = $contattoRepo->find($codDoc);

        if (!$contatto instanceof ContattoDocente) {
            throw new NotFoundHttpException('Professor contact not found');
        }

        $utente_docente = $this->get('universibo_core.repository.user')->find($docente->getIdUtente());

        if (!$utente_docente) {
            throw new NotFoundHttpException('Professor user not found');
        }

        $rub_docente = $docenteRepo->getInfo($docente);
        $rub_presente = true;

        if (!$rub_docente) {
            $rub_presente = false;
            Error::throwError(_ERROR_NOTICE,
                    array('id_utente' => $user->getId(),
                            'msg' => 'Impossibile recuperare le informazioni del docente dalla rubrica',
                            'file' => __FILE__, 'line' => __LINE__,
                            'template_engine' => &$template));
        }
        //		var_dump($contatto);
        $info_docente = array();

        $info_docente['nome'] = ($rub_presente) ? $rub_docente['prefissonome']
                        . ' ' . $rub_docente['nome'] . ' '
                        . $rub_docente['cognome'] : $docente->getNomedoc();
        $info_docente['sesso'] = ($rub_docente['sesso'] == 1) ? 'm' : 'f';
        $datetime = $utente_docente->getLastLogin();
        $date = $datetime instanceof DateTime ? $datetime->getTimestamp() : 0;
        $info_docente['ultimo login al sito'] = ($date == 0) ? 'mai loggato'
                : round((time() - $date) / 86400) . ' gg fa circa';
        $info_docente['email universibo'] = $utente_docente->getEmail();
        $info_docente['mail'] = $rub_docente['email'];
        $info_docente['tel'] = $utente_docente->getPhone();
        $info_docente['afferente a'] = $rub_docente['descrizionestruttura'];

        $elenco_ruoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($utente_docente->getId());

        $info_ruoli = array();
        //		var_dump($elenco_ruoli);
        foreach ($elenco_ruoli as $ruolo) {
            $id_canale = $ruolo->getIdCanale();
            $canale = Canale::retrieveCanale($id_canale);
            $name = $canale->getNome();
            $date = $ruolo->getUltimoAccesso();
            $info_ruoli[$name] = ($date == 0) ? 'mai loggato'
                    : round((time() - $date) / 86400) . ' gg fa circa';
        }

        $lista_collabs = $this->_getCollaboratoriUniversibo();
        $table_collab = array();

        foreach ($lista_collabs as $collab) {
            $id = $collab->getId();
            $username = $collab->getUsername();
            $table_collab[$id] = $username;
        }

        uasort($table_collab, 'strcasecmp');

        // valori default form

        $f35_collab_list['null'] = 'Nessuno';
        foreach ($table_collab as $key => $value)
            $f35_collab_list[$key] = $value;
        $f35_stati = $contatto->getLegend();
        $f35_report = '';
        $f35_stato = $contatto->getStato();
        $id_mod = $contatto->getIdUtenteAssegnato();
        $f35_id_username = ($id_mod != null) ? $id_mod : 'null';

        $notifica_mod = false;

        if (array_key_exists('f35_submit_report', $_POST)) {

            if (!array_key_exists('f35_stato', $_POST)
                    || !array_key_exists($_POST['f35_stato'], $f35_stati))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'template_engine' => &$template));

            if (!array_key_exists('f35_id_username', $_POST)
                    || !array_key_exists($_POST['f35_id_username'],
                            $f35_collab_list))
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getId(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'template_engine' => &$template));

            $f35_stato = $_POST['f35_stato'];
            $f35_id_username = $_POST['f35_id_username'];

            if ($f35_stato != $contatto->getStato())
                $contatto->setStato($f35_stato, $user->getUsername());

            if ($f35_id_username != $contatto->getIdUtenteAssegnato()) {
                if ($f35_id_username != 'null') {
                    $notifica_mod = true;
                    $this->assegna($contatto, $f35_id_username, $user->getId());
                }
            }

            if (trim($_POST['f35_report']) != '')
                $contatto->appendReport($_POST['f35_report']);

            $contatto->updateContattoDocente();

            $notifica_titolo = 'Modifica contatto del docente '
                    . $docente->getNomeDoc();
            $notifica_titolo = substr($notifica_titolo, 0, 199);
            $notifica_dataIns = $contatto->getUltimaModifica();
            $notifica_urgente = false;
            $notifica_eliminata = false;
            $notifica_messaggio = '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Ciao! E\' stata effettuta una modifica dal servizio contatto del docente '
                    . $docente->getNomeDoc() . ' da ' . $user->getUsername()
                    . '

Stato attuale: ' . $f35_stati[$f35_stato] . '

Report attuale:
' . $contatto->getReport() . '

Link: ' . $router->generate('universibo_legacy_contact_professor', array('cod_doc' =>$docente->getCodDoc()))
                    . '
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~';

            if ($notifica_mod) {
                $userRepo = $this->get('universibo_core.repository.user');
                $notifica_user = $userRepo->findOneByUsername($f35_id_username);

                if ($notifica_user instanceof User) {
                    $contactService = $this->get('universibo_core.contact.service');
                    foreach ($contactService->getUserEmails($notifica_user) as $email) {
                        $notifica_destinatario = 'mail://' . $email;
                        $notifica = new NotificaItem(0, $notifica_titolo,
                            $notifica_messaggio, $notifica_dataIns,
                            $notifica_urgente, $notifica_eliminata,
                            $notifica_destinatario);
                        $notifica->insertNotificaItem();
                    }
                }

            }

            //ultima notifica al responsabile contatto docenti
            $notifica_user = $this->get('universibo_core.repository.user')->findOneByUsername($frontcontroller->getAppSetting('contattoDocentiAdmin'));
            $notifica_destinatario = 'mail://' . $notifica_user->getEmail();
            $notifica = new NotificaItem(0, $notifica_titolo,
                    $notifica_messaggio, $notifica_dataIns, $notifica_urgente,
                    $notifica_eliminata, $notifica_destinatario);
            $notifica->insertNotificaItem();
        }

        $template->assign('f35_collab_list', $f35_collab_list);
        $template->assign('f35_stati', $f35_stati);
        $template->assign('f35_stato', $f35_stato);
        $template->assign('f35_report', $f35_report);
        $template->assign('f35_id_username', $f35_id_username);
        $template->assign('ShowContattoDocente_info_docente', $info_docente);
        $template->assign('ShowContattoDocente_info_ruoli', $info_ruoli);
        $template
                ->assign('ShowContattoDocente_titolo',
                        'Info su ' . $docente->getNomeDoc());
        $template
                ->assign('ShowContattoDocente_contatto',
                        array('stato' => $f35_stati[$f35_stato],
                                'assegnato a' => ($f35_id_username != null
                                        && array_key_exists($f35_id_username,
                                                $f35_collab_list)) ? $f35_collab_list[$f35_id_username]
                                        : '',
                                'report' => $contatto->getReport()));

        // TODO da attivare quando sarà aggiunto l'argomento nell'help
        //$this->executePlugin('ShowTopic', array('reference' => 'contattodocenti'));
        return 'default';
    }

    public function _getCollaboratoriUniversibo()
    {
        $userRepo = $this->getContainer()->get('universibo_core.repository.user');

        return $userRepo->findCollaborators();
    }

    /**
     * @param int idUtenteMaster id di chi esegue la modifica della assegnamento
     * @param int newIdUtente nuovo collaboratore assegnato
     *
     */
    private function assegna(ContattoDocente $contact, $newIdUtente, $idUtenteMaster)
    {
        $userRepo = $this->get('universibo_core.repository.user');

        $newUser = $userRepo->find($newIdUtente);
        $masterUser = $userRepo->find($idUtenteMaster);

        $text = $masterUser->getUsername() .': assegnato docente a '.$newUser->getUsername();
        $contact->appendReport($text);
        $contact->setIdUtenteAssegnato($newUser->getId());
    }
}
