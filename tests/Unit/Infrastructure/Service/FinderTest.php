<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Service;

use App\Domain\User\Entity\User;
use App\Infrastructure\Repository\User\UserRepository;
use App\Infrastructure\Service\Finder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    private const TEST_NAME = 'name';
    private const TEST_EMAIL = 'email@email.ru';
    private const TEST_NOTES = 'notes';

    protected function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->setName(self::TEST_NAME);
        $user->setEmail(self::TEST_EMAIL);
        $user->setNotes(self::TEST_NOTES);

        $repository = $this->createMock(UserRepository::class);
        $repository->method('find')->willReturn($user);
        $repository->method('findOneBy')->willReturn($user);
        $repository->method('findAll')->willReturn([$user]);
        $repository->method('findBy')->willReturn([$user]);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $this->finder = new Finder($entityManager);
    }

    public function testFindObject(): void
    {
        $user = $this->finder->findObject(1, User::class);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(self::TEST_NAME, $user->getName());
        $this->assertEquals(self::TEST_EMAIL, $user->getEmail());
        $this->assertEquals(self::TEST_NOTES, $user->getNotes());
    }

    public function testFindObjectBy(): void
    {
        $user = $this->finder->findObjectBy(['id' => 1], User::class);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(self::TEST_NAME, $user->getName());
        $this->assertEquals(self::TEST_EMAIL, $user->getEmail());
        $this->assertEquals(self::TEST_NOTES, $user->getNotes());
    }

    public function testFindAllObjects(): void
    {
        $users = $this->finder->findAllObjects(User::class);

        $this->assertCount(1, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals(self::TEST_NAME, $users[0]->getName());
        $this->assertEquals(self::TEST_EMAIL, $users[0]->getEmail());
        $this->assertEquals(self::TEST_NOTES, $users[0]->getNotes());
    }

    public function testFindAllBy(): void
    {
        $users = $this->finder->findAllBy(['deleted' => null], User::class);

        $this->assertCount(1, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals(self::TEST_NAME, $users[0]->getName());
        $this->assertEquals(self::TEST_EMAIL, $users[0]->getEmail());
        $this->assertEquals(self::TEST_NOTES, $users[0]->getNotes());
    }
}
