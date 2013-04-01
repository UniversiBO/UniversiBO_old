<?php
namespace Universibo\Bundle\ForumBundle\Tests\Naming;

use Universibo\Bundle\ForumBundle\Naming\NameGenerator;

class NameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $generator;

    const YEAR = 2011;

    protected function setUp()
    {
        $this->generator = new NameGenerator();
    }

    public function testUpdateOldType()
    {
        $in = 'COSTRUZIONE DI MACCHINE - aa 2003/2004/2005 - Curioni Sergio';
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2003/../2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->update($in, self::YEAR));
    }

    public function testUpdateNewType()
    {
        $in  = 'COSTRUZIONE DI MACCHINE - aa 2003/../2011 - Curioni Sergio';
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2003/../2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->update($in, self::YEAR));
    }

    public function testGenerate()
    {
        $expected = 'COSTRUZIONE DI MACCHINE - aa 2011/2012 - Curioni Sergio';

        $this->assertEquals($expected, $this->generator->generate('COSTRUZIONE DI MACCHINE', 'Curioni Sergio', self::YEAR));
    }
}
