<?php

namespace Universibo\Bundle\WebsiteBundle\Search;
use Zend\Search\Lucene\Lucene;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * Lucene factory
 */
class LuceneFactory
{
    /**
     * @var string
     */
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @returns \Zend\Search\Lucene\SearchIndexInterface
     */
    public function get()
    {
        if (!$this->checkEsists()) {
            return Lucene::create($this->path);
        } else {
            return Lucene::open($this->path);
        }
    }

    private function checkEsists()
    {
        return file_exists($this->path . '/read.lock.file');
    }
}
