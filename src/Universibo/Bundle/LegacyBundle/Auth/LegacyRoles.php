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

    public static $map = array (
            'singular' => array (
                    self::OSPITE => "Ospite",
                    self::STUDENTE => "Studente",
                    self::COLLABORATORE => "Studente",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docente",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Studente"
            ),
            'plural' => array (
                    self::OSPITE => "Ospiti",
                    self::STUDENTE => "Studenti",
                    self::COLLABORATORE => "Studenti",
                    self::TUTOR => "Tutor", self::DOCENTE => "Docenti",
                    self::PERSONALE => "Personale non docente",
                    self::ADMIN => "Studenti"
            )
        }
    );
}
