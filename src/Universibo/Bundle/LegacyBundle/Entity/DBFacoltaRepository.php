<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;

/**
 * Facolta repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBFacoltaRepository extends DBRepository
{
    /**
     * @var DBCanaleRepository
     */
    private $canaleRepository;

    /**
     * Channel repository
     *
     * @var ChannelRepository
     */
    private $channelRepository;

    /**
     * Constructor
     *
     * @param ConnectionWrapper  $db
     * @param DBCanaleRepository $canaleRepository
     * @param ChannelRepository  $channelRepository
     */
    public function __construct(ConnectionWrapper $db, DBCanaleRepository $canaleRepository,
            ChannelRepository $channelRepository)
    {
        parent::__construct($db);

        $this->canaleRepository = $canaleRepository;
        $this->channelRepository = $channelRepository;
    }

    /**
     * @return Facolta
     */
    public function find($id)
    {
        $channel = $this->channelRepository->find($id);
        if (null === $channel) {
            return null;
        }

        $db = $this->getConnection();

        $query = 'SELECT id_canale, cod_fac, desc_fac, url_facolta FROM facolta b WHERE b.id_canale = ?';
        $res = $db->executeQuery($query, array($id));
        $row = $res->fetch();

        if (!$row) {
            return null;
        }

        $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

        return new Facolta(
            $channel->getId(), $channel->getLegacyGroups(),
            $ultima_modifica, (int) $channel->getType(), '',
            $channel->getName(), $channel->getHits(),
            $channel->hasService('news'), $channel->hasService('files'),
            $channel->hasService('forum'), $channel->getForumId(), 0,
            $channel->hasService('links'), $channel->hasService('student_files'),
            $row['cod_fac'], $row['desc_fac'], $row['url_facolta']
        );
    }

    /**
     * @return Facolta[]
     */
    public function findAll()
    {
        $db = $this->getConnection();

        $query = 'SELECT id_canale, cod_fac, desc_fac, url_facolta FROM facolta b ORDER BY desc_fac';
        $res = $db->executeQuery($query);

        $facolta = array();

        while (false !== ($row = $res->fetch())) {
            $channel = $this->channelRepository->find($row['id_canale']);
            if (null !== $channel) {
                $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;
                $facolta[] = new Facolta(
                    $channel->getId(), $channel->getLegacyGroups(),
                    $ultima_modifica, (int) $channel->getType(), '',
                    $channel->getName(), $channel->getHits(),
                    $channel->hasService('news'), $channel->hasService('files'),
                    $channel->hasService('forum'), $channel->getForumId(), 0,
                    $channel->hasService('links'), $channel->hasService('student_files'),
                    $row['cod_fac'], $row['desc_fac'], $row['url_facolta']
                );
            }
        }

        return $facolta;
    }

    public function update(Facolta $facolta)
    {
        $db = $this->getDb();

        $query = 'UPDATE facolta SET cod_fac = '
                . $db->quote($facolta->getCodiceFacolta()) . ', desc_fac = '
                . $db->quote($facolta->getNome()) . ', url_facolta = '
                . $db->quote($facolta->getUri()) . ' WHERE id_canale = '
                . $db->quote($facolta->getIdCanale());

        $res = $db->query($query);

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_DEFAULT',
                            array('msg' => $query, 'file' => __FILE__,
                                    'line' => __LINE__));
        }

        $this->canaleRepository->update($facolta);
    }

    public function insert(Facolta $facolta)
    {
        $db = $this->getDb();

        if ($this->canaleRepository->insert($facolta) != true) {
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => 'Errore inserimento Canale',
                            'file' => __FILE__, 'line' => __LINE__));

            return false;
        }

        $query = 'INSERT INTO facolta (cod_fac, desc_fac, url_facolta, id_canale) VALUES ('
        . $db->quote($facolta->getCodiceFacolta()) . ' , '
        . $db->quote($facolta->getNome()) . ' , '
        . $db->quote($facolta->getUri()) . ' , '
        . $db->quote($facolta->getIdCanale()) . ' )';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

            return false;
        }

        return true;
    }
}
