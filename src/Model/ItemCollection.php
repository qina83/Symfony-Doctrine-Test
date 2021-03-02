<?php


namespace App\Model;


use Doctrine\Common\Collections\ArrayCollection;

class ItemCollection extends ArrayCollection
{
    public function addItem($item): void
    {
        if (!$this->contains($item)) {
            $this->add($item);
        }
    }

    public function removeItem($item): void
    {
        if ($this->contains($item)) {
            $this->removeElement($item);
        }
    }
}