<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\Repository;

use Universibo\Bundle\ForumBundle\Entity\Forum;
use Universibo\Bundle\ForumBundle\Entity\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhpBB3ForumRepositoryTest extends WebTestCase
{
    /**
     * Forum repository
     *
     * @var ForumRepository
     */
    private $forumRepository;

    protected function setUp()
    {
        $this->markTestSkipped();

        static::createClient();
        $this->forumRepository = static::$kernel->getContainer()->get('universibo_forum.repository.forum');
    }

    public function testCreate()
    {
        $repo = $this->forumRepository;

        $forum = new Forum();

        $forum
            ->setName($name = 'Forum name')
            ->setDescription($description = 'Forum description')
            ->setType($type = Forum::TYPE_FORUM)
            ->setParentId($parent = 0)
        ;

        $repo->save($forum);

        $this->assertGreaterThan(0, $id = $forum->getId());

        $loaded = $repo->find($forum->getId());

        $this->assertEquals($name, $loaded->getName());
        $this->assertEquals($description, $loaded->getDescription());
        $this->assertEquals($type, $loaded->getType());
        $this->assertEquals($parent, $loaded->getParentId());
        $this->assertEquals($id, $loaded->getId());
    }

    public function testMaxId()
    {
        $this->assertGreaterThan(0, $this->forumRepository->getMaxId());
    }

    public function testUpdate()
    {
        $repo = $this->forumRepository;

        $id = $repo->getMaxId();

        $forum = $repo->find($id);

        $forum->setName($name = 'a' . $forum->getName());
        $forum->setDescription($description = 'a' . $forum->getDescription());
        $forum->setParentId($parentId = ($forum->getParentId() + 1));
        $forum->setType($type = (1 - $forum->getType()));

        $repo->save($forum);

        $loaded = $repo->find($id);

        $this->assertEquals($id, $loaded->getId());
        $this->assertEquals($name, $loaded->getName());
        $this->assertEquals($description, $loaded->getDescription());
        $this->assertEquals($parentId, $loaded->getParentId());
        $this->assertEquals($type, $loaded->getType());
    }

    public function testFindOneByName()
    {
        $repo = $this->forumRepository;

        $name = 'Forum name';

        // cleaning up
        while (null !== ($forum = $repo->findOneByName($name))) {
            $repo->delete($forum);
        }

        $forum = new Forum();

        $forum
            ->setName($name)
            ->setDescription($description = 'Forum description')
            ->setType($type = Forum::TYPE_FORUM)
        ;

        $repo->save($forum);

        $found = $repo->findOneByName($name);

        $this->assertInstanceOf('Universibo\\Bundle\\ForumBundle\\Entity\\Forum', $found);

        $this->assertEquals($name, $found->getName());
        $this->assertEquals($description, $found->getDescription());
        $this->assertEquals($type, $found->getType());
        $this->assertEquals($forum->getId(), $found->getId());
    }
}
