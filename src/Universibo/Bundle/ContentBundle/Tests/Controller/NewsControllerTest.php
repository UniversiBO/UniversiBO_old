<?php
namespace Universibo\Bundle\ContentBundle\Tests\Controller;

use Universibo\Bundle\ContentBundle\Entity\News;

use Universibo\Bundle\WebsiteBundle\Tests\Controller\BaseControllerTest;

class NewsControllerTest extends BaseControllerTest
{
    /**
     * User Story functional test
     * As User I want to see a single news (issue #26)
     */
    public function testShow()
    {
        $client = static::createClient();

        $this->login($client);

        $crawler = $client->request('GET', '/news/1/show');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Expecting successful response');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("News title")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("News content")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("www.google.it")')->count());
    }

    public function testDelete()
    {
        $client = static::createClient();
        $em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $channel = $em->getRepository('UniversiboCoreBundle:Channel')->find(3);

        $news = new News();
        $news->setTitle('Delete me title');
        $news->setContent('Delete me content');
        $news->setCreatedAt(new \DateTime());
        $news->setUpdatedAt(new \DateTime());
        $news->getChannels()->add($channel);

        $em->persist($news);
        $em->flush();

        $this->login($client);

        $crawler = $client->request('GET', '/news/'.$news->getId().'/show');
        $form = $crawler
            ->selectButton('Delete')
            ->form();

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), 'Expecting redirect response');
    }
}
