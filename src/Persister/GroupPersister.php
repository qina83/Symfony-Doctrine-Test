<?php


namespace App\Persister;

use App\Model\Group;

interface GroupPersister
{
    function persist(Group $group);
}