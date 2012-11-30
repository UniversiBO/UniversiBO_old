<?php
namespace Universibo\Bundle\LegacyBundle\Notification;

use Swift_Mailer;
use Swift_Message;
use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * PHPMailer Notification Sender
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SwiftMailerSender extends AbstractSender
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * From address and name
     *
     * @var array
     */
    private $from;

    public function __construct(Swift_Mailer $mailer, $fromAddress, $fromName)
    {
        $this->mailer = $mailer;
        $this->from = array($fromAddress => $fromName);
    }

    public function supports(NotificaItem $notification)
    {
        return 'mail' === $notification->getProtocollo();
    }

    protected function doSend(NotificaItem $notification)
    {
        $message = Swift_Message::newInstance()
                ->setFrom($this->from)
                ->setTo($notification->getIndirizzo())
                ->setSubject(
                        str_replace("\n", " - ",
                                '[UniversiBO] ' . $notification->getTitolo()))
                ->setBody($notification->getMessaggio());

        $this->mailer->send($message);
    }
}
