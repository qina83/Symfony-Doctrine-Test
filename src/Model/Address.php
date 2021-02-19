<?php

declare(strict_types=1);

namespace App\Model;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Address
{
    private UuidInterface $id;
    private string $city;
    private string $street;
    private string $civicNumber;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();;
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

    public function getId(): string
    {
        return $this->id->toString();
    }
}
