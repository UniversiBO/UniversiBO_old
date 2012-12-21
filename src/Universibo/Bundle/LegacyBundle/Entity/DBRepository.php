<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Error;
use Exception;
use PDO;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\PearDB\ResultWrapper;

abstract class DBRepository
{
    /**
     * @var ConnectionWrapper
     */
    private $db;

    /**
     * @var boolean
     */
    private $convert;

    /**
     * Class constructor
     *
     * @param ConnectionWrapper $db
     */
    public function __construct(ConnectionWrapper $db, $convert = false)
    {
        $this->db = $db;
        $this->convert = false;
    }

    protected function getDb()
    {
        return $this->db;
    }

    /**
     * @param  string    $level (name of the constant)
     * @param  mixed     $param
     * @throws Exception
     */
    protected function throwError($level, $param)
    {
        if (!defined($level)) {
            throw new Exception($param['msg']);
        }

        Error::throwError(constant($level), $param);
    }

    protected function fetchRow(ResultWrapper $res)
    {
        return $res->unwrap()->fetch(PDO::FETCH_NUM);
    }

    protected function isConvert()
    {
        return $this->convert;
    }

    protected function convertIfNeeded($item)
    {
        if ($this->isConvert()) {
            return self::convertToUtf8($item);
        }

        return $item;
    }

    public static function convertToUtf8($item, $from = 'iso-8859-1')
    {
        if (is_array($item)) {
            array_walk($item,
                    function (&$item, $key) {
                        $item = DBRepository::convertToUtf8($item);
                    });

            return $item;
        }

        if (gettype($item) === 'string') {
            return mb_convert_encoding($item, 'utf-8', $from);
        }

        return $item;
    }
}
