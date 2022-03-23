<?php

declare(strict_types=1);

namespace App\Domain\Base\Interfaces;

interface FinderInterface
{
    public function findObject(int $id, string $className): mixed;
    public function findObjectBy(array $criteria, string $className): mixed;
    public function findAllObjects(string $className): array;
    public function findAllBy(array $criteria, string $className): array;
}
