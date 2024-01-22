<?php

namespace App\Tests\Entity;

use App\Entity\ForumCategory;
use App\Entity\ForumSubject;
use App\Entity\User;
use App\Repository\ForumCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForumSubjectTest extends KernelTestCase
{
    private $validator;
    private ForumCategoryRepository $forumCategoryRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->forumCategoryRepository = self::getContainer()->get(ForumCategoryRepository::class);
    }

    public function createUser(): User
    {
        return (new User())
            ->setEmail('test@email.com')
            ->setPassword('Password1#')
            ->setRoles(['ROLE_USER'])
            ->setUsername('test')
            ->setFirstName('test')
            ->setIsAcceptedTerms(true)
        ;
    }

    public function createCategory(): void
    {
        $category = new ForumCategory();
        $category->setName('test')
            ->setSlug('test')
        ;
        $this->forumCategoryRepository->save($category, true);
    }

    public function testValidForumSubject()
    {
        $this->createCategory();
        $category = $this->forumCategoryRepository->findOneBy(['slug' => 'test']);
        $forumSubject = (new ForumSubject())
            ->setAuthor($this->createUser())
            ->setSubject('teeeeeeeeeeeeeeest')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul'
            )
            ->setForumCategory($category)
            ->setIsRule(false)
            ->setSlug('teeeeeeeeeeeeeeest')
            ->setIsBan(true)
        ;
        $errors = $this->validator->validate($forumSubject);
        $this->assertCount(0, $errors);
    }

    public function testInvalidForumSubject()
    {
        $this->createCategory();
        $category = $this->forumCategoryRepository->findOneBy(['slug' => 'test']);
        $forumSubject = (new ForumSubject())
            ->setAuthor($this->createUser())
            ->setSubject('test')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul'
            )
            ->setForumCategory($category)
            ->setIsRule(false)
            ->setSlug('test')
            ->setIsBan(true)
        ;
        $errors = $this->validator->validate($forumSubject);
        $this->assertCount(1, $errors);
    }
}
