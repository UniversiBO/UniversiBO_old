<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \DB;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\Insegnamento;
use Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;
use Universibo\Bundle\LegacyBundle\Entity\User;

/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 * NON ASTRAE DAL LIVELLO DATABASE!!!
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptCreaForum extends UniversiboCommand
{
    public $anno_accademico = 2011;

    public function execute()
    {
        $anno_accademico = $this->anno_accademico;

        $fc = $this->getFrontController();
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');

        $db->beginTransaction();

        $forum = $this->getContainer()->get('universibo_legacy.forum.api');
        $max_forum_id = $forum->getMaxForumId();

        echo 'max_forum_id: ', $max_forum_id, "\n";

        $cdlAll = Cdl::selectCdlAll();
        //var_dump($cdlAll);

        foreach ($cdlAll as $cdl) {
            echo $cdl->getCodiceCdl(),' - ', $cdl->getTitolo(),"\n";

            // creo categoria
            if ($cdl->getForumCatId()=='') {
                $cat_id = $forum->addForumCategory($cdl->getCodiceCdl().' - '.ucwords( strtolower( $cdl->getNome())), $cdl->getCodiceCdl());
                echo ' > ','creata categoria: ',$cat_id,"\n";

                $cdl->setForumCatId($cat_id);
                $cdl->updateCdl();
                echo ' > ','aggiornato cdl con nuova categoria: ',$cat_id,"\n";
            } else
                $cat_id = $cdl->getForumCatId();

            // creo forum cdl se ? attivo su universibo
            $id_utente = $this->selectIdUtenteFromCodDoc($cdl->getCodDocente()); //presidente cdl pu? essere null

            if ($cdl->isGroupAllowed(User::OSPITE) && $cdl->getServizioForum()==false) {
                $forum_id = $forum->addForum($cdl->getCodiceCdl().' - '.ucwords(strtolower($cdl->getNome())),
                            'Forum riservato alla discussione generale sul CdL '.$cdl->getCodiceCdl(), $cat_id);
                $cdl->setForumForumId($forum_id);
                $cdl->setServizioForum(true);

                echo ' > ','creato forum cdl : ',$forum_id,'-'.$cat_id,"\n";

                if ($id_utente != null) {
                    $group_id = $forum->addGroup('Moderatori '.$cdl->getCodiceCdl(), 'Moderatori del cdl'.$cdl->getCodiceCdl().' - '.ucwords(strtolower($cdl->getNome())), $id_utente );
                    echo ' > ','creato gruppo forum cdl : ',$group_id,"\n";

                    $forum->addGroupForumPrivilegies($forum_id, $group_id);
                    echo ' > ','aggiunti privilegi cdl : ',$group_id,' - '.$forum_id,"\n";

                    $cdl->setForumGroupId($group_id);
                } else
                    echo ' > ','presidente cdl non trovato: ',$forum_id,"\n";

                $cdl->updateCdl();
                echo ' > ','aggiornato il canale con il nuovo forum e categoria: ',$forum_id,"\n";

            } elseif ($cdl->isGroupAllowed(User::OSPITE))
                echo ' > ','forum cdl gia\' presente: ',$cdl->getForumForumId(),' - '.$cdl->getForumGroupId()."\n";

            $elenco_prgAttivitaDidattica = PrgAttivitaDidattica::selectPrgAttivitaDidatticaElencoCdl($cdl->getCodiceCdl(), $anno_accademico);

            //creo i forum degli insegnmanti
            foreach ($elenco_prgAttivitaDidattica as $prg_att) {
                //AAHHH qui la cache di Canale potrebbe restituire dei casini, non la posso usare,
                // PrgAttivit? ed Insegnamento hanno gli stessi id_canali
                if (!$prg_att->isSdoppiato() && $prg_att->isGroupAllowed(User::OSPITE)  && $prg_att->getServizioForum()==false) {
                    $insegnamento = Insegnamento::selectInsegnamentoCanale($prg_att->getIdCanale());
                    echo '   - ',$insegnamento->getIdCanale(),' - '.str_replace("\n",' ',$insegnamento->getNome()),"\n";
                    $simile = $this->selectInsegnamentoConForumSimile($insegnamento);

                    if ($simile == null) {
                        //creo nuovo forum
                        $id_docente = $this->selectIdUtenteFromCodDoc($prg_att->getCodDoc());

                        if ($insegnamento->isGroupAllowed(User::OSPITE) && $insegnamento->getServizioForum()==false) {
                            $ins_forum_id = $forum->addForum($insegnamento->getNome(),'', $cat_id);
                            $insegnamento->setForumForumId($ins_forum_id);
                            $insegnamento->setServizioForum(true);

                            echo '   - ','creato forum insegnamento : ',$ins_forum_id,'-'.$cat_id,"\n";

                            if ($id_docente != null) {
                                $ins_group_id = $forum->addGroup('Moderatori '.$insegnamento->getIdCanale(), 'Moderatori dell\'insegnamento con id_canale'.$insegnamento->getIdCanale().' - '.$insegnamento->getNome(), $id_docente );
                                echo '   - ','creato gruppo forum insegnamento : ',$ins_group_id,"\n";

                                $forum->addGroupForumPrivilegies($ins_forum_id, $ins_group_id);
                                echo '   - ','aggiunti privilegi insegnamento : ',$ins_group_id,' - '.$ins_forum_id,"\n";

                                $insegnamento->setForumGroupId($ins_group_id);
                            } else
                                echo '   ### docente insegnamento non trovato: ',$ins_forum_id,"\n";

                            $insegnamento->updateCanale();
                            echo '   - aggiornato il canale con il nuovo forum e categoria: ',$ins_forum_id,"\n";

                        }

                    } else {
                        $ins_simile = Insegnamento::selectInsegnamentoCanale($simile);
                        echo '   - forum simile a: '.$ins_simile->getIdCanale(),' - '.str_replace("\n",' ',$ins_simile->getNome()),"\n";

                        $forum->addForumInsegnamentoNewYear($ins_simile->getForumForumId(), $anno_accademico);
                        echo '   - aggiornato il nome del forum con il nuovo anno accademico ',$ins_simile->getForumForumId(),"\n";

                        $insegnamento->setForumGroupId($ins_simile->getForumGroupId());
                        $insegnamento->setForumForumId($ins_simile->getForumForumId());
                        $insegnamento->setServizioForum(true);

                        $insegnamento->updateCanale();
                        echo '   - aggiornato il canale con il nuovo forum e categoria: ',$ins_simile->getForumForumId(),"\n";

                    }

                }
                if($prg_att->getServizioForum()==true)
                    echo '   -- forum gia\' attivo ',$prg_att->getIdCanale(),"\n";

            }

        }

        //manca chiamare una funzione per ordinare tutti i forum

        $db->commit();

    }

    /**
     * @todo questa funzione qui fa schifo, bisogna creare una classe Docente che estente Utente (?)
     *
     * @return int id_utente, null se non esiste il cod_doc
     */
    public function selectIdUtenteFromCodDoc($cod_doc)
    {
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $query = 'SELECT id_utente FROM docente WHERE cod_doc = '.$db->quote($cod_doc);

        $res = $db->executeQuery($query);

        if ($res->rowCount() == 0 )
            return null;

        $row = $res->fetch();

        return $row[0];
    }

    /**
     * @todo questa funzione qui fa schifo,
     *
     * @return Insegnamento
     */
    public function selectInsegnamentoConForumSimile($ins)
    {
        $elencoAtt = $ins->getElencoAttivitaPadre();

        //se sono pi? attivit? unite... non ci impazzisco.
        if (count($elencoAtt) > 1) return null;

        $att = $elencoAtt[0];

        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $query = 'SELECT c.id_canale FROM canale c, prg_insegnamento pi WHERE
                c.id_canale=pi.id_canale
                AND c.forum_attivo IS NOT NULL
                AND c.id_forum IS NOT NULL
                AND c.group_id IS NOT NULL
                AND pi.cod_materia_ins='.$db->quote($att->getCodMateriaIns()).'
                AND pi.cod_ril='.$db->quote($att->getCodRil()).'
                AND pi.cod_materia = '.$db->quote($att->getCodMateria()).'
                AND pi.cod_doc = '.$db->quote($att->getCodDoc()).'
                AND anno_accademico = '.$db->quote($this->anno_accademico - 1).'
                AND pi.cod_corso = '.$db->quote($att->getCodiceCdl());

        $res = $db->executeQuery($query);

        if ($res->rowCount() == 0 )
            return null;

        //se ce ne sono di pi? prendo il primo
        if ($res->rowCount() > 1 )
            echo '   #### c\'erano '.$res->rowCount().' forum simili, ho preso solo il primo',"\n";

        $row = $res->fetch();

        return $row[0];

    }
}
