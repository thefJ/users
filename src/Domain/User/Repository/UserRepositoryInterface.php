<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Name;
use App\Domain\User\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function findUser(UserId $userId): ?User;

    public function findActiveUser(UserId $userId): ?User;

    public function findUserByName(Name $name): ?User;

    public function findUserByEmail(Email $email): ?User;

    public function findAllActive(): array;

    public function findAllObjects(): array;

    public function create(UserDTO $userDTO);

    public function update(UserDTO $userDTO, UserId $userId);

    public function delete(UserId $userId);
}
