<?php
namespace Universibo\Bundle\WebsiteBundle\Command;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * Batch rename users
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class TestNotificationCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('notifications:test')
            ->setDescription('Sends a test notification')
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
        $repo = $this->get('universibo_legacy.repository.notifica.notifica_item');
        $notifica = new NotificaItem(0, 'Test mail', 'Test body', time(), false,
                false, 'mail://'.$this->getContainer()->getParameter('mailer_dev'));

        $repo->insert($notifica);
    }

    private function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
