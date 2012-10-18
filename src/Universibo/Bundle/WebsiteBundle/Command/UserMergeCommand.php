<?php
namespace Universibo\Bundle\WebsiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }

    private function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
