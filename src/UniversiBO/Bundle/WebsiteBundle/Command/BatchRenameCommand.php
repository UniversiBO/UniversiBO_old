<?php
namespace UniversiBO\Bundle\WebsiteBundle\Command;

use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Batch rename users
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class BatchRenameCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this->setName('user:batch-rename')
                ->setDescription('Batch rename users')
                ->addOption('pretend', 'p', InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input,
            OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->verboseMessage('Opening database connection', $output);
        $db = $this->get('universibo_legacy.db.connection.main');
        $db->autoCommit(false);

        $repository = $this->get('universibo_legacy.repository.user');

        $doctrine = $this->get('doctrine');
        $em = $doctrine->getEntityManager();
        $batchRenameRepo = $doctrine
                ->getRepository(
                        'UniversiBO\\Bundle\\LegacyBundle\\Entity\\BatchRename');

        $em->beginTransaction();

        $email = array();

        foreach ($batchRenameRepo->findByStatus('pending') as $current) {
            $user = $repository->find($current->getId());
            $oldUsername = $user->getUsername();

            $this->renameUser($repository, $user);
            $this
                    ->verboseMessage(
                            'Renamed user ' . $oldUsername . ' to '
                                    . $user->getUsername(), $output);
            $email[] = array('old' => $oldUsername,
                    'new' => $user->getUsername(),
                    'to' => array($user->getADUsername(), $user->getEmail()));

            $current->setStatus('renamed');
            $em->merge($current);
        }

        if ($input->getOption('pretend')) {
            $this->verboseMessage('Rolling back transaction', $output);
            $db->rollback();
            $em->rollback();
        } else {
            $this->verboseMessage('Committing transaction', $output);
            $db->commit();
            $em->flush();
            $em->commit();
        }

        $mailer = $this->get('mailer');

        foreach ($email as $item) {
            $text = <<<EOD
Ciao {$item['old']},

Come precedentemente comunicato in data 26 marzo abbiamo provveduto a modificare il tuo nome utente.
Il tuo nuovo nome utente è {$item['new']}.

Per qualsiasi dubbio non esitare a contattarci all'indirizzo info_universibo@mama.ing.unibo.it
Lo Staff di UniversiBO

https://www.universibo.unibo.it/
http://www.facebook.com/UniversiBO
EOD;

            if (!$input->getOption('pretend')) {
                $message = \Swift_Message::newInstance()
                         ->setSubject('Cambio Username UniversiBO')
                         ->setFrom('associazione.universibo@unibo.it')
                         ->setTo($item['to'])->setBody(trim($text));
                $mailer->send($message);
            }

            $this->verboseMessage($text, $output);
        }
    }

    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    private function renameUser(DBUserRepository $repository, User $user)
    {
        $newUsername = $repository->getAvaliableUsername($user->getUsername());
        $repository->renameUser($user, $newUsername);
    }

    /**
     * Prints a message only if verbose
     *
     * @param string $message
     * @param OutputInterface $output
     */
    private function verboseMessage($message, OutputInterface $output)
    {
        if ($this->verbose) {
            $output->writeln($message);
        }
    }
}
