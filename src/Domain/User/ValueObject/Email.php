<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\Base\Validator\WrongEmailDomainConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class Email
{
    /**
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @WrongEmailDomainConstraint
     */
    private string $email;

    private function __construct() {}

    public static function create(string $email): self
    {
        $emailObject = new self();
        $emailObject->email = $email;

        return $emailObject;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
