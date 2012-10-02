<?php

namespace Universibo\Bundle\LegacyBundle\Entity\Notifica;

use \DB;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class NotificaItemRepository extends DoctrineRepository
{
    public function find($id)
    {
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findMany(array $ids)
    {
        if (count($ids) == 0) {
            return array();
        }

        $db = $this->getConnection();

        //esegue $db->quote() su ogni elemento dell'array
        //array_walk($id_notifiche, array($db, 'quote'));

        if (count($ids) == 1)
            $values = $ids[0];
        else
            $values = implode(',', $ids);
        //function NotificaItem($id_notifica, $titolo, $messaggio, $dataIns, $urgente, $eliminata, $destinatario)
        $query = 'SELECT id_notifica, titolo, messaggio, timestamp, urgente, eliminata, destinatario FROM notifica WHERE id_notifica in ('
                . $values . ') AND eliminata!='
                . $db->quote(NotificaItem::ELIMINATA);
        //var_dump($query);
        $res = &$db->executeQuery($query);

        $rows = $res->rowCount();

        if ($rows == 0) {
            return false;
        }
        $notifiche_list = array();

        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            $notifiche_list[] = new NotificaItem($row[0], $row[1], $row[2],
                    $row[3], ($row[4] == NotificaItem::URGENTE),
                    ($row[5] == NotificaItem::ELIMINATA), $row[6]);
        }


        //var_dump($notifiche_list);
        return $notifiche_list;
    }

    public function findToSend()
    {
        $db = $this->getConnection();

        $query = 'SELECT id_notifica, titolo, messaggio, timestamp, urgente, eliminata, destinatario FROM notifica WHERE timestamp <= '
                . time() . ' AND eliminata='
                . $db->quote(NotificaItem::NOT_ELIMINATA);
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();

        if ($rows == 0) {
            return false;
        }

        $notifiche_list = array();

        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            $notifiche_list[] = new NotificaItem($row[0], $row[1], $row[2],
                    $row[3], ($row[4] == NotificaItem::URGENTE),
                    ($row[5] == NotificaItem::ELIMINATA), $row[6]);
        }

        return $notifiche_list;
    }

    public function update(NotificaItem $notification)
    {
        $db = $this->getConnection();
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
        $res = $db->executeQuery($query);
        //var_dump($query);

        $db->commit();
        $db->autoCommit(true);

        return true;
    }

    public function insert(NotificaItem $notification)
    {
        $db = $this->getConnection();

        $db->autoCommit(false);
        $next_id = $db->nextID('notifica_id_notifica');
        $return = true;
        $eliminata = ($notification->isEliminata()) ? NotificaItem::ELIMINATA
                : NotificaItem::NOT_ELIMINATA;
        $urgente = ($notification->isUrgente()) ? NotificaItem::URGENTE
                : NotificaItem::NOT_URGENTE;
        //id_notifica urgente messaggio titolo timestamp destinatario eliminata

        $query = 'INSERT INTO notifica (id_notifica, urgente, messaggio, titolo, timestamp, destinatario, eliminata) VALUES '
                . '( ' . $next_id . ' , ' . $db->quote($urgente) . ' , '
                . $db->quote($notification->getMessaggio()) . ' , '
                . $db->quote($notification->getTitolo()) . ' , '
                . $db->quote($notification->getDataIns()) . ' , '
                . $db->quote($notification->getDestinatario()) . ' , '
                . $db->quote($eliminata) . ' ) ';
        //echo $query;
        $res = $db->executeQuery($query);
        //var_dump($query);

        $notification->setIdNotifica($next_id);

        $db->commit();
        $db->autoCommit(true);
    }
}
