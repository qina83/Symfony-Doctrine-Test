<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class GroupServiceImpl implements GroupService
{
    private EntityManagerInterface $em;
    private GroupRepository $groupRepo;

    /**
     * PersonServiceImpl constructor.
     */
    public function __construct(EntityManagerInterface $em, GroupRepository $groupRepo)
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

    public function deleteGroup(string $groupId): void
    {
        $group = $this->groupRepo->findActive($groupId);
        if (null != $group) {
            $group->markAsDeleted();
            $this->em->persist($group);
            $this->em->flush();
        }
    }

    public function updateGroupName(string $groupId, string $name): void
    {
        $group = $this->groupRepo->findActive($groupId);
        if (null != $group && !$group->isDeleted()) {
            $group->setName($name);
            $this->em->persist($group);
            $this->em->flush();
        } else {
            throw new InvalidArgumentException("Group doesn't exists");
        }
    }

    public function listActiveGroups(): array
    {
        return $this->groupRepo->findActiveGroups();
    }
}