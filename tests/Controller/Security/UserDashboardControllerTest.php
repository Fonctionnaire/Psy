<?php

namespace App\Tests\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserDashboardControllerTest extends WebTestCase
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

    private function createUser(): User
    {
        $user = new User();
        $user
            ->setEmail('user@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'UserPassword1#'))
            ->setUsername('username')
            ->setRoles(['ROLE_USER']);
        $this->userRepository->save($user, true);

        return $user;
    }

    public function testUserDashboardAccessWithoutLogin(): void
    {
        $this->client->request('GET', '/utilisateur/mon-compte/'. $this->createUser()->getId());
        $this->assertResponseRedirects();
    }

    public function testUserDashboardAccessWithLogin(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);
        $this->client->request('GET', '/utilisateur/mon-compte/'. $user->getId());
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'MON PROFIL');
    }

    public function testUserDashboardEditPage(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);
        $this->client->request('GET', '/utilisateur/mon-compte/'. $user->getId() . '/edition');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'MODIFIER MES INFORMATIONS');
    }

    public function testUserDashboardEditInfo(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);
        $this->client->request('GET', '/utilisateur/mon-compte/'. $user->getId() . '/edition');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->submitForm('user_edit[submit]', ['user_edit[email]' => 'newemail@email.fr', 'user_edit[username]' => 'newPseudo' , 'user_edit[firstname]' => 'newFirstname', 'user_edit[plainPassword]' => 'UserPassword2Edit#']);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'MON PROFIL');
        $this->assertSelectorExists('.alert.alert-success', 'Votre compte a bien été modifié.');

        $editedUser = $this->userRepository->findOneByUsername('newPseudo');
dd($editedUser);
        $this->assertNotNull($editedUser);
        $this->assertEquals('newPseudo', $editedUser->getUsername());
    }
}
