<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Uid\Uuid;

class Address
{
    private Uuid $id;
    private string $city;
    private string $street;
    private string $civicNumber;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCivicNumber(): string
    {
        return $this->civicNumber;
    }

    /**
     * @param string $civicNumber
     */
    public function setCivicNumber(string $civicNumber): void
    {
        $this->civicNumber = $civicNumber;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }
}
