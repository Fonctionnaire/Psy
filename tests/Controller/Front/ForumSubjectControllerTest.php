<?php

namespace App\Tests\Controller\Front;

use App\Entity\ForumCategory;
use App\Entity\User;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumSubjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class ForumSubjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private UserPasswordHasher $userPasswordHasher;
    private ForumSubjectRepository $forumSubjectRepository;
    private ForumCategoryRepository $forumCategoryRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userPasswordHasher = $this->client->getContainer()->get('security.user_password_hasher');
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->forumSubjectRepository = self::getContainer()->get(ForumSubjectRepository::class);
        $this->forumCategoryRepository = self::getContainer()->get(ForumCategoryRepository::class);
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

    public function createCategory(): void
    {
        $category = new ForumCategory();
        $category->setName('test')
            ->setSlug('test')
        ;
        $this->forumCategoryRepository->save($category, true);
    }

    public function testForumSubjectIndexPage(): void
    {
        $this->client->request('GET', '/forum/sujets');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'TOUS LES SUJETS');
    }

    public function newSubjectWithoutLogin(): void
    {
        $this->client->request('GET', '/forum/nouveau-sujet');
        $this->assertResponseRedirects('/login');
    }

    public function newSubjectWithLogin(): void
    {
        $this->client->request('GET', '/forum/sujets');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->loginUser($this->createUser());

        $this->client->clickLink('Nouveau sujet');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'NOUVEAU SUJET');
    }

    public function testNewSubjectPage(): void
    {
        $this->createCategory();
        $category = $this->forumCategoryRepository->findOneBy(['slug' => 'test']);
        $this->client->loginUser($this->createUser());
        $crawler = $this->client->request('GET', '/forum/nouveau-sujet');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'NOUVEAU SUJET');

        $form = $crawler->selectButton('forum_subject[submit]')->form([
            'forum_subject[subject]' => 'teeeeeeeeeeeeeeeeest',
            'forum_subject[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
            'forum_subject[forumCategory]' => $category->getId(),
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/forum/sujet/teeeeeeeeeeeeeeeeest');
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function forumAnswerTest(): void
    {
        $this->testNewSubjectPage();
        $crawler = $this->client->request('GET', '/forum/sujet/teeeeeeeeeeeeeeeeest');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1', 'teeeeeeeeeeeeeeeeest');
        $this->assertSelectorExists('a', 'Répondre');

        $this->client->clickLink('Répondre');

        $form = $crawler->form([
            'forum_answer[content]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/forum/sujet/teeeeeeeeeeeeeeeeest');
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('.alert.alert-success', 'Votre réponse a bien été envoyée');
    }
}
