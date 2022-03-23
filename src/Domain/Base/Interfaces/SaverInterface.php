<?php

declare(strict_types=1);

namespace App\Domain\Base\Interfaces;

interface SaverInterface
{
    public function save(?EntityInterface $entity = null): void;
}
