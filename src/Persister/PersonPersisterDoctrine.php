<?php

namespace App\Persister;

use App\Model\Person;
use App\Persister\PersonPersister;
use Doctrine\ORM\EntityManagerInterface;

class PersonPersisterDoctrine implements PersonPersister
{
    private EntityManagerInterface $em;

    /**
     * PersonPersisterDoctrine constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function persist(Person $person)
    {
        $this->em->persist($person);
        $this->em->flush();
    }
}