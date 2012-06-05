<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\Collaboratore;

class CollaboratoreTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $collaboratore = new Collaboratore(0, 'intro intro', '3381407176', 'obiettivi obiettivi', 'nofoto.gif', 'ruolo ruolo');

        $this->assertEquals(0, $collaboratore->getIdUtente());
        $this->assertEquals('intro intro', $collaboratore->getIntro());
        $this->assertEquals('3381407176', $collaboratore->getRecapito());
        $this->assertEquals('obiettivi obiettivi', $collaboratore->getObiettivi());
        $this->assertEquals('0_nofoto.gif', $collaboratore->getFotoFilename());
        $this->assertEquals('ruolo ruolo', $collaboratore->getRuolo());
    }
}
