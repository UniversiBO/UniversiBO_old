<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Help;

use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

class DoctrineTopicRepository extends DoctrineRepository
{
    public function findAll()
    {
        $conn = $this->getConnection();
        $builder = $conn->createQueryBuilder();
        $stmt = $builder
            ->select('ht.riferimento, ht.titolo, ht.indice')
            ->from('help_topic', 'ht')
            ->orderBy('ht.indice')
            ->execute();

        return $this->fetchAll($stmt);
    }

    public function find($id)
    {
        $conn = $this->getConnection();
        $builder = $conn->createQueryBuilder();
        $stmt = $builder
            ->select('ht.riferimento, ht.titolo, ht.indice')
            ->from('help_topic', 'ht')
            ->where('riferimento = ?')
            ->setParameter(0, $id)
            ->execute();

        return $this->fetchOne($stmt);
    }

    private function fetchAll($stmt)
    {
        $items = array();

        while (false !== ($row = $stmt->fetch(\PDO::FETCH_ASSOC))) {
            $items[] = $this->rowToItem($row);
        }

        return $items;
    }

    private function fetchOne($stmt)
    {
        if (false !== ($row = $stmt->fetch(\PDO::FETCH_ASSOC))) {
            return $this->rowToItem($row);
        }

        return null;
    }

    /**
     * @param  array $row
     * @return Topic
     */
    private function rowToItem($row)
    {
        $item = new Topic();

        $item->setReference($row['riferimento']);
        $item->setTitle($row['titolo']);
        $item->setIndex($row['indice']);

        return $item;
    }
}
