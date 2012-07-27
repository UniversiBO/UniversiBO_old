<?php

namespace Universibo\Bundle\WebsiteBundle\Tests\Controller;

use Symfony\Component\BrowserKit\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
abstract class BaseControllerTest extends WebTestCase
{
    protected function login(Client $client, $username = 'admin', $password = 'password')
    {
        $client->restart();
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'Response should be successful');

        $form = $crawler
            ->selectButton('Login')
            ->form()
        ;

        $client->submit($form, array(
                '_username' => $username,
                '_password' => $password
        ));

        $this->assertTrue($client->getResponse()->isRedirect(), 'Response should be redirect');

        $crawler = $client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benvenuto")')->count());
    }
}
