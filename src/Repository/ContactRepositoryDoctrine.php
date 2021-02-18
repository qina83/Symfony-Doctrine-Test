<?php

namespace App\Repository;

use App\Model\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepositoryDoctrine extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function countActiveContact(): int
    {
        return $this->count(["deleted" => false]);
    }

    public function findActiveContact(int $page, int $pageSize): array
    {
        return $this->findBy(["deleted" => false], null, $pageSize, $pageSize * ($page-1));
    }

    public function findActive(string $id): ?Contact
    {
        return $this->findOneBy(["id" => UUid::fromString($id), "deleted" => false]);
    }


}
