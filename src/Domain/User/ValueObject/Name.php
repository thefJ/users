<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\Base\Validator\WrongWordConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class Name
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min = 8, minMessage = "Your name must be at least {{ limit }} characters long")
     * @Assert\Regex(pattern="/^[a-z0-9]+$/", match=true, message="Name contains wrong chars")
     * @WrongWordConstraint
     */
    private string $name;

    private function __construct() {}

    public static function create(string $name): self
    {
        $nameObject = new self();
        $nameObject->name = $name;

        return $nameObject;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
