<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use PDO;
use Universibo\Bundle\DidacticsBundle\Entity\School;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCdlRepository extends DBRepository
{
    /**
     * Channel repository
     *
     * @var ChannelRepository
     */
    private $channelRepository;

    public function __construct(ConnectionWrapper $db, ChannelRepository $channelRepository)
    {
        parent::__construct($db);

        $this->channelRepository = $channelRepository;
    }

    /**
     * @return boolean|Cdl[]
     */
    public function findAll()
    {
        $db = $this->getDb();

        $query = 'SELECT cod_corso FROM classi_corso WHERE 1 = 1';

        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

            return false;
        }

        $elencoCdl = array();

        while ($row = $this->fetchRow($res)) {
            //echo $row[0];
            if ( ($elencoCdl[] = $this->findByCodice($row[0]) ) === false )
                return false;
        }

        return $elencoCdl;
    }

    public function findByIdCanale($id)
    {
        $channel = $this->channelRepository->find($id);
        if (null === $channel) {
            return null;
        }

        $db = $this->getConnection();
        $query = 'SELECT cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM classi_corso WHERE id_canale = ?';

        $result = $db->executeQuery($query, [$id]);
        $row = $result->fetch();

        if (!$row) {
            return null;
        }

        $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

        return new Cdl($id, $channel->getLegacyGroups(), $ultima_modifica,
                (int) $channel->getType(), '', $channel->getName(), $channel->getHits(),
                $channel->hasService('news'), $channel->hasService('files'),
                $channel->hasService('forum'), $channel->getForumId(), 0,
                $channel->hasService('links'), $channel->hasService('student_files'),
                $row['cod_corso'], $row['desc_corso'], $row['cat_id'], $row['cod_fac'],
                $row['cod_doc'], $row['cat_id']
        );
    }

    public function findByCodice($codice)
    {
        $db = $this->getConnection();

        // LA PRIMA QUERY E' QUELLA CHE VA BENE, MA BISOGNA ALTRIMENTI SISTEMARE IL DB
        //E VERIFICARE CHE METTENDO DIRITTI = 0 IL CANALE NON VENGA VISUALIZZATO
        //$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,
        //			 a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale AND b.cod_corso = '.$db->quote($codice);

        $query = 'SELECT id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM  classi_corso WHERE cod_corso = ?';
        $stmt = $db->executeQuery($query, [$codice]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $channel = $this->channelRepository->find($id = (int) $row['id_canale']);
        if (null === $channel) {
            return null;
        }

        $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

        return new Cdl($id, $channel->getLegacyGroups(), $ultima_modifica,
                (int) $channel->getType(), '', $channel->getName(), $channel->getHits(),
                $channel->hasService('news'), $channel->hasService('files'),
                $channel->hasService('forum'), $channel->getForumId(), 0,
                $channel->hasService('links'), $channel->hasService('student_files'),
                $row['cod_corso'], $row['desc_corso'], $row['cat_id'], $row['cod_fac'],
                $row['cod_doc'], $row['cat_id']
        );
    }

    public function findBySchool($school, $annoAccademico = null)
    {
        if ($school instanceof School) {
            $school = $school->getId();
        }

        $db = $this->getConnection();

        if ($annoAccademico !== null) {
            $and = <<<EOT
AND EXISTS (
    SELECT p.*
    FROM prg_insegnamento p
    WHERE
            b.cod_corso = p.cod_corso
        AND p.anno_accademico = {$db->quote($annoAccademico)}
)
EOT;
        } else {
            $and = '';
        }

        $query = <<<EOT
SELECT
    b.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id

    FROM classi_corso b, schools_degree_courses sdc
        WHERE
                b.id = sdc.degree_course_id
            AND sdc.school_id = ?
EOT;

        $res = $db->executeQuery($query, array($school));

        /*public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine, $nome, $visite,
                 $news_attivo, $files_attivo, $forum_attivo, $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo,
                 $cod_cdl, $nome_cdl, $categoria_cdl, $cod_facolta_padre, $cod_doc, $forum_cat_id)*/

        $elenco = array();
        while (false !== ($row = $res->fetch(PDO::FETCH_NUM))) {
            $channel = $this->channelRepository->find($row[0]);

            $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;
            $cdl = new Cdl($channel->getId(), $channel->getLegacyGroups(), $ultima_modifica,
                    (int) $channel->getType(), '', $channel->getName(), $channel->getHits(),
                    $channel->hasService('news'), $channel->hasService('files'),
                    $channel->hasService('forum'), $channel->getForumId(),
                    $channel->getForumGroupId(), $channel->hasService('links'),
                    $channel->hasService('student_files'),
                    $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);

            $elenco[] = $cdl;
        }

        return $elenco;
    }

    /**
     * Updates a cdl
     *
     * @todo create canale
     * @param Cdl $cdl
     */
    public function update(Cdl $cdl)
    {
        $db = $this->getDb();

        $query = 'UPDATE classi_corso SET cat_id = '.$db->quote($cdl->getForumCatId()).
        ', cod_corso = '.$db->quote($cdl->getCodiceCdl()).
        ', desc_corso = '.$db->quote($cdl->getNome()).
        ', cod_fac = '.$db->quote($cdl->getCodiceFacoltaPadre()).
        ', categoria = '.$db->quote($cdl->getCategoriaCdl()).
        ', cod_doc =' .$db->quote($cdl->getCodDocente()).
        ' WHERE id_canale = '.$db->quote($cdl->getIdCanale());

        $res = $db->query($query);
        //		$rows =  $db->affectedRows();
        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',array('msg'=>$query,'file'=>__FILE__,'line'=>__LINE__));
    }

    public function insert(Cdl $cdl)
    {
        $db = $this->getDb();

        $query = 'INSERT INTO classi_corso (cod_corso, desc_corso, categoria, cod_doc, cod_fac, id_canale) VALUES ('.
                $db->quote($cdl->getCodiceCdl()).' , '.
                $db->quote($cdl->getNome()).' , '.
                $db->quote($cdl->getCategoriaCdl()).' , '.
                $db->quote($cdl->getCodDocente()).' , '.
                $db->quote($cdl->getCodiceFacoltaPadre()).' , '.
                $db->quote($cdl->getIdCanale()).' )';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

            return false;
        }

        return true;
    }
}
