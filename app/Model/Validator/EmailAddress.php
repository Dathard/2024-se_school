<?php

declare(strict_types=1);

namespace App\Model\Validator;

class EmailAddress
{
    /**
     * @param string $emailAddress
     * @return bool
     */
    public function isValid(string $emailAddress): bool
    {
        return (bool) filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
    }
}