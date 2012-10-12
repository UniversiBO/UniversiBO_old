<?php
namespace Universibo\Bundle\ForumBundle\Naming;
/**
 * Forum Name Generator
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class NameGenerator
{
    const SEPARATOR = ' - ';

    public function update($title, $year)
    {
        return preg_replace('/aa ([0-9]{4})(\\/\\.\\.)?(\\/[0-9]{4})+/',
                'aa $1/../' . $this->getLast($year), $title);
    }

    public function generate($subjectName, $professorName, $year)
    {
        return $subjectName . self::SEPARATOR
                . $this->getAcademicalYearString($year) . self::SEPARATOR
                . $professorName;
    }

    protected function getLast($year)
    {
        return $year + 1;
    }

    protected function getAcademicalYearString($year)
    {
        return 'aa ' . $year . '/' . $this->getLast($year);
    }
}
