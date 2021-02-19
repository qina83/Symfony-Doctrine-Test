<?php

declare(strict_types=1);

namespace Service;

use App\Model\Person;
use App\Model\Group;
use App\Service\PersonServiceImpl;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class PersonServiceImplTest extends TestCase
{
    private PersonServiceImpl $sut;

    private Prophet $prophet;
    private ObjectProphecy $em;
    private ObjectProphecy $personRepo;
    private ObjectProphecy $groupRepo;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->em = $this->prophet->prophesize("Doctrine\ORM\EntityManagerInterface");
        $this->personRepo = $this->prophet->prophesize("App\Repository\PersonRepository");
        $this->groupRepo = $this->prophet->prophesize("App\Repository\GroupRepository");

        $this->sut = new PersonServiceImpl(
            $this->em->reveal(),
            $this->personRepo->reveal(),
            $this->groupRepo->reveal()
        );
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function test_CreateContact(): void
    {
        $this->em->persist(Argument::any())->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();
        $this->sut->createContact('name');

        self:
        self::assertTrue(true);
    }

    public function test_DeleteContact(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $contact = new Person();
        $contact->setId($contactId);
        $this->personRepo->findActive($contactId)->willReturn($contact);

        $this->sut->deleteContact($contactId);

        $this->em->persist($contact)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        self::assertTrue($contact->isDeleted());
    }

    public function test_DeleteContact_contactNotExists_mustWork(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->personRepo->findActive(Argument::any())->willReturn(null);

        $this->sut->deleteContact($contactId);

        self::assertTrue(true);
    }

    public function test_updateContactName(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $contact = new Person();
        $contact->setId($contactId);
        $this->personRepo->findActive('da480bf3-8adb-4626-ba03-68de2d1c8368')->willReturn($contact);

        $this->em->persist($contact)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $this->sut->updateContactName($contactId, 'newName');

        self::assertEquals('newName', $contact->getName());
    }

    public function test_updateContactName_contactNotExists_mustThrowException(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->personRepo->findActive(Argument::any())->willReturn(null);
        $this->expectException(InvalidArgumentException::class);

        $this->sut->updateContactName($contactId, 'newName');
    }

    public function test_addContactToGroup_mustWork(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $contact = new Person();
        $contact->setId($contactId);
        $group = new Group();
        $group->setId($groupId);

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($contactId)->willReturn($contact);

        $this->em->persist($contact)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $this->sut->addContactToGroup($contactId, $groupId);

        self::assertCount(1, $contact->getGroups());
        self::assertEquals($groupId, $contact->getGroups()->first()->getId());
    }

    public function test_addContactToGroup_contactNotExists_MustThrowException(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $group = new Group();
        $group->setId($groupId);

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($contactId)->willReturn(null);

        $this->em->persist(Argument::any())->shouldNotBeCalled();
        $this->em->flush()->shouldNotBeCalled();

        $this->expectException(InvalidArgumentException::class);

        $this->sut->addContactToGroup($contactId, $groupId);
    }

    public function test_addContactToGroup_groupNotExists_MustThrowException(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $contact = new Person();
        $contact->setId($contactId);

        $this->groupRepo->findActive($groupId)->willReturn(null);
        $this->personRepo->findActive($contactId)->willReturn($contact);

        $this->em->persist(Argument::any())->shouldNotBeCalled();
        $this->em->flush()->shouldNotBeCalled();

        $this->expectException(InvalidArgumentException::class);

        $this->sut->addContactToGroup($contactId, $groupId);
    }

    public function test_addContactToGroup_userIsAlreadyInGroup_MustWork(): void
    {
        $contactId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $contact = new Person();
        $contact->setId($contactId);
        $group = new Group();
        $group->setId($groupId);
        $contact->addGroup($group);

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($contactId)->willReturn($contact);

        $this->em->persist($contact)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $this->sut->addContactToGroup($contactId, $groupId);

        self::assertCount(1, $contact->getGroups());
        self::assertEquals($groupId, $contact->getGroups()->first()->getId());
    }

    public function dataProvider_calculatePaginationInfo()
    {
        return [
            //each item represents the related method parameter
            //the first time $a = 'valueOfA-0', $b='valueOfB-0',$expected='valueOfExpected-0'
            //and so on, for each array
            [1, 1, 1],
            [1, 2, 2],
            [2, 2, 1],
            [2, 4, 2],
            [2, 5, 3],
            [2, 6, 3],
        ];
    }

    /**
     * @dataProvider dataProvider_calculatePaginationInfo
     *
     * @param mixed $pageSize
     * @param mixed $totalItems
     * @param mixed $excpectedResults
     */
    public function test_calculatePaginationInfo($pageSize, $totalItems, $excpectedResults): void
    {
        $this->personRepo->countActiveContact()->willReturn($totalItems);
        $paginationInfo = $this->sut->calculatePaginationInfo($pageSize);

        self::assertEquals($excpectedResults, $paginationInfo['totalPages']);
    }
}
