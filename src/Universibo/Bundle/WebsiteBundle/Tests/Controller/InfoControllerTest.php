<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InfoControllerTest extends WebTestCase
{
    public function testRules()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/info/rules');

        $this->assertGreaterThan(0,$crawler->filter('html:contains("REGOLAMENTO")')->count());
        $this->assertGreaterThan(0,$crawler->filter('html:contains("Informativa")')->count());
    }
}
