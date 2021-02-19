<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Person;

interface PersonRepository
{
    public function countActiveContact();
    public function findActiveContact(int $page, int $pageSize);
    public function findActive(string $id): ?Person;
}
