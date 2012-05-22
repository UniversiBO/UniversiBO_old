<?php
namespace UniversiBO\Bundle\WebsiteBundle\Command;

use Zend\Search\Lucene\SearchIndexInterface;

use Symfony\Tests\Component\Routing\Fixtures\AnnotatedClasses\FooClass;

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
        $index = $this->get('universibo_website.search.lucene');
        foreach($index as $item) {
            $index->delete($item->id);
        }
        
        $this->buildFile($index);
        $this->buildNews($index);
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
    
    private function buildFile(SearchIndexInterface $index)
    {
        $repository = $this->get('universibo_legacy.repository.files.file_item');
        
        foreach($repository->findAll() as $file) {
        	$doc = new Document();
        
        	$doc->addField(Field::unStored('title', $file->getTitolo()));
        	$doc->addField(Field::unStored('username', $file->getUsername()));
        	$doc->addField(Field::unIndexed('dbId', $file->getIdFile()));
        	$doc->addField(Field::unIndexed('type', 'file'));
        
        	$index->addDocument($doc);
        }
    }
    
    private function buildNews(SearchIndexInterface $index)
    {
        $repository = $this->get('universibo_legacy.repository.news.newsitem');
        
        foreach($repository->findAll() as $news) {
        	$doc = new Document();
        
        	$doc->addField(Field::unStored('title', $news->getTitolo()));
        	$doc->addField(Field::unStored('username', $news->getUsername()));
        	$doc->addField(Field::unStored('content', $news->getNotizia()));
        	$doc->addField(Field::unIndexed('dbId', $news->getIdNotizia()));
        	$doc->addField(Field::unIndexed('type', 'news'));
        
        	$index->addDocument($doc);
        }
    }
}

