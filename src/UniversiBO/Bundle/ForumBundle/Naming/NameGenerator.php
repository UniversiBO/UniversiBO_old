<?php
namespace UniversiBO\Bundle\ForumBundle\Naming;
/**
 * Forum Name Generator
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class NameGenerator
{
    const SEPARATOR = ' - ';
    /**
     * @var int
     */
    private $year;

    /**
     * @param int $year Academical Year
     */
    public function __construct($year)
    {
        $this->year = $year;
    }

    public function update($title)
    {
        return preg_replace('/aa ([0-9]{4})(\\/\\.\\.)?(\\/[0-9]{4})+/',
                'aa $1/../' . $this->getLast(), $title);
    }

    public function generate($subjectName, $professorName)
    {
        return $subjectName . self::SEPARATOR
                . $this->getAcademicalYearString() . self::SEPARATOR
                . $professorName;
    }

    /**
     * Year getter
     *
     * @return number
     */
    protected function getYear()
    {
        return $this->year;
    }

    protected function getLast()
    {
        return $this->getYear() + 1;
    }

    protected function getAcademicalYearString()
    {
        return 'aa ' . $this->getYear() . '/' . $this->getLast();
    }
}
