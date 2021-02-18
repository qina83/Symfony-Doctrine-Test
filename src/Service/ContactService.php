<?php

namespace App\Service;

use App\Model\Contact;
use App\Model\Group;
use App\Repository\ContactRepositoryDoctrine;
use App\Repository\GroupRepositoryDoctrine;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Uid\Uuid;

class ContactService
{
    private EntityManagerInterface $em;
    private ContactRepositoryDoctrine $contactRepo;
    private GroupRepositoryDoctrine $groupRepo;

    /**
     * ContactService constructor.
     * @param EntityManagerInterface $em
     * @param ContactRepositoryDoctrine $contactRepo
     * @param GroupRepositoryDoctrine $groupRepo
     */
    public function __construct(EntityManagerInterface $em, ContactRepositoryDoctrine $contactRepo, GroupRepositoryDoctrine $groupRepo)
    {
        $this->em = $em;
        $this->contactRepo = $contactRepo;
        $this->groupRepo = $groupRepo;
    }


    public function createContact(string $name): string
    {
        $contact = new Contact();
        $contact->setName($name);

        $this->em->persist($contact);
        $this->em->flush();

        return $contact->getId();
    }

    public function updateContactName(string $contactId, string $name)
    {
        $contact = $this->contactRepo->findActive($contactId);
        if ($contact != null && !$contact->isDeleted()) {
            $contact->setName($name);
            $this->em->persist($contact);
            $this->em->flush();
        } else throw new InvalidArgumentException("Contact doesn't exists");
    }

    public function deleteContact(string $contactId)
    {
        $contact = $this->contactRepo->findActive($contactId);
        if ($contact != null) {
            $contact->markAsDeleted();
            $this->em->persist($contact);
            $this->em->flush();
        }
    }

    #[ArrayShape(["totalItems" => "int", "totalPages" => "false|float"])]
    public function calculatePaginationInfo(?int $pageSize): array{

        $totalItems = $this->contactRepo->countActiveContact();
        $totalPages = ceil($totalItems / $pageSize);
        return [
            "totalItems"=>$totalItems,
            "totalPages"=>$totalPages
        ];
    }

    public function listActiveContact(?int $page, ?int $pageSize): array
    {
        return $this->contactRepo->findActiveContact($page, $pageSize);
    }

    public function find(string $contactId)
    {
        return $this->contactRepo->findActive($contactId);
    }

    public function addContactToGroup(string $contactId, string $groupId)
    {
        $contact = $this->contactRepo->findActive($contactId);
        if (!$contact) throw new InvalidArgumentException("Contact not found");

        $group = $this->groupRepo->findActive($groupId);
        if (!$group) throw new InvalidArgumentException("Group not found");

        $contact->addGroup($group);
        $this->em->persist($contact);
        $this->em->flush();
    }

    public function removeContactFromGroup(string $contactId, string $groupId)
    {
        $contact = $this->contactRepo->findActive($contactId);
        if (!$contact) throw new InvalidArgumentException("Contact not found");

        $group = $this->groupRepo->findActive($groupId);
        if (!$group) throw new InvalidArgumentException("Group not found");

        $contact->removeGroup($group);
        $this->em->persist($contact);
        $this->em->flush();
    }
}