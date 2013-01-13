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
use Universibo\Bundle\ForumBundle\DAO\GroupDAOInterface;
use Universibo\Bundle\ForumBundle\Entity\Forum;
use Universibo\Bundle\ForumBundle\Entity\ForumRepository;
use Universibo\Bundle\ForumBundle\Naming\NameGenerator;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Cdl;
use Universibo\Bundle\LegacyBundle\Entity\DBCanaleRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBCdlRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBDocenteRepository;
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
        $forumRepository  = $container->get('universibo_forum.repository.forum');
        $activityRepo     = $container->get('universibo_legacy.repository.programma');
        $subjectRepo      = $container->get('universibo_legacy.repository.insegnamento');
        $channelRepo      = $container->get('universibo_legacy.repository.canale');

        $output->writeln('Max forum ID = '.$forumRepository->getMaxId());

        foreach ($degreeCourseRepo->findAll() as $degreeCourse) {
            $forumId = $this->findOrCreateDegreeCourseForum($degreeCourse, $degreeCourseRepo, $forumRepository);
            $this->createSubjectForums($output, $degreeCourse, $forumId, $forumRepository, $activityRepo, $subjectRepo, $channelRepo);
            $forumRepository->sortAlphabetically($forumId);
        }
    }

    /**
     * Gets a similar subject
     *
     * @param  Insegnamento    $ins
     * @param  OutputInterface $output
     * @return integer|null
     */
    private function selectSimilarSubject(Insegnamento $ins, OutputInterface $output)
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

    private function findOrCreateDegreeCourseForum(Cdl $degreeCourse, DBCdlRepository $courseRepo, ForumRepository $forumRepository)
    {
        $forumId = $degreeCourse->getForumForumId();

        if ($forumId > 0) {
            return $forumId;
        }

        $code = $degreeCourse->getCodiceCdl();

        $forum = new Forum();

        $forum->setParentId(0);
        $forum->setName($code . ' - ' . $degreeCourse->getNome());
        $forum->setDescription('Forum riservato alla discussione generale sul CdL '.$code);
        $forum->setType(Forum::TYPE_FORUM);

        $forumRepository->save($forum);
        $newForumId = $forum->getId();
        $degreeCourse->setForumForumId($newForumId);

        return $newForumId;
    }

    private function createSubjectForums( OutputInterface $output,
            Cdl $degreeCourse, $courseForumId, ForumRepository $forumRepository,
            DBPrgAttivitaDidatticaRepository $activityRepo,
            DBInsegnamentoRepository $subjectRepo, DBCanaleRepository $channelRepo)
    {
        $nameGenerator = $this->getContainer()->get('universibo_forum.naming.generator');
        $profRepo = $this->getContainer()->get('universibo_legacy.repository.docente');

        foreach ($activityRepo->findByCdlAndYear($degreeCourse->getCodiceCdl(), $this->academicYear) as $activity) {
            if (!$activity->isSdoppiato() && $activity->isGroupAllowed(LegacyRoles::OSPITE)
                    && !$activity->getServizioForum()) {
                $channelId = $activity->getIdCanale();
                $subject = $subjectRepo->findByChannelId($channelId) ?: null;

                if ($subject === null) {
                    $output->writeln('No subject for channel id = '.$channelId);
                    continue;
                }

                $similarId = $this->selectSimilarSubject($subject, $output);

                if ($similarId === null) {
                    $this->createNewSubjectForum($subject, $courseForumId, $nameGenerator, $forumRepository, $profRepo);
                } else {
                    $similar = $subjectRepo->findByChannelId($similarId);
                    $this->setSimilarForum($similar, $subject, $forumRepository, $nameGenerator);
                }

                $channelRepo->update($subject);
            }
        }
    }

    private function createNewSubjectForum(Insegnamento $subject, $parentId,
            NameGenerator $generator, ForumRepository $forumRepository, DBDocenteRepository $profRepo)
    {
        list($activity) = $subject->getElencoAttivita();
        $professor = $profRepo->find($activity->getCodDoc());

        $forum = new Forum();

        $forum->setName($generator->generate($subject->getNome(), $professor->getNomeDoc(), $this->academicYear));
        $forum->setParentId($parentId);
        $forum->setType(Forum::TYPE_FORUM);
        $forum->setDescription('');

        $forumRepository->save($forum);

        $subject->setForumForumId($forum->getId());
    }

    /**
     * Sets the same forum for similar subjects
     *
     * @param Insegnamento    $source
     * @param Insegnamento    $target
     * @param ForumRepository $forumRepository
     * @param NameGenerator   $nameGenerator
     */
    private function setSimilarForum(Insegnamento $source, Insegnamento $target,
            ForumRepository $forumRepository, NameGenerator $nameGenerator)
    {
        $this->copyForumSettings($source, $target);
        $forumId = $source->getForumForumId();

        $forum = $forumRepository->find($forumId);

        $name = $forum->getName();
        $newName = $nameGenerator->update($name, $this->academicYear);

        $forum->setName($newName);
        $forumRepository->save($forum);

        $target->setForumForumId($forum->getId());
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
