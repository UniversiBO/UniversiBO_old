<?php

namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Notifica;

use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;
use Universibo\Bundle\LegacyBundle\Tests\Entity\UniversiBOEntityTest;

/**
 * Description of NotificaItemTest
 *
 * @author davide
 */
class NotificaItemTest extends UniversiBOEntityTest
{
    private $notifica;

    protected function setUp()
    {
        $this->notifica = new NotificaItem(1,'','',0,false,false,'');
    }

    /**
     * @dataProvider provider
     * @param string $field
     * @param mixed  $value
     */
    public function testAccessors($field, $value)
    {
        $this->autoTestAccessor($this->notifica, $field, $value);
    }

    public function provider()
    {
        return array(
            array('destinatario', 'mail://test@example.com'),
            array('urgente', true),
            array('urgente', false),
            array('idNotifica', rand())
        );
    }
}
