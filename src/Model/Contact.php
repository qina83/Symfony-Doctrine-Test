<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

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
        $this->name = "";
        $this->deleted = false;
    }

    public function markAsDeleted(){
        $this->deleted = true;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->toRfc4122();
    }


    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = Uuid::fromString($id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addAddress(Address $address)
    {
        if (!$this->addresses->contains($address))
            $this->addresses->add($address);
    }

    public function removeAddress(Address $address){
        if ($this->addresses->contains($address))
            $this->addresses->removeElement($address);
    }

    public function addGroup(Group $group){
        if (!$this->groups->contains($group))
            $this->groups->add($group);
    }

    public function removeGroup(Group $group){
        if ($this->groups->contains($group))
            $this->groups->removeElement($group);
    }

}