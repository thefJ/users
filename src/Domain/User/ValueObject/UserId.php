<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

class UserId
{
    private int $number;

    private function __construct() {}

    public static function create(int $number): self
    {
        $userId = new self();
        $userId->number = $number;

        return $userId;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
