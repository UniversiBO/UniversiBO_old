<?php
/**
 * ChannelMigrateCommand class file
 */
namespace Universibo\Bundle\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * Channel migration command
 */
class ChannelMigrateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('universibo:migrate:channels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channelRepo = $this->getContainer()->get('universibo_legacy.repository.canale');

        $types = [
            Canale::CDEFAULT,
            Canale::CDL,
            Canale::FACOLTA,
            Canale::HOME,
            Canale::INSEGNAMENTO
        ];

        foreach ($types as $type) {
            $ids = $channelRepo->findManyByType($type);
        }
    }
}
