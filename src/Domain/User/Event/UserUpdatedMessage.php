<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\DTO\UserDTO;

class UserUpdatedMessage
{
    private UserDTO $userDTO;

    private function __construct() {}

    public static function create(UserDTO $userDTO): self
    {
        $dto = new self();
        $dto->userDTO = $userDTO;

        return $dto;
    }

    public function getUserDTO(): UserDTO
    {
        return $this->userDTO;
    }
}
