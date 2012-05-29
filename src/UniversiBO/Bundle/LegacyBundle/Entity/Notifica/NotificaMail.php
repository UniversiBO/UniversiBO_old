<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Notifica;

/**
 *
 * NotificaMail class
 *
 * Rappresenta una singola Notifica di tipo Mail.
 *
 * @package universibo
 * @subpackage Notifica
 * @version 2.0.0
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */

use UniversiBO\Bundle\LegacyBundle\Notification\PHPMailerSender;

use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

class NotificaMail extends NotificaItem
{
    public function __construct ($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario)
    {
        //$id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario
        parent::__construct($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario);
    }

    /**
    * Overwrite the send (abstract) function of the base class
    *
    * @return boolean true "success" or false "failed"
    */
    public function send(FrontController $fc) {

        //per usare l'SMTPkeepAlive usa il singleton
        $mailer = $fc->getMail(MAIL_KEEPALIVE_ALIVE);
        $sender = new PHPMailerSender($mailer);

        try {
            $sender->send($this);

            return true;
        } catch (SenderException $e) {
            return false;
        }
    }

    public static function factoryNotifica($id_notifica)
    {
        $not = NotificaItem::selectNotifica($id_notifica);
        $ret = new NotificaMail($not->getIdNotifica(), $not->getTitolo(), $not->getMessaggio(), $not->getTimestamp(), $not->isUrgente(), $not->isEliminata(), $not->getDestinatario());

         return $ret;
        //$notif=NotificaMail::selectNotifica($id_notifica);
        //$notifMail=new NotificaMail($notif,$fc);
        //return $notifMail;
    }
}
