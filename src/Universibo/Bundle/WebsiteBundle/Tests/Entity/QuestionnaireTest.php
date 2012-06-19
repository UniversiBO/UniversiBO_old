<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\WebsiteBundle\Entity\Questionnaire;

class QuestionnaireTest extends EntityTest
{
    /**
     * @var Questionario
     */
    private $questionnaire;

    protected function setUp()
    {
        $this->questionnaire = new Questionnaire();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->questionnaire, $name, $value);
    }

    public function accessorDataProvider()
    {
        return array(
                array('createdAt', new \DateTime()),
                array('name', 'Mario'),
                array('surname', 'Rossi'),
                array('email', 'hello@example.com'),
                array('phone', '3401234567'),
                array('availableTime', 42),
                array('onlineTime', 42),
                array('offline', 'S'),
                array('moderator', true),
                array('content', true),
                array('test', true),
                array('graphics', true),
                array('designing', true),
                array('notes', 'Hello '.rand()),
                array('degreeCourse', 'gestionale')
        );
    }
}

