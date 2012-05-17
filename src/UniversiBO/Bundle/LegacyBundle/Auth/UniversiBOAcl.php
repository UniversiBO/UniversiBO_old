<?php

namespace UniversiBO\Bundle\LegacyBundle\Auth;
use UniversiBO\Bundle\LegacyBundle\Entity\DBRuoloRepository;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Entity\User;

class UniversiBOAcl
{
    const BASE = 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\';

    private $handlers = array();

    /**
     * @var DBRuoloRepository
     */
    private $ruoloRepository;

    public function __construct(DBRuoloRepository $ruoloRepository)
    {
        $this->ruoloRepository = $ruoloRepository;

        $this->handlers[self::BASE . 'Canale']['read'] = array($this,
                'canaleReadHandler');
    }

    public function canRead(User $user = null, $object)
    {
        return $this->canDoAction('read', $user, $object);
    }

    public function canWrite(User $user = null, $object)
    {
        return $this->canDoAction('write', $user, $object);
    }

    public function canDoAction($action, User $user = null, $object)
    {
        foreach ($this->handlers as $key => $handler) {
            if ($object instanceof $key) {
                return call_user_func($handler[$action], $user, $object);
            }
        }

        return false;
    }

    private function canaleReadHandler(User $user = null, Canale $canale)
    {
        $groups = is_null($user) ? User::OSPITE : $user->getGroups();

        if (($canale->getPermessi() & $groups) !== 0) {
            return true;
        }

        return false;
    }
}
