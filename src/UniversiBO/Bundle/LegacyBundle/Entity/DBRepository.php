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
    public function __construct(\DB_common $db, $convert = true)
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
     * @param mixed $param
     * @throws Exception
     */
    protected function throwError($level, $param)
    {
        if (!defined($level)) {
            throw new Exception($param['msg']);
        }

        Error::throwError(constant($level), $param);
    }

    protected function convertRowToUtf8(&$row)
    {
        array_walk($row,
                function (&$item, $key)
                {
                    if (gettype($item) === 'string') {
                        $item = mb_convert_encoding($item, 'utf-8',
                                'iso-8859-1');
                    }
                });
    }
}
