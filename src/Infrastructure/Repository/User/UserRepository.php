<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\User;

use App\Domain\Base\Exception\NotFoundEntityException;
use App\Domain\Base\Interfaces\FinderInterface;
use App\Domain\Base\Interfaces\SaverInterface;
use App\Domain\Base\Interfaces\ValidatorInterface;
use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Event\UserUpdatedMessage;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Name;
use App\Domain\User\ValueObject\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private ValidatorInterface $validator,
        private UserFactory $userFactory,
        private SaverInterface $saver,
        private EventDispatcherInterface $eventDispatcher,
        private FinderInterface $finder
    ) {
        parent::__construct($registry, User::class);
    }

    public function findUser(UserId $userId): ?User
    {
        return $this->finder->findObject($userId->getNumber(),User::class);
    }

    public function findActiveUser(UserId $userId): ?User
    {
        return $this->finder->findObjectBy(['id' => $userId->getNumber(), 'deleted' => null],User::class);
    }

    public function findUserByName(Name $name): ?User
    {
        return $this->finder->findObjectBy(['name' => (string)$name],User::class);
    }

    public function findUserByEmail(Email $email): ?User
    {
        return $this->finder->findObjectBy(['email' => (string)$email],User::class);
    }

    public function findAllActive(): array
    {
        return $this->finder->findAllBy(['deleted' => null], User::class);
    }

    public function findAllObjects(): array
    {
        return $this->finder->findAllObjects(User::class);
    }

    public function create(UserDTO $userDTO)
    {
        $this->validator->validate($userDTO);

        $user = $this->userFactory->createdFromUserDTO($userDTO);

        $this->validator->validate($user);

        $this->saver->save($user);
    }

    /**
     * @param UserDTO $userDTO
     * @param UserId $userId
     *
     * @throws NotFoundEntityException
     */
    public function update(UserDTO $userDTO, UserId $userId)
    {
        $this->validator->validate($userDTO);

        $user = $this->findUser($userId);

        if (!$user) {
            throw new NotFoundEntityException('User not found');
        }

        $user->updateFromUserDTO($userDTO);

        $this->validator->validate($user);

        $this->saver->save($user);

        // Сообщение уходит синхронно, если есть необходимость уменьшить время на обработку события обновления,
        // данное сообщение можно переключить на брокер сообщений (условный кролик или кафка) и обрабатывать асинхронно
        //
        // Для перехвата событий изменения сущностей у Доктрины есть свои события,
        // но в тестовом задании их использовать не стал,
        // т.к хотел чтобы отправка события отразилось в логике обновления юзера
        $this->eventDispatcher->dispatch(UserUpdatedMessage::create($userDTO));
    }

    /**
     * @param UserId $userId
     *
     * @throws NotFoundEntityException
     */
    public function delete(UserId $userId)
    {
        $user = $this->findUser($userId);

        if (!$user) {
            throw new NotFoundEntityException('User not found');
        }

        $user->delete();

        $this->saver->save($user);
    }
}
