<?php
namespace Universibo\Bundle\ImportBundle\Command;

use Doctrine\ORM\EntityManager;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\DidacticsBundle\Entity\Subject;
use Universibo\Bundle\ImportBundle\CSV\CesiaReader;

/**
 * Batch import subjects from CSV
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SubjectImportCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('universibo:import:subject')
            ->setDescription('Batch import subjects from CSV')
            ->addArgument('filename', InputArgument::REQUIRED)
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
        $subjectRepo = $this
            ->getContainer()
            ->get('universibo_didactics.repository.subject')
        ;

        $em = $this
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
        ;

        $filename = $input->getArgument('filename');
        if (!is_readable($filename)) {
            throw new RuntimeException('Cannot read file: '.$filename.'.');
        }

        if (!is_file($filename)) {
            throw new RuntimeException('Path '.$filename.' is not a file.');
        }

        $reader = new CesiaReader($filename);

        $i = 0;
        while (false !== ($row = $reader->getRow())) {
            $output->write('Line: '.$reader->getLineNumber().' ');
            $code = $row['COD_MATERIA'];
            $description = trim($row['DESC_MATERIA']);

            $subject = $subjectRepo->findOneByCode($code);
            if ($subject === null) {
                $subject = new Subject();
                $subject->setCode($code);
                $subject->setDescription($description);

                $i++;
                $em->persist($subject);

                $output->writeln('Added: '.$code.' - '.$description);
            } else {
                $output->writeln('Exists: '.$code.' - '.$description);
            }

            if ($i >= 1000) {
                $this->flush($em, $output);
                $i=0;
            }
        }

        $this->flush($em, $output);
    }

    private function flush(EntityManager $em, OutputInterface $output)
    {
        $output->write('Flushing EntityManager...');
        $em->flush();
        $output->writeln('done.');
    }
}
