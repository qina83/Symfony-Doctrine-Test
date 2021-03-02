<?php


namespace App\Persister;

use App\Model\Group;
use Doctrine\ORM\EntityManagerInterface;

class GroupPersisterDoctrine implements GroupPersister
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

    function persist(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }
}