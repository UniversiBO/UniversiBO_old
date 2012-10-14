<?php

namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3ConfigDAO extends AbstractDAO implements ConfigDAOInterface
{
    /**
     * @param  string $name
     * @return mixed
     */
    public function getValue($name)
    {
        $conn = $this->getConnection();

        $sql = '';
        $sql .= 'SELECT config_value ';
        $sql .= 'FROM '.$this->getPrefix().'config ';
        $sql .= 'WHERE config_name = ?';

        return $conn->fetchColumn($sql, array($name));
    }
}
