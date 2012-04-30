<?php
namespace UniversiBO\Bundle\WebsiteBundle\Command;
use Symfony\Bundle\DoctrineBundle\Registry;

use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
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

        foreach ($batchRenameRepo->findByStatus('pending') as $current) {
            $user = $repository->find($current->getId());
            $oldUsername = $user->getUsername();
            
            $this->renameUser($repository, $user);
            $this->verboseMessage('Renamed user '.$oldUsername.' to '.$user->getUsername(), $output);
            
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
