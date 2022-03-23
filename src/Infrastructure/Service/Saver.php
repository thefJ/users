<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Base\Interfaces\EntityInterface;
use App\Domain\Base\Interfaces\SaverInterface;
use Doctrine\ORM\EntityManagerInterface;

class Saver implements SaverInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(?EntityInterface $entity = null): void
    {
        if (null !== $entity && !$this->entityManager->contains($entity)) {
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
    }
}
