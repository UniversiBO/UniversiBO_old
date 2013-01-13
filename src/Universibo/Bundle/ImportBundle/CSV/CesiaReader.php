<?php
namespace Universibo\Bundle\ImportBundle\CSV;

use EasyCSV\Reader;

/**
 * CeSIA format CSV reader
 */
class CesiaReader extends Reader
{
    /**
     * Cesia-specific delimiter
     * @var string
     */
    protected $delimiter = ';';
}
