<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\Collaboratore;
use Universibo\Bundle\LegacyBundle\Entity\User;

class CollaboratoreTest extends UniversiBOEntityTest
{
    /**
     * @var Collaboratore
     */
    private $collaboratore;

    /**
     *
     */
    protected function setUp()
    {
        $this->collaboratore = new Collaboratore('intro intro',
                '3381407176', 'obiettivi obiettivi', 'nofoto.gif',
                'ruolo ruolo');
        $this->collaboratore->setUser(new User(0));
    }

    public function testGetters()
    {
        $collaboratore = $this->collaboratore;

        $this->assertEquals(0, $collaboratore->getIdUtente());
        $this->assertEquals('intro intro', $collaboratore->getIntro());
        $this->assertEquals('3381407176', $collaboratore->getRecapito());
        $this->assertEquals('obiettivi obiettivi',$collaboratore->getObiettivi());
        $this->assertEquals('0_nofoto.gif', $collaboratore->getFotoFilename());
        $this->assertEquals('ruolo ruolo', $collaboratore->getRuolo());
    }

    /**
     *
     * @dataProvider accessorDataProvider
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->collaboratore, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('intro', 'Lorem ipsum'),
                array('recapito', 'hello'),
                array('obiettivi', 'find a girlfriend'),
                array('ruolo', 'aaaa'),
                array('show', true),
                array('show', false),
        );
    }
}
