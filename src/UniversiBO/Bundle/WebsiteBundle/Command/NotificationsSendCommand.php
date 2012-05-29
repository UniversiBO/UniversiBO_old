<?php
namespace UniversiBO\Bundle\WebsiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Batch rename users
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class NotificationsSendCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this->setName('notifications:send')
                ->setDescription('Send queued notifications');
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
