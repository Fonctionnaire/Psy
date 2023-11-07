<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactTest extends KernelTestCase
{
    private $validator;

    public function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidContact(): void
    {
        $contact = (new Contact())
            ->setEmail('test@mail.com')
            ->setFirstName('test')
            ->setSubject('test')
            ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lectus turpis, pharetra at faucibus et, aliquet at justo. Cras eget tempus turpis. Ut ac dapibus nunc. Donec viverra id libero id elementum. Pellentesque feugiat mi ac semper ullamcorper. In viverra felis nibh, ut feugiat sapien ultricies suscipit. Suspendisse eget eros quis ipsum vehicul')
            ->setIsTermsAccepted(true)
        ;

        $errors = $this->validator->validate($contact);
        $this->assertCount(0, $errors);
    }

    public function testInvalidContact(): void
    {
        $contact = (new Contact())
            ->setEmail('test@mail')
            ->setFirstName('test')
            ->setSubject('')
            ->setContent('Lorem')
            ->setIsTermsAccepted(false)
        ;

        $errors = $this->validator->validate($contact);
        $this->assertCount(3, $errors);
    }
}
