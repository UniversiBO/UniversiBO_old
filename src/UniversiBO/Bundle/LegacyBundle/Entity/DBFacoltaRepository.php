<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * Facolta repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBFacoltaRepository extends DBRepository
{
    /**
     * @return Facolta[]
     */
    public function findAll()
    {
        $db = $this->getDb();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, files_studenti_attivo, a.id_canale, cod_fac, desc_fac, url_facolta FROM canale a , facolta b WHERE a.id_canale = b.id_canale ORDER BY 16';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_DEFAULT',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        if ($res->numRows() === 0) {
            return array();
        }

        $facolta = array();

        while ($res->fetchInto($row)) {
            $facolta[] = new Facolta($row[13], $row[5], $row[4], $row[0],
                    $row[2], $row[1], $row[3], $row[7] == 'S', $row[6] == 'S',
                    $row[8] == 'S', $row[9], $row[10], $row[11] == 'S',
                    $row[12] == 'S', $row[14], $row[15], $row[16]);
        }

        $res->free();

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

        $canaleRepo = new DBCanaleRepository($db);
        $canaleRepo->update($facolta);
    }

}
