<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Docente;

class DocenteTest extends EntityTest
{
    /**
     * @var Canale
     */
    private $docente;

    protected function setUp()
    {
        $this->docente = new Docente(3, '031888', 'DENTI ENRICO');
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->docente, $name, $value);
    }

    public function accessorDataProvider()
    {
        return array(
                array('idUtente', rand())
        );
    }
}
