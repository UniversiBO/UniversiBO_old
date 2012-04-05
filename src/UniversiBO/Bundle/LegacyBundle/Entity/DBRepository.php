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

    protected function errorOrException($level, $param)
    {
        if(is_null($levelValue = constant($level))) {
            throw new Exception($param['msg']);
        }

        Error::throwError($levelValue, $param);
    }
}