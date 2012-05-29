<?php
namespace UniversiBO\Bundle\LegacyBundle\Notification;

use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * SMS Notification Sender
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class MobytSender extends AbstractSender
{
    /**
     * One week TTL
     * @var int
     */
    const DEFAULT_TTL = 604800;

    /**
     * @var \mobytSms
     */
    private $mobyt;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Class constructor
     * @param \mobytSms $mobyt
     * @param int       $ttl
     */
    public function __construct(\mobytSms $mobyt, $ttl = self::DEFAULT_TTL)
    {
        $this->mobyt = $mobyt;
        $this->ttl = intval($ttl);
    }

    protected function doSend(NotificaItem $notification)
    {
        // won't send expired notifications
        if (time() > ($notification->getTimestamp() + $this->ttl)) {
            return true;
        }

        $result = $this->mobyt
                ->sendSms($notification->getIndirizzo(),
                        $notification->getMessaggio());


        if('OK' !== substr($result, 0, 2)) {
            throw new SenderException('Error: '.$result);
        }
    }

    public function supports(NotificaItem $notification)
    {
        return 'sms' === $notification->getProtocollo();
    }
}
