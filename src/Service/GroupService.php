<?php


namespace App\Service;

use App\Model\Group;
use App\Repository\GroupRepositoryDoctrine;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

class GroupService
{
    private EntityManagerInterface $em;
    private GroupRepositoryDoctrine $groupRepo;

    /**
     * ContactService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, GroupRepositoryDoctrine $groupRepo)
    {
        $this->em = $em;
        $this->groupRepo = $groupRepo;
    }

    public function createGroup(string $name): string
    {
        $group = new Group();
        $group->setName($name);

        $this->em->persist($group);
        $this->em->flush();

        return $group->getId();
    }

    public function deleteGroup(string $groupId)
    {
        $group = $this->groupRepo->findActive($groupId);
        if ($group != null) {
            $group->markAsDeleted();
            $this->em->persist($group);
            $this->em->flush();
        }
    }

    public function updateGroupName(string $groupId, string $name){
        $group = $this->groupRepo->findActive($groupId);
        if ($group != null && !$group->isDeleted()) {
            $group->setName($name);
            $this->em->persist($group);
            $this->em->flush();
        }
        else throw new InvalidArgumentException("Group doesn't exists");
    }

    public function listActiveGroups(): array
    {
        return $this->groupRepo->findActiveGroups();
    }

}