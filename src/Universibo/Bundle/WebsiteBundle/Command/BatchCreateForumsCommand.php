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
use Universibo\Bundle\ForumBundle\Naming\NameGenerator;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\DBInsegnamentoRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBPrgAttivitaDidatticaRepository;
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
    /**
     * Verbose option
     *
     * @var boolean
     */
    private $verbose;

    /**
     * Academic year
     *
     * @var integer
     */
    private $academicYear;

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
        $this->academicYear = $input->getArgument('academic_year');
    }

    /**
     * Creates forum for every Degree Course
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $degreeCourseRepo = $container->get('universibo_legacy.repository.cdl');
        $forumDAO         = $container->get('universibo_forum.dao.forum');
        $activityRepo     = $container->get('universibo_legacy.repository.attivita');
        $subjectRepo      = $container->get('universibo_legacy.repository.insegnamento');

        $output->writeln('Max forum ID = '.$forumDAO->getMaxId());

        foreach ($degreeCourseRepo->findAll() as $degreeCourse) {
            $forumId = $this->findOrCreateDegreeCourseForum($degreeCourse, $forumDAO);
            $this->createSubjectForums($degreeCourse, $forumId, $forumDAO, $activityRepo, $subjectRepo);
            $forumDAO->sortForumsAlphabetically($forumId);
        }
    }

    /**
     * Old execute function
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function oldStuff(InputInterface $input, OutputInterface $output)
    {
        $academicYear = $input->getArgument('academic_year');

        $container = $this->getContainer();
        $db = $container->get('doctrine.dbal.default_connection');

        $db->beginTransaction();

        $forumDAO = $container->get('universibo_forum.dao.forum');
        $output->writeln('Max forum ID = '.$forumDAO->getMaxId());

        $degreeCourseRepo = $container->get('universibo_legacy.repository.cdl');

        foreach ($degreeCourseRepo->findAll() as $degreeCourse) {
            $output->writeln($degreeCourse->getCodiceCdl(),' - ', $degreeCourse->getTitolo());

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

            $elenco_prgAttivitaDidattica = PrgAttivitaDidattica::selectPrgAttivitaDidatticaElencoCdl($degreeCourse->getCodiceCdl(), $academicYear);

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

                        $forum->addForumInsegnamentoNewYear($ins_simile->getForumForumId(), $academicYear);
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

    private function createSubjectForums( OutputInterface $output,
            Cdl $degreeCourse, $courseForumId, ForumDAOInterface $forumDAO,
            DBPrgAttivitaDidatticaRepository $activityRepo,
            DBInsegnamentoRepository $subjectRepo)
    {
        $nameGenerator = $this->get('universibo_forum.naming.generator');

        foreach ($activityRepo->findByCdlAndYear($degreeCourse, $this->academicYear) as $activity) {
            if (!$activity->isSdoppiato() && $activity->isGroupAllowed(User::OSPITE)
                    && !$activity->getServizioForum()) {
                $channelId = $activity->getIdCanale();
                $subject = $subjectRepo->findByChannelId($channelId) ?: null;

                if ($subject === null) {
                    $output->writeln('No subject for channel id = '.$channelId);
                    continue;
                }

                $similarId = $this->selectInsegnamentoConForumSimile($subject);

                if ($similarId === null) {
                    $this->createNewSubjectForum($subject);
                } else {
                    $similar = $subjectRepo->findByChannelId($similar);
                    $this->setSimilarForum($similar, $subject, $forumDAO, $nameGenerator);
                }

                $subjectRepo->updateForumSettings($subject);
            }
        }
    }

    private function createNewSubjectForum(Insegnamento $subject, $parentId,
            NameGenerator $generator)
    {

    }

    /**
     * Sets the same forum for similar subjects
     *
     * @param Insegnamento      $source
     * @param Insegnamento      $target
     * @param ForumDAOInterface $forumDAO
     * @param NameGenerator     $nameGenerator
     */
    private function setSimilarForum(Insegnamento $source, Insegnamento $target,
            ForumDAOInterface $forumDAO, NameGenerator $nameGenerator)
    {
        $this->copyForumSettings($source, $target);
        $forumId = $source->getForumForumId();
        $name = $forumDAO->getForumName($forumId);
        $newName = $nameGenerator->update($name, $this->academicYear);
        $forumDAO->rename($forumId, $newName);
    }

    /**
     * Copies forum setting from $source to $target
     *
     * @param Insegnamento $source
     * @param Insegnamento $target
     */
    private function copyForumSettings(Insegnamento $source, Insegnamento $target)
    {
        $target->setForumForumId($source->getForumForumId());
        $target->setForumGroupId($source->getForumGroupId());
        $target->setServizioForum($source->getServizioForum());
    }
}
