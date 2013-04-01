<?php
namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface ConfigDAOInterface
{
    /**
     * @param  string $name
     * @return mixed
     */
    public function getValue($name);
}
