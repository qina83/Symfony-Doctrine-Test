<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Person;
use App\Persister\PersonPersister;
use App\Repository\PersonRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;


class PersonServiceImpl implements PersonService
{
    private PersonPersister $personPersister;
    private PersonRepository $personRepo;
    private GroupRepository $groupRepo;

    /**
     * PersonServiceImpl constructor.
     */
    public function __construct(PersonPersister $personPersister, PersonRepository $personRepo, GroupRepository $groupRepo)
    {
        $this->personPersister = $personPersister;
        $this->personRepo = $personRepo;
        $this->groupRepo = $groupRepo;
    }

    public function createPerson(string $name): string
    {
        $person = new Person($name);
        $this->personPersister->persist($person);

        return $person->getId();
    }

    public function updatePersonPersonalInfo(string $personId, string $name): void
    {
        $person = $this->personRepo->findActive($personId);
        if (null != $person && !$person->isDeleted()) {
            $person->updatePersonalInfo($name);
            $this->personPersister->persist($person);
        } else {
            throw new InvalidArgumentException("Person doesn't exists");
        }
    }

    public function deletePerson(string $personId): void
    {
        $person = $this->personRepo->findActive($personId);
        if (null != $person) {
            $person->markAsDeleted();
            $this->personPersister->persist($person);
        }
    }


    public function calculatePaginationInfo(int $pageSize): array
    {
        $totalItems = $this->personRepo->countActivePersons();
        $totalPages = ceil($totalItems / $pageSize);

        return [
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
        ];
    }

    public function listActivePersons(int $page, int $pageSize): array
    {
        return $this->personRepo->findActivePersons($page, $pageSize);
    }

    public function find(string $personId)
    {
        return $this->personRepo->findActive($personId);
    }

    public function addPersonToGroup(string $personId, string $groupId): void
    {
        $person = $this->personRepo->findActive($personId);
        if (!$person) {
            throw new InvalidArgumentException('Person not found');
        }
        $group = $this->groupRepo->findActive($groupId);
        if (!$group) {
            throw new InvalidArgumentException('Group not found');
        }
        $person->addGroup($group);
        $this->personPersister->persist($person);
    }

    public function removePersonFromGroup(string $personId, string $groupId): void
    {
        $person = $this->personRepo->findActive($personId);
        if (!$person) {
            throw new InvalidArgumentException('Person not found');
        }
        $group = $this->groupRepo->findActive($groupId);
        if (!$group) {
            throw new InvalidArgumentException('Group not found');
        }
        $person->removeGroup($group);
        $this->personPersister->persist($person);
    }


}
