<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\WebsiteBundle\Entity\Collaborator;

class CollaboratorTest extends EntityTest
{
    /**
     * @var Collaborator
     */
    private $collaborator;

    /**
     *
     */
    protected function setUp()
    {
        $this->collaborator = new Collaborator();
    }

    /**
     *
     * @dataProvider accessorDataProvider
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->collaborator, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('intro', 'Lorem ipsum'),
                array('contact', 'hello'),
                array('goals', 'find a girlfriend'),
                array('role', 'aaaa'),
                array('show', true),
                array('show', false),
        );
    }
}
