<?php
namespace UniversiBO\Bundle\WebsiteBundle\Command;

use Zend\Search\Lucene\Document\Field;

use Zend\Search\Lucene\Document;

use Zend\Search\Lucene\Lucene;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Batch rename users
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class LuceneBuildIndexCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this->setName('lucene:build-index')
                ->setDescription('Build lucene index');
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
        $repository = $this->get('universibo_legacy.repository.news.newsitem');

        $index = Lucene::create($this->get('kernel')->getRootDir().'/data/lucene');

        foreach($repository->findAll() as $news) {
            $doc = new Document();

            $doc->addField(Field::text('title', $news->getTitolo()));
            $doc->addField(Field::text('content', $news->getNotizia()));

            $index->addDocument($doc);
        }
    }

    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Prints a message only if verbose
     *
     * @param string          $message
     * @param OutputInterface $output
     */
    private function verboseMessage($message, OutputInterface $output)
    {
        if ($this->verbose) {
            $output->writeln($message);
        }
    }
}
