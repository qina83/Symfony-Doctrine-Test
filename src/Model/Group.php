<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Group
{
    private Uuid $id;
    private string $name;
    private bool $deleted;
    private Collection $persons;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->deleted = false;
        $this->name = '';
        $this->persons = new ArrayCollection();
    }

    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id->toRfc4122();
    }

    public function setId(string $id): void
    {
        $this->id = Uuid::fromString($id);
    }

    public function markAsDeleted(): void
    {
        $this->deleted = true;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }
}
