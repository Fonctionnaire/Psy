<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\UserConversation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private $validator;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidUser(): void
    {
        $userConversation = new UserConversation();

        $user = (new User())
            ->setEmail('test@email.com')
            ->setPassword('Password1#')
            ->setRoles(['ROLE_USER'])
            ->setUsername('test')
            ->setFirstName('test')
            ->setIsAcceptedTerms(true)
            ->setUserConversation($userConversation)
        ;
        $errors = $this->validator->validate($user);
        $this->assertCount(0, $errors);
    }

    public function testInvalidUser(): void
    {
        $userConversation = new UserConversation();

        $user = (new User())
            ->setEmail('test')
            ->setPassword('Password1#')
            ->setUsername('test')
            ->setFirstName('test')
            ->setIsAcceptedTerms(true)
            ->setRoles(['ROLE_USER'])
            ->setUserConversation($userConversation)
        ;
        $errors = $this->validator->validate($user);
        $this->assertCount(1, $errors);
    }
}
