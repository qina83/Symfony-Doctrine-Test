<?php

declare(strict_types=1);

use App\Model\Group;

class GroupMapper
{
    public static function GroupToDto(Group $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'isDeleted' => $group->isDeleted(),
        ];
    }
}
