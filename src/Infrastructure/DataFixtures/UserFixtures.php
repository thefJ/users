<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const FIRST_TEST_NAME = 'user1';
    public const FIRST_TEST_EMAIL = 'test@ya.ru';
    public const SECOND_TEST_NAME = 'user2';
    public const SECOND_TEST_EMAIL = 'user2@ya.ru';

    private const DATA_SET = [
        [self::FIRST_TEST_NAME, self::FIRST_TEST_EMAIL, 'Some notes', false],
        [self::SECOND_TEST_NAME, self::SECOND_TEST_EMAIL, 'Some notes again', true],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA_SET as $data) {
            $this->loadUser($manager, ...$data);
        }

        $manager->flush();
    }

    private function loadUser(
        ObjectManager $manager,
        string $name,
        string $email,
        string $notes,
        bool $isDeleted
    ): void {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setNotes($notes);
        if ($isDeleted) {
            $user->delete();
        }

        $manager->persist($user);
    }
}
