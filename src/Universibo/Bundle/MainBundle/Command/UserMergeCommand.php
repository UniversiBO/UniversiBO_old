<?php
namespace Universibo\Bundle\MainBundle\Command;

use InvalidArgumentException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\MainBundle\Entity\User;

/**
 * Batch rename users
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class UserMergeCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('universibo:user:merge')
            ->setDescription('Merge multiple accounts in one')
            ->addArgument(
                'username',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Username to merge'
            )
            ->addOption(
                'target',
                't',
                InputArgument::OPTIONAL,
                'Target username, won\'t ask for confirmation'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input,
            OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @throws LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usernames = $input->getArgument('username');

        if (count($usernames) <= 1) {
            throw new LogicException('Please provide at least 2 usernames');
        }

        $userManager = $this->get('fos_user.user_manager');
        $merger = $this->get('universibo_main.merge.user');

        $output->setDecorated(true);

        $output->writeln('Merging users:');
        $users = array();
        foreach ($usernames as $id => $username) {
            $user = $userManager->findUserByUsername($username);
            if (!$user instanceof User) {
                throw new InvalidArgumentException('Username not found');
            }

            $username = $user->getUsername();

            $output->writeln('<info>'.($id+1).') '.$username.'</info>');
            $owned = $merger->getOwnedResources($user);

            foreach ($owned as $key => $resource) {
                $output->write('    '.$resource['description'].': ');
                $output->writeln($resource['count']);
            }

            $users[$id] = $user;
        }

        $person = $merger->getTargetPerson($users);

        $dialog = $this->getHelperSet()->get('dialog');

        array_walk($usernames, function (&$value, $key) { $value = strtolower($value); });
        $key = array_search(strtolower($input->getOption('target')), $usernames);
        if ($key === false) {
            do {
                $choosen = $dialog->ask(
                        $output,
                        sprintf('Please choose target user (%d-%d) or oress CTRL+D to abort: ', 1, count($users)),
                        null
                );
            } while (!preg_match('/^[0-9]+$/', $choosen--) || !array_key_exists($choosen, $users));
        } else {
            $choosen = $key;
        }

        $output->writeln('Target user: '.$users[$choosen]);
        $target = $users[$choosen];
        $others = array_diff($users, array($target));

        $start = microtime(true);
        $merger->merge($target, $others, $person);
        $elapsed = microtime(true) - $start;

        if ($elapsed < 1.0) {
            $message = 'less than one second';
        } else {
            $message = sprintf('%.2f seconds', $elapsed);
        }

        $output->writeln('<info>Operation took '.$message.'</info>');
    }

    private function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
