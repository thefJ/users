<?php

declare(strict_types=1);

namespace App\Domain\Base\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WrongWordConstraintValidator extends ConstraintValidator
{
    // В реальном проекте можно использовать любое хранилище для хранения некорректных слов
    // и подтягивания вместо статического массива.
    private const WRONG_WORDS = [
        'wrong'
    ];

    public function validate($value, Constraint $constraint)
    {
        foreach (self::WRONG_WORDS as $wrongWord) {
            if (str_contains($value, $wrongWord)) {
                $this->context->buildViolation((string)$constraint)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
                return;
            }
        }
    }
}
