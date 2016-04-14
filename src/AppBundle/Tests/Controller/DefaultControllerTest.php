<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('New', $crawler->filter('.active.section')->text());
    }
    
    public function testPopular()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/popular');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Popular', $crawler->filter('.active.section')->text());
    }
}
