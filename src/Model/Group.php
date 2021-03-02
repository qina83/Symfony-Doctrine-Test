<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Group
{
    private UuidInterface $id;
    private string $name;
    private bool $deleted;
    private Collection $persons;

    /**
     * Group constructor.
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::uuid4();;
        $this->deleted = false;
        $this->name = $name;
        $this->persons = new ArrayCollection();
    }

    public static function createByIdAndName(string $id, string $name): Group
    {
        $group = new Group($name);
        $group->id = uuid::fromString($id);
        return $group;
    }

    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function updateInfo(string $name){
        $this->name = $name;
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
