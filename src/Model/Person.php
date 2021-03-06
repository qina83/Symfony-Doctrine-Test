<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

class Person
{
    private UuidInterface $id;
    private string $name;
    private bool $deleted;
    private Collection $addresses;
    private Collection $groups;

    private function __construct(string $name)
    {
        Assert::notEmpty($name);
        $this->id = Uuid::uuid4();
        $this->addresses = new ItemCollection();
        $this->groups = new ItemCollection();
        $this->name = $name;
        $this->deleted = false;
    }

    public static function fromName(string $name): Person
    {
        return new Person($name);
    }

    public static function fromIdAndName(string $id, string $name): Person
    {
        $person = self::fromName($name);
        $person->id = uuid::fromString($id);
        return $person;
    }

    public function updatePersonalInfo(string $name){
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

    public function getId(): string
    {
        return $this->id->toString();
    }

      public function getName(): string
    {
        return $this->name;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addAddress(Address $address): void
    {
        $this->addresses->addItem($address);
    }

    public function removeAddress(Address $address): void
    {
        $this->addresses->removeItem($address);
    }

    public function addGroup(Group $group): void
    {
        $this->groups->addItem($group);
    }

    public function removeGroup(Group $group): void
    {
        $this->groups->removeItem($group);
    }
}
