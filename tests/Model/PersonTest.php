<?php

declare(strict_types=1);

namespace Model;

use App\Model\Address;
use App\Model\Person;
use App\Model\Group;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function test_address(): void
    {
        $person = new Person("name");
        $address = new Address();

        $person->addAddress($address);

        $addresses = $person->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address, $addresses->first(), 'address');
    }

    public function test_addMultiAddress(): void
    {
        $person = new Person("name");
        $address1 = new Address();
        $address2 = new Address();

        $person->addAddress($address1);
        $person->addAddress($address2);

        $addresses = $person->getAddresses();

        self::assertCount(2, $addresses);
        self::assertEquals($address1, $addresses->first(), 'address1');
        self::assertEquals($address2, $addresses->next(), 'address2');
    }

    public function test_addDoubleAddress(): void
    {
        $person = new Person("name");
        $address1 = new Address();

        $person->addAddress($address1);
        $person->addAddress($address1);

        $addresses = $person->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address1, $addresses->first(), 'address');
    }

    public function test_group(): void
    {
        $person = new Person("name");
        $group = new Group("groupName");

        $person->addGroup($group);

        $groups = $person->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group, $groups->first(), 'group');
    }

    public function test_addDoubleGroups(): void
    {
        $person = new Person("name");
        $group1 =new Group("groupName");

        $person->addGroup($group1);
        $person->addGroup($group1);

        $groups = $person->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group1, $groups->first(), 'group1');
    }

    public function test_addMultiGroups(): void
    {
        $person = new Person("name");
        $group1 = new Group("groupName");
        $group2 = new Group("groupName");

        $person->addGroup($group1);
        $person->addGroup($group2);

        $groups = $person->getGroups();

        self::assertCount(2, $groups);

        self::assertEquals($group1, $groups->first(), 'group1');
        self::assertEquals($group2, $groups->next(), 'group2');
    }

    public function test_removeAddress(): void
    {
        $person = new Person("name");
        $address1 = new Address();
        $address2 = new Address();
        $person->addAddress($address1);
        $person->addAddress($address2);

        $person->removeAddress($address1);

        $addresses = $person->getAddresses();

        self::assertCount(1, $addresses);
        self::assertEquals($address2, $addresses->first(), 'address1');
    }

    public function test_removeGroup(): void
    {
        $person = new Person("name");
        $group1 = new Group("groupName");
        $group2 = new Group("groupName");
        $person->addGroup($group1);
        $person->addGroup($group2);

        $person->removeGroup($group1);

        $groups = $person->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($group2, $groups->first());
    }
}
