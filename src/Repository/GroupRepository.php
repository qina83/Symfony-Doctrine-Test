<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Group;

interface GroupRepository
{
    public function findActiveGroups(): array;
    public function findActive($id): ?Group;
}
