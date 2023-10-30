<?php

namespace App\Tests\Entity;

use App\Entity\Testimony;
use App\Entity\TestimonyCategory;
use App\Entity\User;
use App\Repository\TestimonyCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestimonyTest extends KernelTestCase
{
    private $validator;
    private TestimonyCategoryRepository $testimonyCategoryRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
        $this->testimonyCategoryRepository = self::getContainer()->get(TestimonyCategoryRepository::class);
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
        $category = new TestimonyCategory();
        $category->setName('test')
            ->setName('test')
            ->setSlug('test')
        ;
        $this->testimonyCategoryRepository->save($category, true);
    }

    public function testValidTestimony(): void
    {
        $this->createCategory();
        $category = $this->testimonyCategoryRepository->findOneBy(['slug' => 'test']);
        $testimony = (new Testimony())
            ->setUser($this->createUser())
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul'
            )
            ->setTestimonyCategory($category)
            ->setIsValidated(true)
            ->setToken(Uuid::v4())
        ;

        $errors = $this->validator->validate($testimony);
        $this->assertCount(0, $errors);
    }

    public function testInvalidTestimony(): void
    {
        $this->createCategory();
        $category = $this->testimonyCategoryRepository->findOneBy(['slug' => 'test']);
        $testimony = (new Testimony())
            ->setUser($this->createUser())
            ->setContent(
                'test'
            )
            ->setTestimonyCategory($category)
            ->setIsValidated(true)
            ->setToken(Uuid::v4())
        ;

        $errors = $this->validator->validate($testimony);
        $this->assertCount(1, $errors);
    }
}
