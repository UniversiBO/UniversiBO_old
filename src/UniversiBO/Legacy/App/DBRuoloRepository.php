<?php

namespace UniversiBO\Legacy\App;

/**
 * Ruolo repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBUserRepository extends DBRepository
{
    /**
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db)
    {
        parent::__construct($db);
    }
}