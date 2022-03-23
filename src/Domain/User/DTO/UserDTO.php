<?php

declare(strict_types=1);

namespace App\Domain\User\DTO;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Name;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\Valid()
     */
    private Name $name;

    /**
     * @Assert\Valid()
     */
    private Email $email;

    private ?string $notes;

    private function __construct()
    {
    }

    public static function create(Name $name, Email $email, ?string $notes = null): self
    {
        $dto = new self();
        $dto->name = $name;
        $dto->email = $email;
        $dto->notes = $notes;

        return $dto;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
