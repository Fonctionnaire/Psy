<?php

namespace App\Tests\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userPasswordHasher = $this->client->getContainer()->get('security.user_password_hasher');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    }

    private function createUser(): User
    {
        $user = new User();
        $user
            ->setEmail('user@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
            ->setUsername('username')
            ->setRoles(['ROLE_USER']);
        $this->userRepository->save($user, true);

        return $user;
    }

    public function testLoginAccess(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('form.login-form')->count());
        $this->assertSelectorTextContains('h1', 'CONNEXION');
    }

    public function testLoginSuccess(): void
    {
        $this->createUser();

        $this->client->request(Request::METHOD_GET, '/login');
        $this->client->submitForm('CONNEXION', ['_username' => 'user@email.fr', '_password' => 'password']);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success', 'Vous êtes désormais connecté.e.');
    }
}
