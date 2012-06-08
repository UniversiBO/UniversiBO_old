<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Help;
use Doctrine\DBAL\Connection;

use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

class DoctrineItemRepository extends DoctrineRepository
{
    /**
     * @return Item[]
     */
    public function findAll()
    {
        $conn = $this->getConnection();
        $stmt = $conn
                ->query(
                        'SELECT id_help, titolo, contenuto, ultima_modifica, indice FROM help ORDER BY indice');

        return $this->fetchAll($stmt);
    }

    public function findMany(array $ids)
    {
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery(
                        'SELECT id_help, titolo, contenuto, ultima_modifica, indice FROM help WHERE id_help IN (?) ORDER BY indice',
                        array($ids), array(Connection::PARAM_INT_ARRAY));

        return $this->fetchAll($stmt);
    }

    private function fetchAll($stmt)
    {
        $items = array();

        while (false !== ($row = $stmt->fetch(\PDO::FETCH_ASSOC))) {
            $items[] = $this->rowToItem($row);
        }

        return $items;
    }

    /**
     * @param array $row
     * @return Item
     */
    private function rowToItem($row)
    {
        $item = new Item();

        $item->setId($row['id_help']);
        $item->setTitle($row['titolo']);
        $item->setContent($row['contenuto']);
        $item->setLastEdit($row['ultima_modifica']);
        $item->setIndex($row['indice']);

        return $item;
    }
}
