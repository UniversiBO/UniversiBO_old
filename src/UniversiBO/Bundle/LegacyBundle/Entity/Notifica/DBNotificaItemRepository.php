<?php

namespace UniversiBO\Bundle\LegacyBundle\Entity\Notifica;
/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBNotificaItemRepository extends DBRepository
{
    public function update(NotificaItem $notification)
    {
        $db = $this->getDb();
        $db->autoCommit(false);

        $urgente = ($notification->isUrgente()) ? NotificaItem::URGENTE
                : NotificaItem::NOT_URGENTE;
        $eliminata = ($notification->isEliminata()) ? NotificaItem::ELIMINATA
                : NotificaItem::NOT_ELIMINATA;
        $query = 'UPDATE notifica SET titolo = '
                . $db->quote($notification->getTitolo()) . ' , timestamp = '
                . $db->quote($notification->getDataIns()) . ' , eliminata = '
                . $db->quote($eliminata) . ' , urgente = '
                . $db->quote($urgente) . ' , messaggio = '
                . $db->quote($notification->getMessaggio())
                . ' WHERE id_notifica = '
                . $db->quote($notification->getIdNotifica());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $db->commit();
        $db->autoCommit(true);

        return true;
    }
}
