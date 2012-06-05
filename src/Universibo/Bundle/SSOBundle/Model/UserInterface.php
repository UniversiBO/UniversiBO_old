<?php
namespace Universibo\Bundle\SSOBundle\Model;

interface UserInterface
{
    /**
     * @return string
     */
    public function getPrincipalName();
    
    /**
     * @param string $principalName
     * @return UserInterface
     */
    public function setPrincipalName($principalName);
}
