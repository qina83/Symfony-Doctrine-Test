<?php

declare(strict_types=1);

namespace App\Service;

interface PersonService
{
    public function createPerson(string $name): string;
    public function updatePersonPersonalInfo(string $personId, string $name): void;
    public function deletePerson(string $personId): void;

    public function listActivePersons(int $page, int $pageSize): array;
    public function find(string $personId);
    public function calculatePaginationInfo(int $pageSize): array;

    public function addPersonToGroup(string $personId, string $groupId): void;
    public function removePersonFromGroup(string $personId, string $groupId): void;
}
