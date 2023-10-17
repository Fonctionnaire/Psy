<?php

namespace App\Service\CensorUserEmail;

class CensorUserEmail implements CensorUserEmailInterface
{
    public function __invoke(string $email): string
    {
        list($localPart, $domainPart) = explode('@', $email);

        $localPartLength = strlen($localPart);

        $censorLength = ceil($localPartLength / 2);

        $censorString = str_repeat('*', $censorLength);

        $censoredLocalPart = substr($localPart, 0, $localPartLength - $censorLength).$censorString;
        $censoredDomainPart = str_repeat('*', floor(strlen($domainPart) / 2)).substr($domainPart, floor(strlen($domainPart) / 2));
        $censoredEmail = $censoredLocalPart.'@**'.$censoredDomainPart;

        return $censoredEmail;
    }
}
