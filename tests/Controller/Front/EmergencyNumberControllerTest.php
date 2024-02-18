<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmergencyNumberControllerTest extends WebTestCase
{
    public function testEmergencyNumberPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/numeros-urgence');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'PRINCIPAUX NUMÉROS D\'URGENCE');
    }
}
