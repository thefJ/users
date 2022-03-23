<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Repository\User;

use App\Domain\Base\Exception\NotFoundEntityException;
use App\Domain\Base\Exception\ValidateException;
use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Name;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepositoryInterface $userRepository;

    private const ACTIVE_USER_COUNT = 1;
    private const ALL_USER_COUNT = 2;
    private const ACTIVE_USER_ID = 1;
    private const DELETED_USER_ID = 2;
    private const NOT_EXISTS_USER_ID = 99999;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepositoryInterface::class);
    }

    /**
     * @dataProvider findUserProvider
     */
    public function testFindUser(UserId $userId): void
    {
        $user = $this->userRepository->findUser($userId);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userId->getNumber(), $user->getId());
    }

    public function findUserProvider(): array
    {
        return [
            [UserId::create(self::ACTIVE_USER_ID)],
            [UserId::create(self::DELETED_USER_ID)],
        ];
    }

    /**
     * @dataProvider findActiveUserProvider
     */
    public function testFindActiveUser(UserId $userId, ?string $instanceOf): void
    {
        $user = $this->userRepository->findActiveUser($userId);
        if ($instanceOf) {
            $this->assertInstanceOf($instanceOf, $user);
        } else {
            $this->assertNull($user);
        }
    }

    public function findActiveUserProvider(): array
    {
        return [
            [UserId::create(self::ACTIVE_USER_ID), User::class],
            [UserId::create(self::DELETED_USER_ID), null],
        ];
    }

    /**
     * @dataProvider findUserByNameProvider
     */
    public function testFindUserByName(Name $name): void
    {
        $user = $this->userRepository->findUserByName($name);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($name->getValue(), $user->getName());
    }

    public function findUserByNameProvider(): array
    {
        return [
            [Name::create(UserFixtures::FIRST_TEST_NAME)],
            [Name::create(UserFixtures::SECOND_TEST_NAME)],
        ];
    }

    /**
     * @dataProvider findUserByEmailProvider
     */
    public function testFindUserByEmail(Email $email): void
    {
        $user = $this->userRepository->findUserByEmail($email);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email->getValue(), $user->getEmail());
    }

    public function findUserByEmailProvider(): array
    {
        return [
            [Email::create(UserFixtures::FIRST_TEST_EMAIL)],
            [Email::create(UserFixtures::SECOND_TEST_EMAIL)],
        ];
    }

    public function testFindAllActive(): void
    {
        $users = $this->userRepository->findAllActive();

        $this->assertCount(self::ACTIVE_USER_COUNT, $users);
        $this->assertInstanceOf(User::class, $users[0]);

    }

    public function testFindAllObjects(): void
    {
        $users = $this->userRepository->findAllObjects();

        $this->assertCount(self::ALL_USER_COUNT, $users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreated(UserDTO $userDTO, ?string $exceptionClass): void
    {
        $currentDateTime = new DateTime();

        if ($exceptionClass) {
            $this->expectException($exceptionClass);
        }

        $this->userRepository->create($userDTO);

        if (!$exceptionClass) {
            $user = $this->userRepository->findUserByEmail($userDTO->getEmail());

            $this->assertInstanceOf(User::class, $user);
            $this->assertEquals($userDTO->getName(), $user->getName());
            $this->assertEquals($userDTO->getEmail(), $user->getEmail());
            $this->assertEquals($userDTO->getNotes(), $user->getNotes());
            $this->assertNotNull($user->getCreated());
            $this->assertNull($user->getDeleted());
            $this->assertTrue($currentDateTime < $user->getCreated());
        }
    }

    public function createProvider(): array
    {
        return [
            [UserDTO::create(Name::create('testuser1'), Email::create('test@testuser.ru'), 'Some Notes'), null],
            [UserDTO::create(Name::create('testuser2'), Email::create(UserFixtures::FIRST_TEST_EMAIL), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('Testuser3'), Email::create('test3@testuser.ru'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('tt'), Email::create('test3@testuser.ru'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('testuser3'), Email::create('test'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create(UserFixtures::SECOND_TEST_NAME), Email::create('test2@testuser.ru'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('testuser2'), Email::create('test@test.ru'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('wrongtestuser2'), Email::create('test2@testuser.ru'), 'Some Notes'), ValidateException::class],
            [UserDTO::create(Name::create('testuser2'), Email::create('test2@testuser.ru')), null],
        ];
    }

    /**
     * @dataProvider updateProvider
     */
    public function testUpdate(UserDTO $userDTO, UserId $userId, ?string $exceptionClass): void
    {
        if ($exceptionClass) {
            $this->expectException($exceptionClass);
        } else {
            $user = $this->userRepository->findUser($userId);

            $oldCreated = $user->getCreated();
            $oldDeleted = $user->getDeleted();
        }

        $this->userRepository->update($userDTO, $userId);

        if (!$exceptionClass) {
            $this->assertEquals($userDTO->getName(), $user->getName());
            $this->assertEquals($userDTO->getEmail(), $user->getEmail());
            $this->assertEquals($userDTO->getNotes(), $user->getNotes());
            $this->assertNotNull($user->getCreated());
            $this->assertEquals($oldCreated, $user->getCreated());
            $this->assertEquals($oldDeleted, $user->getDeleted());
        }
    }

    public function updateProvider(): array
    {
        return [
            [UserDTO::create(Name::create(UserFixtures::FIRST_TEST_NAME), Email::create(UserFixtures::SECOND_TEST_EMAIL), 'Some Notes'), UserId::create(self::ACTIVE_USER_ID), ValidateException::class],
            [UserDTO::create(Name::create(UserFixtures::FIRST_TEST_NAME), Email::create(UserFixtures::SECOND_TEST_EMAIL), 'Some Notes'), UserId::create(self::DELETED_USER_ID), ValidateException::class],
            [UserDTO::create(Name::create('testuser1'), Email::create('test@testuser.ru'), 'Some Notes'), UserId::create(self::ACTIVE_USER_ID), null],
            [UserDTO::create(Name::create('testuser2'), Email::create('test2@testuser.ru'), 'Some Notes'), UserId::create(self::DELETED_USER_ID), null],
            [UserDTO::create(Name::create(UserFixtures::FIRST_TEST_NAME), Email::create('test@testuser.ru'), 'Some Notes'), UserId::create(self::DELETED_USER_ID), ValidateException::class],
            [UserDTO::create(Name::create('testuser1'), Email::create('test@testuser.ru'), 'Some Notes'), UserId::create(self::NOT_EXISTS_USER_ID), NotFoundEntityException::class],
        ];
    }

    /**
     * @dataProvider deleteProvider
     */
    public function testDelete(UserId $userId, ?string $exceptionClass): void
    {
        if ($exceptionClass) {
            $this->expectException($exceptionClass);
        }

        $this->userRepository->delete($userId);

        if (!$exceptionClass) {
            $emptyUser = $this->userRepository->findActiveUser($userId);
            $deletedUser = $this->userRepository->findUser($userId);
            $this->assertNull($emptyUser);
            $this->assertInstanceOf(User::class, $deletedUser);
        }

    }

    public function deleteProvider(): array
    {
        return [
            [UserId::create(self::ACTIVE_USER_ID), null],
            [UserId::create(self::DELETED_USER_ID), null],
            [UserId::create(self::NOT_EXISTS_USER_ID), NotFoundEntityException::class],
        ];
    }
}
