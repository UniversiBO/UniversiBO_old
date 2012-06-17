<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class PrgAttivitaDidatticaRepository extends DoctrineRepository
{
    public function findByCdlAndYear($cod_cdl, $anno_accademico)
    {
        $db = $this->getConnection();

        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo ) *
        FROM (
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, ? AS sdoppiato, null AS id_sdop
        FROM canale c, prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND i.anno_accademico=?
        AND cod_corso=?
        UNION
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, ? AS sdoppiato, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
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
        AND s.cod_corso=?
        AND s.anno_accademico=?
        ) AS cdl
        ) AS cdl1
        ORDER BY 32, 30, 23';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */

        $params = array('N', $anno_accademico, $cod_cdl, 'S', $cod_cdl, $anno_accademico);
        $stmt = $db->executeQuery($query, $params);

        $elenco = array();

        while (false !== ($row = $stmt->fetch())) {
            $elenco[] = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                    $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                    $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                    $row[30], $row[31], $row[32]=='S' , $row[33]);

        }

        return $elenco;
    }

    public function update(PrgAttivitaDidattica $attivita)
    {
        $db = $this->getConnection();

        if ($attivita->isSdoppiato()) {
            $query = 'UPDATE prg_sdoppiamento '
                    . 'SET anno_corso_universibo = ?'
                    . ', tipo_ciclo = ?'
                    . ' WHERE  	id_sdop=?';

            $rows = $db->executeUpdate($query, array(
                    $attivita->getAnnoCorsoUniversibo(),
                    $attivita->getTipoCiclo(),
                    $attivita->getIdSdop()
            ));
        } else {
            $query = 'UPDATE prg_insegnamento '
                    . 'SET anno_corso_universibo = ?'
                    . ' , tipo_ciclo = ?'.
                    ' , cod_doc = ?'
                    .' WHERE  id_canale= ?';

            $rows = $db->executeUpdate($query, array(
                    $attivita->getAnnoCorsoUniversibo(),
                    $attivita->getTipoCiclo(),
                    $attivita->getCodDoc(),
                    $attivita->getIdCanale()
            ));
        }

        return $rows > 0;
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
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '
                . $db->quote('N')
                . ' AS sdoppiato, null AS id_sdop
        FROM canale c, prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
        AND i.cod_materia=m1.cod_materia
        AND i.cod_materia_ins=m2.cod_materia
        AND i.cod_doc=d.cod_doc
        AND c.id_canale=' . $id_canale
                . '
        UNION
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, '
                . $db->quote('S')
                . ' AS sdoppiato, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
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
        AND c.id_canale=' . $id_canale
                . '
        ) AS cdl
        ) AS cdl1
        ORDER BY 32, 30, 23';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */

        $stmt = $db->executeQuery($query);

        $elenco = array();
        while (false !== ($row = $stmt->fetch())) {
            $prgAtt = new PrgAttivitaDidattica($row[13], $row[5], $row[4],
                    $row[0], $row[2], $row[1], $row[3], $row[7] == 'S',
                    $row[6] == 'S', $row[8] == 'S', $row[9], $row[10],
                    $row[11] == 'S', $row[12] == 'S', $row[14], $row[15],
                    $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                    $row[22], $row[23], $row[24], $row[25], $row[26], $row[27],
                    $row[28], $row[29], $row[30], $row[31], $row[32] == 'S',
                    $row[33]);

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

        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo, sdoppiato ) *
        FROM (
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, i.anno_accademico, i.cod_corso, i.cod_ind, i.cod_ori, i.cod_materia, m1.desc_materia, i.anno_corso, i.cod_materia_ins, m2.desc_materia AS desc_materia_ins, i.anno_corso_ins, i.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, i.tipo_ciclo, i.cod_ate, i.anno_corso_universibo, '.$db->quote('N').' AS sdoppiato, null AS id_sdop
        FROM canale c, prg_insegnamento i, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
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
        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo, c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, '.$db->quote('S').' AS sdoppiato, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
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
        ORDER BY 33, 32, 30, 23';

        $stmt = $db->executeQuery($query);

        $elenco = array();
        while (false !== ($row = $stmt->fetch())) {
            $prgAtt = new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                    $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                    $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                    $row[30], $row[31], $row[32]=='S' , $row[33]);

            $elenco[] = $prgAtt;
        }

        return $elenco;
    }

    public function findByIdSdoppiamento($id_sdop)
    {
        $db = $this->getConnection();

        $id_sdop = $db->quote($id_sdop);
        //attenzione!!! ...c'? il distinct anche su sdoppiato!!
        $query = 'SELECT *
        FROM (
        SELECT DISTINCT ON (id_canale, anno_accademico, cod_corso, cod_materia, anno_corso, cod_materia_ins, anno_corso_ins, cod_ril, cod_doc, tipo_ciclo, cod_ate, anno_corso_universibo) *
        FROM (

        SELECT c.tipo_canale, c.nome_canale, c.immagine, c.visite, c.ultima_modifica, c.permessi_groups, c.files_attivo, c.news_attivo, c.forum_attivo, c.id_forum, c.group_id, c.links_attivo,c.files_studenti_attivo, c.id_canale, s.anno_accademico, s.cod_corso, s.cod_ind, s.cod_ori, s.cod_materia, m1.desc_materia, i.anno_corso, s.cod_materia_ins, m2.desc_materia AS desc_materia_ins, s.anno_corso_ins, s.cod_ril, i.cod_modulo, i.cod_doc, d.nome_doc, i.flag_titolare_modulo, s.tipo_ciclo, s.cod_ate, s.anno_corso_universibo, id_sdop
        FROM canale c, prg_insegnamento i, prg_sdoppiamento s, classi_materie m1, classi_materie m2, docente d
        WHERE c.id_canale = i.id_canale
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
        ORDER BY 32, 30, 23';
        /**
         * @todo ATTENZIONE! ...questa query non ? portabile.
         * bisogna cambiarla ed eventualmente gestire i duplicati via PHP
         */

        $stmt = $db->executeQuery($query);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return new PrgAttivitaDidattica( $row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29],
                $row[30], $row[31], true, $row[32] );
    }
}
