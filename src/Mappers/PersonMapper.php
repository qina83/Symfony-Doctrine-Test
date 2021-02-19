<?php

declare(strict_types=1);

use App\Model\Person;

class PersonMapper
{
    public static function ContactToDto(Person $contact): array
    {
        $groups = $contact->getGroups();
        $groupsDTO = [];
        foreach ($groups as $group) {
            $groupsDTO[] = GroupMapper::GroupToDto($group);
        }

        return [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'isDeleted' => $contact->isDeleted(),
            'groups' => $groupsDTO,
        ];
    }
}
