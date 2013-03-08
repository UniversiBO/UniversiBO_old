<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

class CanaleTest extends EntityTest
{
    /**
     * @var Canale
     */
    private $canale;

    protected function setUp()
    {
        $this->canale = new Canale(41, 64, time(), Canale::CDEFAULT, '', 'Goofy', 2, true, true, false, 0, 0, true, true);
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->canale, $name, $value);
    }

    public function accessorDataProvider()
    {
        return array(
                array('idCanale', rand()),
                array('permessi', rand()),
                array('servizioFilesStudenti', true),
                array('servizioFilesStudenti', false),
                array('servizioFiles', true),
                array('servizioFiles', false),
                array('servizioForum', true),
                array('servizioForum', false),
                array('visite', rand()),
        );
    }
}
