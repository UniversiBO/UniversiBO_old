<?php

namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \Error;
use \Exception;

abstract class DBRepository
{
    /**
     * @var \DB_common
     */
    private $db;

    /**
     * @var boolean
     */
    private $convert;

    /**
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db, $convert = false)
    {
        $this->db = $db;
        $this->convert = $convert;
    }

    protected function getDb()
    {
        return $this->db;
    }

    /**
     * @param string $level (name of the constant)
     * @param mixed  $param
     * @throws Exception
     */
    protected function throwError($level, $param)
    {
        if (!defined($level)) {
            throw new Exception($param['msg']);
        }

        Error::throwError(constant($level), $param);
    }

    protected function fetchRow(\DB_result $res)
    {
        $row = $res->fetchRow();

        if ($this->convert) {
            $row = self::convertToUtf8($row);
        }


        return $row;
    }

    protected function isConvert()
    {
        return $this->convert;
    }

    protected function convertIfNeeded($item)
    {
        if($this->isConvert()) {
            return self::convertToUtf8($item);
        }


        return $item;
    }

    public static function convertToUtf8($item, $from = 'iso-8859-1')
    {
        if (is_array($item)) {
            array_walk($item,
                    function (&$item, $key)
                    {
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
