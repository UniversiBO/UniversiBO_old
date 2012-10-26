<?php

namespace Universibo\Bundle\WebsiteBundle\Entity\Merge;

use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\ForumBundle\DAO\PostDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\UserDAOInterface;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\DBCommentoItemRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBDocenteRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBRuoloRepository;
use Universibo\Bundle\LegacyBundle\Entity\Files\DBFileItemRepository;
use Universibo\Bundle\LegacyBundle\Entity\Links\DBLinkRepository;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

class UserMerger implements UserMergerInterface
{
    /**
     * Retrievers
     * @var array
     */
    private $retrievers = array();

    /*
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository           $userRepository
     * @param DBFileItemRepository     $fileItemRepository
     * @param DBCommentoItemRepository $commentoRepository
     * @param DBNewsItemRepository     $newsRepository
     * @param DBLinkRepository         $linkRepository
     * @param DBRuoloRepository        $ruoloRepository
     * @param DBDocenteRepository      $docenteRepository
     * @param PostDAOInterface         $postDAO
     * @param UserDAOInterface         $userDAO
     */
    public function __construct(UserRepository $userRepository,
            DBFileItemRepository $fileItemRepository,
            DBCommentoItemRepository $commentoRepository,
            DBNewsItemRepository $newsRepository,
            DBLinkRepository $linkRepository,
            DBRuoloRepository $ruoloRepository,
            DBDocenteRepository $docenteRepository,
            PostDAOInterface $postDAO,
            UserDAOInterface $userDAO)
    {
        $this->retrievers['file'] = array(
            'description' => 'Uploaded files',
            'repository' => $fileItemRepository
        );

        $this->retrievers['comment'] = array(
            'description' => 'File comments',
            'repository' => $commentoRepository
        );

        $this->retrievers['news'] = array(
            'description' => 'Sent news',
            'repository' => $newsRepository
        );

        $this->retrievers['link'] = array(
            'description' => 'Added links',
            'repository' => $linkRepository
        );

        $this->retrievers['post'] = array(
            'description' => 'Forum posts',
            'repository' => $postDAO
        );

        $this->retrievers['roles'] = array(
                'description' => 'Roles',
                'repository' => $ruoloRepository
        );

        $this->userRepository = $userRepository;
    }

    public function getOwnedResources(User $user)
    {
        $owned = array();

        foreach ($this->retrievers as $key => $retriever) {
            $owned[$key] = array (
                'count' =>  $retriever['repository']->countByUser($user),
                'description' => $retriever['description']
            );
        }

        return $owned;
    }

    public function getUsersFromPerson(Person $person, $includeLocked = false)
    {
        $users = $this->userRepository->findByPerson($person);

        if ($includeLocked) {
            return $users;
        }

        $unlockedUsers = array();

        foreach ($users as $user) {
            if (!$user->isLocked()) {
                $unlockedUsers[] = $user;
            }
        }

        return $unlockedUsers;
    }

    public function merge(User $target, array $others)
    {
        foreach ($this->retrievers as $retriever) {
            foreach ($others as $source) {
                $retriever['repository']->transferOwnership($source, $target);

                $source->setLocked(true);
                $this->userRepository->save($source);
            }
        }

        $target->setLocked(false);
        $this->userRepository->save($target);
    }
}
