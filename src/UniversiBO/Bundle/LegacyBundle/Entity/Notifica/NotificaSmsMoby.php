<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Notifica;
/**
 *
 * NotificaSmsMoby class
 *
 * Rappresenta una singola Notifica di tipo Sms.
 *
 * @package Notifica
 * @version 2.0.0
 * @author GNU/Mel <gnu.mel@gmail.com>
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2003
 */
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

use UniversiBO\Bundle\LegacyBundle\Notification\MobytSender;

class NotificaSmsMoby extends NotificaItem
{

    public function __construct($id_notifica, $titolo, $messaggio, $dataIns,
            $urgente, $eliminata, $destinatario)
    {
        //$id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario
        parent::__construct($id_notifica, $titolo, $messaggio, $dataIns,
                $urgente, $eliminata, $destinatario);
    }

    /**
     * Overwrite the send (abstract) function of the base class
     *
     * @return boolean true "success" or false "failed"
     */
    public function send(FrontController $fc)
    {
        $sender = new MobytSender($fc->getSmsMoby());

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

        return new NotificaSmsMoby($not->getIdNotifica(), $not->getTitolo(),
                $not->getMessaggio(), $not->getTimestamp(), $not->isUrgente(),
                $not->isEliminata(), $not->getDestinatario());
    }
}
