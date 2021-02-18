<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Contact
{
    private Uuid $id;
    private string $name;
    private bool $deleted;
    private Collection $addresses;
    private Collection $groups;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->addresses = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->name = '';
        $this->deleted = false;
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
        return $this->id->toRfc4122();
    }

    public function setId(string $id): void
    {
        $this->id = Uuid::fromString($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }
    }

    public function removeAddress(Address $address): void
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }
    }

    public function addGroup(Group $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    public function removeGroup(Group $group): void
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
    }
}
