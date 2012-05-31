<?php
namespace UniversiBO\Bundle\LegacyBundle\Notification;

use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

/**
 * PHPMailer Notification Sender
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PHPMailerSender extends AbstractSender
{
    /**
     * @var \PHPMailer
     */
    private $mailer;

    public function __construct(\PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function supports(NotificaItem $notification)
    {
        return 'mail' === $notification->getProtocollo();
    }

    protected function doSend(NotificaItem $notification)
    {
        $mailer = $this->mailer;

        $mailer->clearAddresses();
        $mailer->AddAddress($notification->getIndirizzo());

        $mailer->Subject = str_replace( "\n"," - ",  '[UniversiBO] '.$notification->getTitolo());
        $mailer->Body = $notification->getMessaggio();

        if (!$mailer->Send()) {
            throw new SenderException($mailer->ErrorInfo);
        }
    }
}
