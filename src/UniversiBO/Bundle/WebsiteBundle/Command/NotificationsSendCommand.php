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
        $lock = $this->get('kernel')->getRootDir() . '/data/notifications.lock';

        if (!is_resource($fp = fopen($lock, 'a'))) {
            throw new \Exception('Cannot open lock file: ' . $lock);
        }

        flock($fp, LOCK_EX);

        $repo = $this
                ->get('universibo_legacy.repository.notifica.notifica_item');
        $sender = $this->get('universibo_legacy.notification.sender');
        $logger = $this->get('logger');
        
        foreach ($repo->findToSend() as $notification) {
            try {
                $sender->send($notification);
                $notification->setEliminata(true);
                $repo->update($notification);
                $msg = 'Successfully sent notification '. $notification->getIdNotifica();
                $output->writeln($msg);
                $logger->info($msg);
            } catch (\Exception $e) {
                $err = 'Exception sending notification '. $notification->getIdNotifica().': ' . $e->getMessage();
                $output->writeln($err);
                $logger->err($err);
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
