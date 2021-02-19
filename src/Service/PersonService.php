<?php

declare(strict_types=1);

namespace App\Service;

interface PersonService
{
    public function createContact(string $name): string;
    public function updateContactName(string $contactId, string $name): void;
    public function deleteContact(string $contactId): void;

    public function listActiveContact(int $page, int $pageSize): array;
    public function find(string $contactId);
    public function calculatePaginationInfo(int $pageSize): array;

    public function addContactToGroup(string $contactId, string $groupId): void;
    public function removeContactFromGroup(string $contactId, string $groupId): void;
}
