<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'Response should be successful');
        $this->assertTrue($crawler->filter('html:contains("UniversiBO")')->count() > 0);
    }
}
