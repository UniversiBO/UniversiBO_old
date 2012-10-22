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
            'count' => function(User $user) use ($fileItemRepository) {
                return $fileItemRepository->countByUser($user);
            }
        );

        $this->retrievers['comment'] = array(
            'description' => 'File comments',
            'count' => function(User $user) use ($commentoRepository) {
                return $commentoRepository->countByUser($user);
            }
        );

        $this->retrievers['news'] = array(
            'description' => 'Sent news',
            'count' => function(User $user) use ($newsRepository) {
                return $newsRepository->countByUser($user);
            }
        );

        $this->retrievers['link'] = array(
            'description' => 'Added links',
            'count' => function(User $user) use ($linkRepository) {
                return $linkRepository->countByUser($user);
            }
        );
    }

    public function getOwnedResources(User $user)
    {
        $owned = array();

        foreach ($this->retrievers as $key => $retriever) {
            $owned[$key] = array (
                'count' =>  $retriever['count']($user),
                'description' => $retriever['description']
            );
        }

        return $owned;
    }

    public function getUsersFromPerson(Person $person, $includeLocked = false)
    {
    }

    public function merge(User $target, array $others)
    {
    }
}
