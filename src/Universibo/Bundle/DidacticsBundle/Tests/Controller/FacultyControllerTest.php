<?php
namespace Universibo\Bundle\WebsiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FacultyControllerTest extends BaseControllerTest
{
    public function testIngegneria()
    {
        $client = static::createClient();
        
        $this->login($client);
        
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Response should be successful');
        
        $link = $crawler->filter('a:contains("Ingegneria")')->eq(0)->link();
        
        $crawler = $client->click($link);
        
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Response should be successful');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("INGEGNERIA")')->count());
    }
}
