<?php declare(strict_types=1); 

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ViolationsService
{
    public function __construct(
        private ValidationFailedException $error
    ) {
    }

    public function toArray(): array
    {
        $errors = [];
        foreach ($this->error->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}