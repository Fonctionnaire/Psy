<?php

namespace App\Tests\Controller\Front;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private ContactRepository $contactRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->contactRepository = self::getContainer()->get(ContactRepository::class);
    }

    public function testContactPage(): void
    {
        $this->client->request('GET', '/nous-contacter');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'NOUS CONTACTER');
    }

    public function testContactSend(): void
    {
        $crawler = $this->client->request('GET', '/nous-contacter');
        $form = $crawler->selectButton('contact[submit]')->form([
            'contact[firstname]' => 'test',
            'contact[email]' => 'test@test.com',
            'contact[subject]' => 'Demande d\'informations',
            'contact[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
            'contact[isTermsAccepted]' => true,
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('.alert.alert-success', 'Votre message a bien été envoyé. Nous vous répondrons le plus rapidement possible.');
    }
}
