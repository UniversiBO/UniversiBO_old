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
        $this->assertGreaterThan(0,$crawler->filter('html:contains("UniversiBO è la community di studenti e docenti dell\'Università di Bologna")')->count());
    }
}
