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

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function supports(NotificaItem $notification)
    {
        return 'mail' === $notification->getProtocollo();
    }

    protected function doSend(NotificaItem $notification)
    {
        $message = Swift_Message::newInstance()
                ->setFrom(array('associazione.universibo@unibo.it' => 'Associazione UniversiBO'))
                ->setTo($notification->getIndirizzo())
                ->setSubject(
                        str_replace("\n", " - ",
                                '[UniversiBO] ' . $notification->getTitolo()))
                ->setBody($notification->getMessaggio());

        $this->mailer->send($message);
    }
}
