<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Person;
use App\Repository\PersonRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class PersonServiceImpl implements PersonService
{
    private EntityManagerInterface $em;
    private PersonRepository $personRepo;
    private GroupRepository $groupRepo;

    /**
     * PersonServiceImpl constructor.
     */
    public function __construct(EntityManagerInterface $em, PersonRepository $personRepo, GroupRepository $groupRepo)
    {
        $this->em = $em;
        $this->personRepo = $personRepo;
        $this->groupRepo = $groupRepo;
    }

    public function createContact(string $name): string
    {
        $contact = new Person();
        $contact->setName($name);

        $this->em->persist($contact);
        $this->em->flush();

        return $contact->getId();
    }

    public function updateContactName(string $contactId, string $name): void
    {
        $contact = $this->personRepo->findActive($contactId);
        if (null != $contact && !$contact->isDeleted()) {
            $contact->setName($name);
            $this->em->persist($contact);
            $this->em->flush();
        } else {
            throw new InvalidArgumentException("Person doesn't exists");
        }
    }

    public function deleteContact(string $contactId): void
    {
        $contact = $this->personRepo->findActive($contactId);
        if (null != $contact) {
            $contact->markAsDeleted();
            $this->em->persist($contact);
            $this->em->flush();
        }
    }


    public function calculatePaginationInfo(int $pageSize): array
    {
        $totalItems = $this->personRepo->countActiveContact();
        $totalPages = ceil($totalItems / $pageSize);

        return [
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
        ];
    }

    public function listActiveContact(int $page, int $pageSize): array
    {
        return $this->personRepo->findActiveContact($page, $pageSize);
    }

    public function find(string $contactId)
    {
        return $this->personRepo->findActive($contactId);
    }

    public function addContactToGroup(string $contactId, string $groupId): void
    {
        $contact = $this->personRepo->findActive($contactId);
        if (!$contact) {
            throw new InvalidArgumentException('Person not found');
        }
        $group = $this->groupRepo->findActive($groupId);
        if (!$group) {
            throw new InvalidArgumentException('Group not found');
        }
        $contact->addGroup($group);
        $this->em->persist($contact);
        $this->em->flush();
    }

    public function removeContactFromGroup(string $contactId, string $groupId): void
    {
        $contact = $this->personRepo->findActive($contactId);
        if (!$contact) {
            throw new InvalidArgumentException('Person not found');
        }
        $group = $this->groupRepo->findActive($groupId);
        if (!$group) {
            throw new InvalidArgumentException('Group not found');
        }
        $contact->removeGroup($group);
        $this->em->persist($contact);
        $this->em->flush();
    }


}
