<?php
namespace Universibo\Bundle\ContentBundle\Tests\Controller;

use Universibo\Bundle\WebsiteBundle\Tests\Controller\BaseControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
}
