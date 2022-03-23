<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\User;

class UserFactory
{
    public function createdFromUserDTO(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setName((string)$userDTO->getName());
        $user->setEmail((string)$userDTO->getEmail());
        $user->setNotes($userDTO->getNotes());

        return $user;
    }
}
