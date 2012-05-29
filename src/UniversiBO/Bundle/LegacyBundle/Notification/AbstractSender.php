<?php
namespace UniversiBO\Bundle\LegacyBundle\Notification;

use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * Base class for senders
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
abstract class AbstractSender implements Sender
{
    public function send(NotificaItem $notification)
    {
        if (!$this->supports($notification)) {
            throw new \InvalidArgumentException('protocol not supported');
        }

        return $this->doSend($notification);
    }

    protected abstract function doSend(NotificaItem $notification);
}
