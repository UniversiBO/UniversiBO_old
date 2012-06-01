<?php
namespace UniversiBO\Bundle\ForumBundle\Tests\Naming;

use UniversiBO\Bundle\ForumBundle\Naming\NameGenerator;

class NameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $generator;

    const YEAR = 2011;

    protected function setUp()
    {
        $this->generator = new NameGenerator(self::YEAR);
    }

    public function testUpdateOldType()
    {
        $in = 'COSTRUZIONE DI MACCHINE - aa 2003/2004/2005 - Curioni Sergio';
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2003/../2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->update($in));
    }

    public function testUpdateNewType()
    {
        $in  = 'COSTRUZIONE DI MACCHINE - aa 2003/../2011 - Curioni Sergio';
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2003/../2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->update($in));
    }

    public function testGenerate()
    {
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2011/2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->generate('COSTRUZIONE DI MACCHINE', 'Curioni Sergio'));
    }
}

