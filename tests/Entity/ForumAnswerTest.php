<?php

namespace App\Tests\Entity;

use App\Entity\ForumAnswer;
use App\Entity\ForumCategory;
use App\Entity\ForumSubject;
use App\Entity\User;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumSubjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForumAnswerTest extends KernelTestCase
{
    private $validator;
    private ForumCategoryRepository $forumCategoryRepository;
    private ForumSubjectRepository $forumSubjectRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->forumCategoryRepository = self::getContainer()->get(ForumCategoryRepository::class);
        $this->forumSubjectRepository = self::getContainer()->get(ForumSubjectRepository::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    public function createUser(): User
    {
        $user = new User();
        $user->setEmail('test@email.com')
            ->setPassword('Password1#')
            ->setRoles(['ROLE_USER'])
            ->setUsername('test')
            ->setFirstName('test')
            ->setIsAcceptedTerms(true)
        ;
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

    public function createSubject()
    {
        $this->createCategory();
        $category = $this->forumCategoryRepository->findOneBy(['slug' => 'test']);
        $forumSubject = (new ForumSubject())
            ->setAuthor($this->userRepository->findOneBy(['username' => 'test']))
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

        $this->forumSubjectRepository->save($forumSubject, true);
    }

    public function testNewAnswer()
    {
        $this->createSubject();
        $subject = $this->forumSubjectRepository->findOneBy(['slug' => 'teeeeeeeeeeeeeeest']);
        $forumAnswer = (new ForumAnswer())
            ->setAuthor($this->createUser())
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul'
            )
            ->setForumSubject($subject)
            ->setIsBan(false)
        ;
        $errors = $this->validator->validate($forumAnswer);
        $this->assertCount(0, $errors);
    }
}
