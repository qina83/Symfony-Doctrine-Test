<?php

declare(strict_types=1);

namespace App\Service;

interface GroupServiceInterface
{
    public function createGroup(string $name): string;
    public function deleteGroup(string $groupId): void;
    public function updateGroupName(string $groupId, string $name): void;
    public function listActiveGroups(): array;
}
