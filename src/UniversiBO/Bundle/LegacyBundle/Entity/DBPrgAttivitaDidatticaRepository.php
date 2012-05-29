<?php

namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBPrgAttivitaDidatticaRepository extends DBRepository
{
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
        $db = $this->getDb();

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
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0) {
            $ret = array();

            return $ret;
        }
        $elenco = array();
        while ($row = $this->fetchRow($res)) {
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
        $res->free();

        return $elenco;
    }
}
