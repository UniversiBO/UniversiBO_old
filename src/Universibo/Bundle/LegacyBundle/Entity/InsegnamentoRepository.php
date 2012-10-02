<?php

namespace Universibo\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class InsegnamentoRepository extends DoctrineRepository
{
    /**
     * @var DBPrgAttivitaDidatticaRepository
     */
    private $programmaRepository;

    public function __construct(\DB_common $db, DBPrgAttivitaDidatticaRepository $programmaRepository, $convert = false)
    {
        parent::__construct($db);

        $this->programmaRepository = $programmaRepository;
    }

    public function findByChannelId($channelId)
    {
        $db = $this->getConnection();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, id_canale, files_studenti_attivo FROM canale WHERE id_canale = '
                . $db->quote($channelId) . ';';
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        if ($rows > 1) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array(
                                    'msg' => 'Errore generale database: canale insegnamento non unico',
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        if ($rows = 0)
            return false;

        $elenco_attivita = $this->programmaRepository->findByChannelId($channelId);

        return new Insegnamento($row[12], $row[5], $row[4], $row[0],
                $row[2], $row[1], $row[3], $row[7] == 'S', $row[6] == 'S',
                $row[8] == 'S', $row[9], $row[10], $row[11] == 'S',
                $row[13] == 'S', $elenco_attivita);
    }
}
