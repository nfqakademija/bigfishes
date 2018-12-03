<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class ValidateService
{
    public function isValidDate($value)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($value, [
            new NotBlank(),
            new Date(),
            new GreaterThanOrEqual("today"),
            new LessThanOrEqual("+30days")
        ]);

        if (count($violations) !== 0) {
            return false;
        }
        return true;
    }
}
