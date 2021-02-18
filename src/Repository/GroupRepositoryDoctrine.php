<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepositoryDoctrine extends ServiceEntityRepository implements GroupRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findActiveGroups(): array
    {
        return $this->findBy(['deleted' => false]);
    }

    public function findActive($id): ?Group
    {
        return $this->findOneBy(['id' => UUid::fromString($id), 'deleted' => false]);
    }
}
