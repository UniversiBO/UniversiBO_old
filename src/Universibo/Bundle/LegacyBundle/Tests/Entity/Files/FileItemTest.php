<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Files;

use Universibo\Bundle\LegacyBundle\Tests\Entity\UniversiBOEntityTest;

use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

class FileItemTest extends UniversiBOEntityTest
{
    /**
     * @var User
     */
    private $file;

    protected function setUp()
    {
        $this->file = new FileItem(1, 255, 255, 81, '$titolo', '$descrizione', 45345345, 3443737, 34535, 0, 'hello.jpg', 1, 1, '', '', '', '', '', '', '');
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->file, $name, $value);
    }

    public function accessorDataProvider()
    {
        return array(
                array('idFile', 42),
                array('categoriaDesc', 'esami')
        );
    }
}
