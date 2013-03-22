<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\DBAL\DBALException;
use PDO;
use Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBPrgAttivitaDidatticaRepository extends DBRepository
{
    private $channelRepository;

    /**
     * Constructor
     *
     * @param ConnectionWrapper $db
     * @param ChannelRepository $channelRepository
     */
    public function __construct(ConnectionWrapper $db, ChannelRepository $channelRepository)
    {
        parent::__construct($db);

        $this->channelRepository = $channelRepository;
    }

    public function findMaxAcademicYearFacolta($cod_fac)
    {
        return $this->findAcademicYearFacolta($cod_fac, 'MAX');
    }

    public function findMinAcademicYearFacolta($cod_fac)
    {
        return $this->findAcademicYearFacolta($cod_fac, 'MIN');
    }

    public function findMaxAcademicYear($cod_cdl)
    {
        return $this->findAcademicYear($cod_cdl, 'MAX');
    }

    public function findMinAcademicYear($cod_cdl)
    {
        return $this->findAcademicYear($cod_cdl, 'MIN');
    }

    protected function findAcademicYear($cod_cdl, $mode)
    {
        $db = $this->getDb();

        $query =<<<EOT
SELECT $mode (anno_accademico)
    FROM prg_insegnamento
        WHERE
            cod_corso = {$db->quote($cod_cdl)}
EOT;
        $res = $db->query($query);

        if (DB::isError($res)) {
            throw new DBALException(DB::errorMessage($res));
        }

        $result = $res->fetchRow();
        $res->free();

        return $result[0];
    }

    protected function findAcademicYearFacolta($cod_fac, $mode)
    {
        $db = $this->getDb();

        $query =<<<EOT
SELECT $mode (p.anno_accademico)
    FROM prg_insegnamento p, classi_corso c
        WHERE
                p.cod_corso = c.cod_corso
            AND c.cod_fac = {$db->quote($cod_fac)}
EOT;

        $res = $db->query($query);

        if (DB::isError($res)) {
            throw new DBALException(DB::errorMessage($res));
        }

        $result = $res->fetchRow();
        $res->free();

        return $result[0];
    }

    public function findByCdlAndYear($cod_cdl, $anno_accademico)
    {
        $db = $this->getConnection();

        $cod_cdl         = $db->quote($cod_cdl);
        $anno_accademico = $db->quote($anno_accademico);

        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo ) *
        FROM (
        SELECT i.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '.$db->quote('N').' AS sdoppiato, null AS id_sdop
        FROM prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND cod_corso='.$cod_cdl.'
        UNION
        SELECT i.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo,  '.$db->quote('S').' AS sdoppiato, id_sdop
        FROM prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND i.cod_doc=d.cod_doc
        AND s.cod_corso='.$cod_cdl.'
        AND s.anno_accademico='.$anno_accademico.'
        ) AS cdl
        ) AS cdl1
        ORDER BY 19, 17, 10';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */

        /**
         * public function __construct($id_canale, $permessi, $ultima_modifica, $tipo_canale, $immagine,
    $nome, $visite, $news_attivo, $files_attivo, $forum_attivo,
    $forum_forum_id, $forum_group_id, $links_attivo,$files_studenti_attivo, $annoAccademico, $codiceCdl,
    $codInd, $codOri, $codMateria, $nomeMateria, $annoCorso,
    $codMateriaIns, $nomeMateriaIns, $annoCorsoIns, $codRil, $codModulo,
    $codDoc, $nomeDoc, $flagTitolareModulo, $tipoCiclo, $codAte,
    $annoCorsoUniversibo, $sdoppiato, $id_sdop)
         */

        $res = $db->executeQuery($query);
        $elenco = array();
        while (false !== ($row = $res->fetch(PDO::FETCH_NUM))) {
            $channel = $this->channelRepository->find($row[0]);
            $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

            $prgAtt = new PrgAttivitaDidattica($channel->getId(), $channel->getLegacyGroups(),
                    $ultima_modifica, (int) $channel->getType(), '', $channel->getName(),
                    $channel->getHits(), $channel->hasService('news'),
                    $channel->hasService('files'), $channel->hasService('forum'),
                    $channel->getForumId(), $channel->getForumGroupId(),
                    $channel->hasService('links'), $channel->hasService('student_files'),
                    $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8],
                    $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16],
                    $row[17], $row[18], $row[19]=='S' , $row[20]);

            $elenco[] = $prgAtt;
        }

        return $elenco;
    }

    public function update(PrgAttivitaDidattica $attivita)
    {
        $db = $this->getDb();

        if ($attivita->isSdoppiato())
            $query = 'UPDATE prg_sdoppiamento '
                    . 'SET anno_corso_universibo = '
                    . $db->quote($attivita->getAnnoCorsoUniversibo())
                    . ', tipo_ciclo = ' . $db->quote($attivita->getTipoCiclo())
                    . ' WHERE  	id_sdop='
                    . $db->quote($attivita->getIdSdop());
        else
            $query = 'UPDATE prg_insegnamento '
                    . 'SET anno_corso_universibo = '
                    . $db->quote($attivita->getAnnoCorsoUniversibo())
                    . ' , tipo_ciclo = '
                    . $db->quote($attivita->getTipoCiclo()) . ' , cod_doc = '
                    . $db->quote($attivita->getCodDoc()) . ' WHERE  id_canale='
                    . $db->quote($attivita->getIdCanale());

        $res = $db->query($query);
        //		var_dump($query);

        if (DB::isError($res))
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        $rows = $db->affectedRows();
        if ($rows >= 1)
            return true;
        elseif ($rows == 0)
            return false;
        else
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => 'Errore generale database',
                                    'file' => __FILE__, 'line' => __LINE__));
    }

    public function findByChannelId($channelId)
    {
        $db = $this->getConnection();

        $id_canale = $db->quote($channelId);
        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo, sdoppiato ) *
        FROM (
        SELECT i.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '
                . $db->quote('N')
                . ' AS sdoppiato, null AS id_sdop
        FROM prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.id_canale=' . $id_canale
                . '
        UNION
        SELECT i.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, '
                . $db->quote('S')
                . ' AS sdoppiato, id_sdop
        FROM prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND i.cod_doc=d.cod_doc
        AND i.id_canale=' . $id_canale
                . '
        ) AS cdl
        ) AS cdl1
        ORDER BY 19, 17, 10';

        $res = $db->executeQuery($query);

        $elenco = array();
        while (false !== ($row = $res->fetch())) {
            $channel = $this->channelRepository->find($row['id_canale']);

            $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

            $prgAtt = new PrgAttivitaDidattica($channel->getId(), $channel->getLegacyGroups(),
                    $ultima_modifica, $channel->getType(), '', $channel->getName(),
                    $channel->getHits(), $channel->hasService('news'), $channel->hasService('files'),
                    $channel->hasService('forum'), $channel->getForumId(),
                    $channel->getForumGroupId(), $channel->hasService('links'),
                    $channel->hasService('student_files'), $row['anno_accademico'],
                    $row['cod_corso'], 0, 0, $row['cod_materia'], $row['desc_materia'],
                    $row['anno_corso'], $row['cod_materia_ins'], $row['desc_materia_ins'],
                    $row['anno_corso_ins'], $row['cod_ril'], $row['cod_modulo'],
                    $row['cod_doc'], $row['nome_doc'], $row['flag_titolare_modulo'],
                    $row['tipo_ciclo'], $row['cod_ate'], $row['anno_corso_universibo'],
                    $row['sdoppiato'] === 'S', $row['id_sdop']
            );

            $elenco[] = $prgAtt;
        }

        return $elenco;
    }

    public function find($anno_accademico, $cod_corso, $cod_ind, $cod_ori,
            $cod_materia, $cod_materia_ins, $anno_corso, $anno_corso_ins,
            $cod_ril, $cod_ate)
    {
        $db = $this->getConnection();

        $anno_accademico = $db->quote( $anno_accademico );
        $cod_corso = $db->quote( $cod_corso );
        $cod_ind = $db->quote( $cod_ind );
        $cod_ori = $db->quote( $cod_ori );
        $cod_materia = $db->quote( $cod_materia );
        $cod_materia_ins = $db->quote( $cod_materia_ins );
        $anno_corso = $db->quote( $anno_corso );
        $anno_corso_ins = $db->quote( $anno_corso_ins );
        $cod_ril = $db->quote( $cod_ril );
        $cod_ate = $db->quote( $cod_ate );

        // 13
        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo, sdoppiato ) *
        FROM (
        SELECT i.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '.$db->quote('N').' AS sdoppiato, null AS id_sdop
        FROM prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND i.cod_corso='.$cod_corso.'
        AND i.cod_ind='.$cod_ind.'
        AND i.cod_ori='.$cod_ori.'
        AND i.cod_materia='.$cod_materia.'
        AND i.cod_materia_ins='.$cod_materia_ins.'
        AND i.anno_corso='.$anno_corso.'
        AND i.anno_corso_ins='.$anno_corso_ins.'
        AND i.cod_ril='.$cod_ril.'
        AND i.cod_ate='.$cod_ate.'
        UNION
        SELECT i.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, '.$db->quote('S').' AS sdoppiato, id_sdop
        FROM prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico='.$anno_accademico.'
        AND i.cod_corso='.$cod_corso.'
        AND i.cod_ind='.$cod_ind.'
        AND i.cod_ori='.$cod_ori.'
        AND i.cod_materia='.$cod_materia.'
        AND i.cod_materia_ins='.$cod_materia_ins.'
        AND i.anno_corso='.$anno_corso.'
        AND i.anno_corso_ins='.$anno_corso_ins.'
        AND i.cod_ril='.$cod_ril.'
        AND i.cod_ate='.$cod_ate.'
        ) AS cdl
        ) AS cdl1
        ORDER BY 20, 19, 17, 10';

        $result = $db->executeQuery($query);

        $elenco = array();
        while (false !== ($row = $result->fetch(PDO::FETCH_NUM))) {
            $channel = $this->channelRepository->find($row[0]);

            $prgAtt = new PrgAttivitaDidattica( $row[0], $channel->getLegacyGroups(),
                    $channel->getType(), '', $channel->getName(), $channel->getHits(),
                    $channel->hasService('news'), $channel->hasService('files'),
                    $channel->hasService('forum'), $channel->getForumId(), 0,
                    $channel->hasService('links'), $channel->hasService('student_files'),
                    $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8],
                    $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16],
                    $row[17], $row[18], $row[19]=='S' , $row[20]);

            $elenco[] = $prgAtt;
        }

        return $elenco;
    }

    /**
     * @todo incomplete
     * @param  type                                                              $id_sdop
     * @return array|\Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica
     */
    public function findByIdSdoppiamento($id_sdop)
    {
        $db = $this->getDb();

        $id_sdop = $db->quote($id_sdop);
        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo) *
        FROM (

        SELECT i.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, id_sdop
        FROM prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE 1=1
        AND i.anno_accademico=s.anno_accademico_fis
        AND i.cod_corso=s.cod_corso_fis
        AND i.cod_ind=s.cod_ind_fis
        AND i.cod_ori=s.cod_ori_fis
        AND i.cod_materia=s.cod_materia_fis
        AND s.cod_materia=m1.cod_materia
        AND s.cod_materia_ins=m2.cod_materia
        AND i.anno_corso=s.anno_corso_fis
        AND i.cod_materia_ins=s.cod_materia_ins_fis
        AND i.anno_corso_ins=s.anno_corso_ins_fis
        AND i.cod_ril=s.cod_ril_fis
        AND i.cod_doc=d.cod_doc
        AND s.id_sdop='.$db->quote($id_sdop).'
        ) AS cdl
        ) AS cdl1
        ORDER BY 19, 17, 10';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */
        //		var_dump($query); die;
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0) {
            $ret = array(); return $ret;
        }

        $res->fetchInto($row);
        $prgAtt = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                $row[30], $row[31], true, $row[32] );

        $res->free();

        return $prgAtt;
    }

    /**
     * Finds previous year's channel
     *
     * @param  PrgAttivitaDidattica $att
     * @param  integer              $count
     * @return null|integer         channel id
     */
    public function getPreviousYearWithForum(PrgAttivitaDidattica $att, &$count)
    {
        $db = $this->getConnection();

        $query = 'SELECT c.id_canale FROM canale c, prg_insegnamento pi WHERE
        c.id_canale=pi.id_canale
        AND c.forum_attivo IS NOT NULL
        AND c.id_forum IS NOT NULL
        AND c.group_id IS NOT NULL
        AND pi.cod_materia_ins='.$db->quote($att->getCodMateriaIns()).'
        AND pi.cod_ril='.$db->quote($att->getCodRil()).'
        AND pi.cod_materia = '.$db->quote($att->getCodMateria()).'
        AND pi.cod_doc = '.$db->quote($att->getCodDoc()).'
        AND anno_accademico = '.$db->quote($att->getAnnoAccademico() - 1).'
        AND pi.cod_corso = '.$db->quote($att->getCodiceCdl());

        $res = $db->executeQuery($query);

        $count = $res->rowCount();

        if ($res->rowCount() === 0)
            return null;

        return intval($res->fetchColumn());
    }
}
