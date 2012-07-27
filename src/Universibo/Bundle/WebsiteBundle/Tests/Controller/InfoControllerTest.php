<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Controller;

class InfoControllerTest extends BaseControllerTest
{
    public function testRules()
    {
        $client = static::createClient();
        $this->login($client);

        $crawler = $client->request('GET', '/info/rules');

        $this->assertGreaterThan(0,$crawler->filter('html:contains("REGOLAMENTO")')->count());
        $this->assertGreaterThan(0,$crawler->filter('html:contains("Informativa")')->count());
    }
}
