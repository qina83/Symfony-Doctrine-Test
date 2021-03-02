<?php

declare(strict_types=1);

use App\Model\Person;

class PersonMapper
{
    public static function PersonToDto(Person $person): array
    {
        $groups = $person->getGroups();
        $groupsDTO = [];
        foreach ($groups as $group) {
            $groupsDTO[] = GroupMapper::GroupToDto($group);
        }

        return [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'isDeleted' => $person->isDeleted(),
            'groups' => $groupsDTO,
        ];
    }
}
