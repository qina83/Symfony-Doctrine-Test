<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Contact;

interface ContactRepositoryInterface
{
    public function countActiveContact();
    public function findActiveContact(int $page, int $pageSize);
    public function findActive(string $id): ?Contact;
}
