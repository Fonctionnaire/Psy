<?php

namespace App\Tests\Controller\Front;

use App\Entity\User;
use App\Entity\UserConversation;
use App\Repository\UserConversationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserConversationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private UserPasswordHasher $userPasswordHasher;
    private UserConversationRepository $userConversationRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userPasswordHasher = $this->client->getContainer()->get('security.user_password_hasher');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->userConversationRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(UserConversation::class);
    }

    private function createUser(): User
    {
        $user = new User();
        $userConversation = new UserConversation();

        $user
            ->setEmail('user@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'UserPassword1#'))
            ->setUsername('username')
            ->setRoles(['ROLE_USER'])
            ->setUserConversation($userConversation)
        ;
        $userConversation->setUser($user)
        ;
        $this->userConversationRepository->save($userConversation, true);
        $this->userRepository->save($user, true);

        return $user;
    }

    private function createAdmin(): User
    {
        $user = new User();
        $userConversation = new UserConversation();

        $user
            ->setEmail('admin@email.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'UserPassword1#'))
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setUserConversation($userConversation)
        ;
        $userConversation->setUser($user)
        ;
        $this->userConversationRepository->save($userConversation, true);
        $this->userRepository->save($user, true);

        return $user;
    }

    public function testUserConversationIndex(): void
    {
        $this->client->request('GET', '/message-prive');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'MESSAGES PRIVÉS');
    }

    public function testUserConversationAccessWithoutLogin(): void
    {
        $user = $this->createUser();

        $this->client->request('GET', '/message-prive/ma-discussion/'.$user->getId().'/'.$user->getUserConversation()->getToken());
        $this->assertResponseRedirects();
    }

    public function testUserConversationAccessWithLogin(): void
    {
        $user = $this->createUser();

        $this->client->loginUser($user);

        $this->client->request('GET', '/message-prive/ma-discussion/'.$user->getId().'/'.$user->getUserConversation()->getToken());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'MA CONVERSATION');
    }

    public function testUserConversationSubmitForm(): void
    {
        $this->createAdmin();
        $user = $this->createUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/message-prive/ma-discussion/'.$user->getId().'/'.$user->getUserConversation()->getToken());

        $this->client->submitForm('user_message[submit]', [
            'user_message[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
        ]);

        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'MA CONVERSATION');
        $this->assertSelectorExists('.alert.alert-success', 'Votre message a bien été envoyé.');
    }

    public function testUserConversationAdminSubmitForm(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();
        $this->client->loginUser($admin);

        $this->client->request('GET', '/message-prive/ma-discussion/'.$user->getId().'/'.$user->getUserConversation()->getToken());

        $this->client->submitForm('user_message[submit]', [
            'user_message[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
        ]);

        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'MA CONVERSATION');
        $this->assertSelectorExists('.alert.alert-success', 'Votre message a bien été envoyé.');
    }
}
