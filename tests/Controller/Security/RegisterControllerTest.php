<?php

namespace App\Tests\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class RegisterControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private UserPasswordHasher $userPasswordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userPasswordHasher = $this->client->getContainer()->get('security.user_password_hasher');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    }

    public function testCreateUser(): void
    {
        $this->client->request('GET', '/inscription');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "INSCRIPTION");

        $this->client->submitForm("register[submit]", [
            'register[username]' => 'test',
            'register[firstname]' => 'test',
            'register[email]' => 'test@mail.fr',
            'register[password][first]' => 'Password1#',
            'register[password][second]' => 'Password1#',
            'register[isAcceptedTerms]' => true,
        ]);
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('.alert.alert-success', 'Votre compte a bien été créé. Vous allez recevoir un email afin de confirmer votre inscription.');
    }
}