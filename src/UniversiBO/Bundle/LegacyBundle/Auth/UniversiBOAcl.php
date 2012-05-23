<?php

namespace UniversiBO\Bundle\LegacyBundle\Auth;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItem;

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
        $this->handlers[self::BASE . 'Files\\FileItem']['read'] = array($this,
                'fileReadHandler');
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
            if ($object instanceof $key && array_key_exists($action, $handler)) {
                return call_user_func($handler[$action], $user, $object);
            }
        }

        return false;
    }

    private function fileReadHandler(User $user = null, FileItem $file)
    {
        return ($file->getPermessiVisualizza() & $this->getGroups($user)) !== 0;
    }

    private function canaleReadHandler(User $user = null, Canale $canale)
    {
        return ($canale->getPermessi() & $this->getGroups($user)) !== 0;
    }

    private function getGroups(User $user = null)
    {
        return is_null($user) ? User::OSPITE : $user->getGroups();
    }
}
