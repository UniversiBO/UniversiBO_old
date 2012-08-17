<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\LegacyBundle\Entity\Questionario;

class QuestionarioTest extends UniversiBOEntityTest
{
    /**
     * @var Questionario
     */
    private $questionario;

    protected function setUp()
    {
        $this->questionario = new Questionario();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->questionario, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('id', rand()),
                array('data', rand()),
                array('nome', 'Mario'),
                array('cognome', 'Rossi'),
                array('mail', 'hello@example.com'),
                array('telefono', '3401234567'),
                array('tempoDisponibile', 42),
                array('tempoInternet', 42),
                array('attivitaOffline', 'S'),
                array('attivitaModeratore', 'S'),
                array('attivitaContenuti', 'S'),
                array('attivitaTest', 'S'),
                array('attivitaGrafica', 'S'),
                array('attivitaProgettazione', 'S'),
                array('altro', 'Hello '.rand()),
                array('idUtente', 43),
                array('cdl', 'gestionale')
        );
    }
}
