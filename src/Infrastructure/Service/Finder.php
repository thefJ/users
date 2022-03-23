<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Base\Interfaces\FinderInterface;
use Doctrine\ORM\EntityManagerInterface;

class Finder implements FinderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findObject(int $id, string $className): mixed
    {
        return $this->entityManager->getRepository($className)->find($id);
    }

    public function findObjectBy(array $criteria, string $className): mixed
    {
        return $this->entityManager->getRepository($className)->findOneBy($criteria);
    }

    public function findAllObjects(string $className): array
    {
        return $this->entityManager->getRepository($className)->findAll();
    }

    public function findAllBy(array $criteria, string $className): array
    {
        return $this->entityManager->getRepository($className)->findBy($criteria);
    }
}
