<?php
/**
 * @license GPLv3
 * @copyright (c) 2012, Associazione UniversiBO
 */
namespace Universibo\Bundle\ForumBundle\Naming;

/**
 * Forum Name Generator
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class NameGenerator
{
    const SEPARATOR = ' - ';

    /**
     * Updates the forum name with new year
     *
     * @param  string  $title current title
     * @param  integer $year
     * @return string  new title
     */
    public function update($title, $year)
    {
        return preg_replace('/aa ([0-9]{4})(\\/\\.\\.)?(\\/[0-9]{4})+/',
                'aa $1/../' . ($year + 1), $title);
    }

    /**
     * Generates a new forum name
     * @param  string  $subjectName
     * @param  string  $professorName
     * @param  integer $year
     * @return string
     */
    public function generate($subjectName, $professorName, $year)
    {
        return $subjectName . self::SEPARATOR
                . $this->getAcademicYearString($year) . self::SEPARATOR
                . $professorName;
    }

    /**
     * Gets Academic year string
     *
     * @param  integer $year
     * @return string
     */
    protected function getAcademicYearString($year)
    {
        return 'aa ' . $year . '/' . ($year + 1);
    }
}
