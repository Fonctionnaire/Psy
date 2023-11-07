<?php

namespace App\Service\Email;

use App\Entity\Contact;

interface ContactMailInterface
{
    public function sendToUser(Contact $contact): void;

    public function sendToAdmin(Contact $contact): void;
}
