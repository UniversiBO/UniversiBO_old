<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBInsegnamentoRepository extends DBRepository
{
    /**
     * @var DBPrgAttivitaDidatticaRepository
     */
    private $programmaRepository;

    /**
     * Channel repository
     *
     * @var ChannelRepository
     */
    private $channelRepository;

    /**
     * Constructor
     *
     * @param ConnectionWrapper                $db
     * @param DBPrgAttivitaDidatticaRepository $programmaRepository
     * @param ChannelRepository                $channelRepository
     */
    public function __construct(ConnectionWrapper $db, DBPrgAttivitaDidatticaRepository $programmaRepository,
            ChannelRepository $channelRepository)
    {
        parent::__construct($db);

        $this->programmaRepository = $programmaRepository;
        $this->channelRepository = $channelRepository;
    }

    /**
     *
     * @param  integer              $channelId
     * @return boolean|Insegnamento
     */
    public function findByChannelId($channelId)
    {
        $db = $this->getDb();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, id_canale, files_studenti_attivo FROM canale WHERE id_canale = '
                . $db->quote($channelId) . ';';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();
        $row = $this->fetchRow($res);
        $res->free();

        if ($rows > 1) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array(
                                    'msg' => 'Errore generale database: canale insegnamento non unico',
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        if ($rows == 0)
            return false;

        $elenco_attivita = $this->programmaRepository->findByChannelId($channelId);

        return new Insegnamento($row[12], $row[5], $row[4], $row[0],
                $row[2], $row[1], $row[3], $row[7] == 'S', $row[6] == 'S',
                $row[8] == 'S', $row[9], $row[10], $row[11] == 'S',
                $row[13] == 'S', $elenco_attivita);
    }

    /**
     * Finds all channels of type "Insegnamento"
     *
     * @param  boolean        $full
     * @return Insegnamento[]
     */
    public function findAll($full = false)
    {
        $channels = $this->channelRepository->findByType(self::INSEGNAMENTO);

        foreach ($channels as $channel) {
            $channelId = $channel->getId();
            $attivita = $full ? $this->programmaRepository->findByChannelId($channelId) : array();

            $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

            $insegnamenti[] = new Insegnamento($channelId,
                    $channel->getLegacyGroups(), $ultima_modifica,
                    Canale::INSEGNAMENTO, '', $channel->getName(),
                    $channel->getHits(), $channel->hasService('news'),
                    $channel->hasService('files'), $channel->hasService('forum'),
                    $channel->getForumId(), $channel->getForumId(),0,
                    $channel->hasService('links'), $channel->hasService('student_files'),$attivita);
        }

        return $insegnamenti;
    }
}
