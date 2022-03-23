<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Base\Exception\ValidateException;
use App\Domain\Base\Interfaces\ValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as InternalValidatorInterface;

class Validator implements ValidatorInterface
{
    public function __construct(private InternalValidatorInterface $internalValidator)
    {

    }

    /**
     * @param mixed $value
     *
     * @throws ValidateException
     */
    public function validate(mixed $value): void
    {
        $errors = $this->internalValidator->validate($value);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[] = $violation->getMessage();
            }
            throw new ValidateException('Validation error: ' . implode(';' , $messages));
        }
    }
}
