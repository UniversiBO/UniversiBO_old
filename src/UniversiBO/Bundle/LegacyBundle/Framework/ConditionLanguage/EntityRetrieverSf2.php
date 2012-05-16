<?php
namespace UniversiBO\Bundle\LegacyBundle\Framework\ConditionLanguage;

use \DB_common;
use \Krono;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

use Symfony\Component\Security\Core\SecurityContextInterface;

class EntityRetrieverSf2 implements IExecutor
{
    private $entityTable = array();

    public function __constructor(DB_common $db, SecurityContextInterface $securityContext, Krono $krono)
    {
        $this->entityTable['user'] = $getUser = function() use($securityContext) {
            $token = $securityContext->getToken();

            if(is_null($token)) {
                return false;
            }

            return is_null($user = $token->getUser()) ? false : $user;
        };

        $this->entityTable['ruoli'] = function() use($getUser) {
            $user = $getUser();

            if($user instanceof User) {
                return $user->getRuoli();
            }


            return null;
        };

        $this->entityTable['db'] = function() use($db) {
            return $db;
        };

        $this->entityTable['krono'] = function() use($krono) {
        	return $krono;
        };
    }

    public function run($args)
    {
        //var_dump($name); die;
        if (!array_key_exists($args['codice'],$this->entityTable)) return false;

        return array($this->entityTable[$args['codice']]());
    }
}
