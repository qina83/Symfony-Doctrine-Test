<?php


use App\Model\Contact;

class ContactMapper
{
    public static function ContactToDto(Contact $contact): array
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
            'groups'=>$groupsDTO
        ];
    }
}