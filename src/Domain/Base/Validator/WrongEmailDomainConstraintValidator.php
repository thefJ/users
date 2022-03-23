<?php

declare(strict_types=1);

namespace App\Domain\Base\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WrongEmailDomainConstraintValidator extends ConstraintValidator
{
    // В реальном проекте можно использовать любое хранилище для хранения запрещенных доменнов
    // и подтягивания вместо статического массива.
    private const WRONG_DOMAINS = [
        'test.ru'
    ];

    public function validate($value, Constraint $constraint)
    {
        foreach (self::WRONG_DOMAINS as $wrongDomain) {
            if (str_contains($value, '@' . $wrongDomain) > 0) {
                $this->context->buildViolation((string)$constraint)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
                return;
            }
        }
    }
}
