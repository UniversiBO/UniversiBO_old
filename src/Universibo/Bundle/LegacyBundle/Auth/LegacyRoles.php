<?php

namespace Universibo\Bundle\LegacyBundle\Auth;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class LegacyRoles
{
    const NONE = 0;
    const OSPITE = 1;
    const STUDENTE = 2;
    const COLLABORATORE = 4;
    const TUTOR = 8;
    const DOCENTE = 16;
    const PERSONALE = 32;
    const ADMIN = 64;
    const ALL = 127;
}
