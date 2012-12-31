<?php
/**
 * @copyright (c) 2002-2012, Associazione UniversiBO
 */
namespace Universibo\Bundle\WebsiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\ForumBundle\DAO\ForumDAOInterface;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\Insegnamento;
use Universibo\Bundle\LegacyBundle\Entity\PrgAttivitaDidattica;

/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 * NON ASTRAE DAL LIVELLO DATABASE!!!
 *
 * @version 2.6.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class BatchCreateForumsCommand extends ContainerAwareCommand
{
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('universibo:forum:create')
            ->setDescription('Batch create forums')
            ->addArgument('academic_year', InputArgument::REQUIRED, 'Academic Year (e.g. 2012)');
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input,
            OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->verbose = $input->getOption('verbose');
    }

    /**
     * Creates forum for every Degree Course
     * president of degree course's council can moderate forum
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $anno_accademico = $input->getArgument('academic_year');

        $container = $this->getContainer();
        $db = $container->get('doctrine.dbal.default_connection');

        $db->beginTransaction();

        $forumDAO = $container->get('universibo_forum.dao.forum');
        $output->writeln('Max forum ID = '.$forumDAO->getMaxId());

        $degreeCourseRepo = $container->get('universibo_legacy.repository.cdl');

        foreach ($degreeCourseRepo->findAll() as $degreeCourse) {
            $output->writeln($degreeCourse->getCodiceCdl(),' - ', $degreeCourse->getTitolo());

            $this->findOrCreateDegreeCourseForum($degreeCourse);

            // creo categoria
            if ($degreeCourse->getForumCatId()=='') {
                $cat_id = $forum->addForumCategory($degreeCourse->getCodiceCdl().' - '.ucwords( strtolower( $degreeCourse->getNome())), $degreeCourse->getCodiceCdl());
                $output->writeln(' > ','creata categoria: '.$cat_id);

                $degreeCourse->setForumCatId($cat_id);
                $degreeCourse->updateCdl();
                echo ' > ','aggiornato cdl con nuova categoria: ',$cat_id,"\n";
            } else
                $cat_id = $degreeCourse->getForumCatId();

            // creo forum cdl se ? attivo su universibo
            $id_utente = $this->selectIdUtenteFromCodDoc($degreeCourse->getCodDocente()); //presidente cdl pu? essere null

            if ($degreeCourse->isGroupAllowed(User::OSPITE) && $degreeCourse->getServizioForum()==false) {
                $forum_id = $forum->addForum($degreeCourse->getCodiceCdl().' - '.ucwords(strtolower($degreeCourse->getNome())),
                            'Forum riservato alla discussione generale sul CdL '.$degreeCourse->getCodiceCdl(), $cat_id);
                $degreeCourse->setForumForumId($forum_id);
                $degreeCourse->setServizioForum(true);

                echo ' > ','creato forum cdl : ',$forum_id,'-'.$cat_id,"\n";

                if ($id_utente != null) {
                    $group_id = $forum->addGroup('Moderatori '.$degreeCourse->getCodiceCdl(), 'Moderatori del cdl'.$degreeCourse->getCodiceCdl().' - '.ucwords(strtolower($degreeCourse->getNome())), $id_utente );
                    echo ' > ','creato gruppo forum cdl : ',$group_id,"\n";

                    $forum->addGroupForumPrivilegies($forum_id, $group_id);
                    echo ' > ','aggiunti privilegi cdl : ',$group_id,' - '.$forum_id,"\n";

                    $degreeCourse->setForumGroupId($group_id);
                } else
                    echo ' > ','presidente cdl non trovato: ',$forum_id,"\n";

                $degreeCourse->updateCdl();
                echo ' > ','aggiornato il canale con il nuovo forum e categoria: ',$forum_id,"\n";

            } elseif ($degreeCourse->isGroupAllowed(User::OSPITE))
                echo ' > ','forum cdl gia\' presente: ',$degreeCourse->getForumForumId(),' - '.$degreeCourse->getForumGroupId()."\n";

            $elenco_prgAttivitaDidattica = PrgAttivitaDidattica::selectPrgAttivitaDidatticaElencoCdl($degreeCourse->getCodiceCdl(), $anno_accademico);

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
                if ($prg_att->getServizioForum()==true) {
                    $output->writeln('   -- forum gia\' attivo '.$prg_att->getIdCanale());
                }
            }
        }

        //manca chiamare una funzione per ordinare tutti i forum

        $db->commit();

    }

    /**
     * Gets a similar subject
     *
     * @param  Insegnamento    $ins
     * @param  OutputInterface $output
     * @return integer|null
     */
    public function selectInsegnamentoConForumSimile(Insegnamento $ins, OutputInterface $output)
    {
        $elencoAtt = $ins->getElencoAttivitaPadre();

        //se sono pi? attivit? unite... non ci impazzisco.
        if (!is_array($elencoAtt) || count($elencoAtt) !== 1) {
            return null;
        }

        list($att) = $elencoAtt;

        $prgRepo = $this->getContainer()->get('universibo_legacy.repository.programma');

        $count = 0;
        $channelId = $prgRepo->getPreviousYearWithForum($att, $count);

        //se ce ne sono di pi? prendo il primo
        if ($count > 1) {
            $output->writeln('   #### c\'erano '.$count.' forum simili, ho preso solo il primo');
        }

        return $channelId;
    }

    private function findOrCreateDegreeCourseForum(Cdl $degreeCourse, ForumDAOInterface $forumDAO)
    {
        $forumId = $degreeCourse->getForumForumId();

        if ($forumId > 0) {
            return $forumId;
        }
    }
}
