<?php

declare(strict_types=1);

namespace Model;

use App\Model\Address;
use App\Model\Contact;
use App\Model\Group;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function test_address(): void
    {
        $contact = new Contact();
        $address = new Address();

        $contact->addAddress($address);

        $addresses = $contact->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address, $addresses->first(), 'address');
    }

    public function test_addMultiAddress(): void
    {
        $contact = new Contact();
        $address1 = new Address();
        $address2 = new Address();

        $contact->addAddress($address1);
        $contact->addAddress($address2);

        $addresses = $contact->getAddresses();

        self::assertCount(2, $addresses);
        self::assertEquals($address1, $addresses->first(), 'address1');
        self::assertEquals($address2, $addresses->next(), 'address2');
    }

    public function test_addDoubleAddress(): void
    {
        $contact = new Contact();
        $address1 = new Address();

        $contact->addAddress($address1);
        $contact->addAddress($address1);

        $addresses = $contact->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address1, $addresses->first(), 'address');
    }

    public function test_group(): void
    {
        $contact = new Contact();
        $group = new Group();

        $contact->addGroup($group);

        $groups = $contact->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group, $groups->first(), 'group');
    }

    public function test_addDoubleGroups(): void
    {
        $contact = new Contact();
        $group1 = new Group();

        $contact->addGroup($group1);
        $contact->addGroup($group1);

        $groups = $contact->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group1, $groups->first(), 'group1');
    }

    public function test_addMultiGroups(): void
    {
        $contact = new Contact();
        $group1 = new Group();
        $group2 = new Group();

        $contact->addGroup($group1);
        $contact->addGroup($group2);

        $groups = $contact->getGroups();

        self::assertCount(2, $groups);

        self::assertEquals($group1, $groups->first(), 'group1');
        self::assertEquals($group2, $groups->next(), 'group2');
    }

    public function test_removeAddress(): void
    {
        $contact = new Contact();
        $address1 = new Address();
        $address2 = new Address();
        $contact->addAddress($address1);
        $contact->addAddress($address2);

        $contact->removeAddress($address1);

        $addresses = $contact->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address2, $addresses->first(), 'address1');
    }

    public function test_removeGroup(): void
    {
        $contact = new Contact();
        $group1 = new Group();
        $group2 = new Group();
        $contact->addGroup($group1);
        $contact->addGroup($group2);

        $contact->removeGroup($group1);

        $groups = $contact->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group2, $groups->first());
    }
}
