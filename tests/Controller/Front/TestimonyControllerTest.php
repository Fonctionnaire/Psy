<?php

namespace App\Tests\Controller\Front;

use App\Entity\TestimonyCategory;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class TestimonyControllerTest extends WebTestCase
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

    public function createCategory(): TestimonyCategory
    {
        return (new TestimonyCategory())
            ->setName('test')
            ->setSlug('test')
        ;
    }

    public function testTestimonyIndexPage(): void
    {
        $this->client->request('GET', '/temoignages');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'LES TÉMOIGNAGES');
    }

    public function testCreateTestimony(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/temoignages/nouveau-temoignage');
        $form = $crawler->selectButton('testimony[submit]')->form([
            'testimony[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
            'testimony[testimonyCategory]' => '10',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('.alert.alert-success', 'Votre témoignage a bien été envoyé, il sera publié après validation par un administrateur');
    }
}
