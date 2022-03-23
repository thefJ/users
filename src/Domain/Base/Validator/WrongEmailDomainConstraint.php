<?php

declare(strict_types=1);

namespace App\Domain\Base\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WrongEmailDomainConstraint extends Constraint
{
    public function __toString(): string
    {
        return '{{ string }} has wrong domain.';
    }
}
