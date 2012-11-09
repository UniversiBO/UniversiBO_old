<?php
namespace Universibo\Bundle\ImportBundle\CSV;

use EasyCSV\Reader;

/**
 * CeSIA format CSV reader
 */
class CesiaReader extends Reader
{
    /**
     * Class constructor
     *
     * @param string $path file name
     * @param string $mode fopen mode
     */
    public function __construct($path, $mode = 'r+')
    {
        $this->_delimiter = ';';

        parent::__construct($path, $mode);
    }
}
