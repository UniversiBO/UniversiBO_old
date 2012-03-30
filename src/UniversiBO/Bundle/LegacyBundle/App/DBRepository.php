<?php

namespace UniversiBO\Bundle\LegacyBundle\App;

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
}