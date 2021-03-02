<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepositoryDoctrine extends ServiceEntityRepository implements PersonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function countActivePersons(): int
    {
        return $this->count(['deleted' => false]);
    }

    public function findActivePersons(Page $page): array
    {
        return $this->findBy(['deleted' => false], null, $page->getSize(), $page->getOffset());
    }

    public function findActive(string $id): ?Person
    {
        return $this->findOneBy(['id' => UUid::fromString($id), 'deleted' => false]);
    }
}
