<?php
namespace Universibo\Bundle\LegacyBundle\Notification;

use InvalidArgumentException;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * Notifier sender chain
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SenderChain implements SenderInterface
{
    /**
     * @var SenderInterface[]
     */
    private $senders = array();

    public function supports(NotificaItem $notification)
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($notification)) {
                return true;
            }
        }

        return false;
    }

    public function register(SenderInterface $sender)
    {
        if (!in_array($sender, $this->senders, true)) {
            $this->senders[] = $sender;
        }
    }

    public function unregister(SenderInterface $sender)
    {
        if (false === ($key = array_search($sender, $this->senders, true))) {
            throw new InvalidArgumentException('Sender was not registered');
        }

        unset($this->senders[$key]);
    }

    public function send(NotificaItem $notification)
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($notification)) {
                return $sender->send($notification);
            }
        }

        throw new InvalidArgumentException('Protocol not supported');
    }
}
