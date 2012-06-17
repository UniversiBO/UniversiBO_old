<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class CdlRepository extends DoctrineRepository
{
    /**
     * @return boolean|Cdl[]
     */
    public function findAll()
    {
        $db = $this->getConnection();

        $query = 'SELECT cod_corso FROM classi_corso WHERE 1 = 1';

        $stmt = $db->executeQuery($query);

        $elencoCdl = array();

        while (false !== ($row = $stmt->fetch())) {
            //echo $row[0];
            if ( ($elencoCdl[] = $this->findByCodice($row[0]) ) === false )

                return false;
        }

        return $elencoCdl;
    }

    public function findByIdCanale($idCanale)
    {
        $db = $this->getConnection();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
        a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale AND a.id_canale = '.$db->quote($idCanale);

        $stmt = $db->executeQuery($query);

        if (false === ($row = $stmt->fetch())) {
            return false;
        }

        return new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S', $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
    }

    public function findByCodice($codice)
    {
        $db = $this->getConnection();

        // LA PRIMA QUERY E' QUELLA CHE VA BENE, MA BISOGNA ALTRIMENTI SISTEMARE IL DB
        //E VERIFICARE CHE METTENDO DIRITTI = 0 IL CANALE NON VENGA VISUALIZZATO
        //$query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,
        //			 a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale AND b.cod_corso = '.$db->quote($codice);

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
        a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM  classi_corso b LEFT OUTER JOIN canale a ON a.id_canale = b.id_canale WHERE b.cod_corso = '.$db->quote($codice);
        $stmt = $db->executeQuery($query);

        if (false === ($row = $stmt->fetch())) {
            return false;
        }

        return new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S', $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
    }

    public function findByFacolta($codiceFacolta)
    {
        $db = $this->getConnection();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo,files_studenti_attivo,
        a.id_canale, cod_corso, desc_corso, categoria, cod_fac, cod_doc, cat_id FROM canale a , classi_corso b WHERE a.id_canale = b.id_canale
        AND b.cod_fac = '.$db->quote($codiceFacolta).' ORDER BY 17 , 15 ';

        $stmt = $db->executeQuery($query);

        $elenco = array();
        while (	false !== ($row = $stmt->fetch()) ) {
            $cdl = new Cdl($row[13], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[12]=='S',
                    $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);

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
        $db = $this->getConnection();

        $query = 'UPDATE classi_corso SET cat_id = '.$db->quote($cdl->getForumCatId()).
        ', cod_corso = '.$db->quote($cdl->getCodiceCdl()).
        ', desc_corso = '.$db->quote($cdl->getNome()).
        ', cod_fac = '.$db->quote($cdl->getCodiceFacoltaPadre()).
        ', categoria = '.$db->quote($cdl->getCategoriaCdl()).
        ', cod_doc =' .$db->quote($cdl->getCodDocente()).
        ' WHERE id_canale = '.$db->quote($cdl->getIdCanale());

        $db->executeUpdate($query);
    }

    public function insert(Cdl $cdl)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO classi_corso (cod_corso, desc_corso, categoria, cod_doc, cod_fac, id_canale) VALUES ('.
                $db->quote($cdl->getCodiceCdl()).' , '.
                $db->quote($cdl->getNome()).' , '.
                $db->quote($cdl->getCategoriaCdl()).' , '.
                $db->quote($cdl->getCodDocente()).' , '.
                $db->quote($cdl->getCodiceFacoltaPadre()).' , '.
                $db->quote($cdl->getIdCanale()).' )';

        $db->executeUpdate($query);

        return true;
    }
}
