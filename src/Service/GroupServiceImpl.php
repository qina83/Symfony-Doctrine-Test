<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Group;
use App\Persister\GroupPersister;
use App\Repository\GroupRepository;

use InvalidArgumentException;

class GroupServiceImpl implements GroupService
{
    private GroupPersister $groupPersister;
    private GroupRepository $groupRepo;

    /**
     * PersonServiceImpl constructor.
     */
    public function __construct(GroupPersister $groupPersister, GroupRepository $groupRepo)
    {
        $this->groupPersister = $groupPersister;
        $this->groupRepo = $groupRepo;
    }

    public function createGroup(string $name): string
    {
        $group = new Group($name);

        $this->groupPersister->persist($group);

        return $group->getId();
    }

    public function deleteGroup(string $groupId): void
    {
        $group = $this->groupRepo->findActive($groupId);
        if (null != $group) {
            $group->markAsDeleted();
            $this->groupPersister->persist($group);
        }
    }

    public function updateGroupName(string $groupId, string $name): void
    {
        $group = $this->groupRepo->findActive($groupId);
        if (null != $group && !$group->isDeleted()) {
            $group->updateInfo($name);
            $this->groupPersister->persist($group);
        } else {
            throw new InvalidArgumentException("Group doesn't exists");
        }
    }

    public function listActiveGroups(): array
    {
        return $this->groupRepo->findActiveGroups();
    }
}
