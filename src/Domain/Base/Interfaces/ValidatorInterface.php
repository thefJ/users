<?php

declare(strict_types=1);

namespace App\Domain\Base\Interfaces;

interface ValidatorInterface
{
    public function validate(mixed $value): void;
}
