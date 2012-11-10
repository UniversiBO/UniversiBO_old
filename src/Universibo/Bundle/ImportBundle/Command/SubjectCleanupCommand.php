<?php
namespace Universibo\Bundle\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clean up unused subjects
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SubjectCleanupCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('universibo:cleanup:subject')
            ->setDescription('Clean up unused subjects')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
$query = <<<EOT
DELETE
    FROM classi_materie cm
    WHERE NOT EXISTS
    (
        SELECT i.*
            FROM prg_insegnamento i
            WHERE i.cod_materia = m.cod_materia
    )
    AND NOT EXISTS
    (
        SELECT s.*
            FROM prg_sdoppiamento s
            WHERE s.cod_materia = cm.cod_materia
    )
EOT;
        $affected = $this->get('doctrine.dbal.default_connection')->executeUpdate($query);

        $output->writeln($affected . ' records deleted.');
    }
}
