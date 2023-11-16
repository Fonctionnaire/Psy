<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\UserMessage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserMessageTest extends KernelTestCase
{
    private $validator;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidUserMessage(): void
    {
        $userMessage = (new UserMessage())
            ->setUser($this->createMock(User::class))
            ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul');

        $errors = $this->validator->validate($userMessage);
        $this->assertCount(0, $errors);
    }
}
