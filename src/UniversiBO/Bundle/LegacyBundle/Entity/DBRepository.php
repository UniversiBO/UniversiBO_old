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
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db)
    {
        $this->db = $db;
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
        if(!defined($level)) {
            throw new Exception($param['msg']);
        }

        Error::throwError(constant($level), $param);
    }
}