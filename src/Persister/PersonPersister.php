<?php

namespace App\Persister;

use App\Model\Person;

interface PersonPersister
{
    function persist(Person $person);
}