<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Base\Interfaces\EntityInterface;
use App\Domain\User\DTO\UserDTO;
use App\Infrastructure\Repository\User\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="users_name_uindex", columns={"name"}),
 *         @ORM\UniqueConstraint(name="users_email_uindex", columns={"email"})
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("name")
 * @UniqueEntity("email")
 */
class User implements EntityInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $deleted = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getDeleted(): ?DateTime
    {
        return $this->deleted;
    }

    public function delete(): void
    {
        $this->deleted = new DateTime();
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function updateFromUserDTO(UserDTO $userDTO): void
    {
        $this->setName((string)$userDTO->getName());
        $this->setEmail((string)$userDTO->getEmail());
        $this->setNotes($userDTO->getNotes());
    }
}
