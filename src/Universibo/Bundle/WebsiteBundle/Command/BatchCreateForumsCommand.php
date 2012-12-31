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
use Universibo\Bundle\ForumBundle\DAO\GroupDAOInterface;
use Universibo\Bundle\ForumBundle\Naming\NameGenerator;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\DBInsegnamentoRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBPrgAttivitaDidatticaRepository;
use Universibo\Bundle\LegacyBundle\Entity\Insegnamento;

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
     * Gets a similar subject
     *
     * @param  Insegnamento    $ins
     * @param  OutputInterface $output
     * @return integer|null
     */
    public function selectSimilarSubject(Insegnamento $ins, OutputInterface $output)
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

                $similarId = $this->selectSimilarSubject($subject);

                if ($similarId === null) {
                    $this->createNewSubjectForum($subject);
                } else {
                    $similar = $subjectRepo->findByChannelId($similar);
                    $this->setSimilarForum($similar, $subject, $forumDAO, $nameGenerator);
                }

                $subjectRepo->update($subject);
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

    private function createModeratorGroup(GroupDAOInterface $groupDAO,
            $forumId, User $moderator, $moderatorGroupName)
    {

    }
}
