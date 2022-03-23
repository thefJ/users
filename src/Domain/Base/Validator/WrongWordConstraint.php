<?php

declare(strict_types=1);

namespace App\Domain\Base\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WrongWordConstraint extends Constraint
{
    public function __toString(): string
    {
        return '{{ string }} contains wrong word.';
    }
}
