<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Person;

interface PersonRepository
{
    public function countActivePersons();
    public function findActivePersons(Page $page);
    public function findActive(string $id): ?Person;
}
