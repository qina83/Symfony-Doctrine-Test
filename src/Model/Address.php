<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Uid\Uuid;

class Address
{
    private Uuid $id;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string uuid as binary
     */
    public function getIdAsBinary(): string
    {
        return $this->id->toBinary();
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }
}
