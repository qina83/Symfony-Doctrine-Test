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
    private ObjectProphecy $personPersister;
    private ObjectProphecy $personRepo;
    private ObjectProphecy $groupRepo;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->personPersister = $this->prophet->prophesize("App\Persister\PersonPersister");
        $this->personRepo = $this->prophet->prophesize("App\Repository\PersonRepository");
        $this->groupRepo = $this->prophet->prophesize("App\Repository\GroupRepository");

        $this->sut = new PersonServiceImpl(
            $this->personPersister->reveal(),
            $this->personRepo->reveal(),
            $this->groupRepo->reveal()
        );
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function test_CreatePerson(): void
    {
        $this->personPersister->persist(Argument::any())->shouldBeCalled();
        $this->sut->createPerson('name');

        self::assertTrue(true);
    }

    public function test_DeletePerson(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $person = Person::createByIdAndName($personId, "name");
        $this->personRepo->findActive($personId)->willReturn($person);

        $this->sut->deletePerson($personId);

        $this->personPersister->persist($person)->shouldBeCalled();

        self::assertTrue($person->isDeleted());
    }

    public function test_DeletePerson_personNotExists_mustWork(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->personRepo->findActive(Argument::any())->willReturn(null);

        $this->sut->deletePerson($personId);

        self::assertTrue(true);
    }

    public function test_updatePersonName(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $person = Person::createByIdAndName($personId, "name");
        $this->personRepo->findActive('da480bf3-8adb-4626-ba03-68de2d1c8368')->willReturn($person);

        $this->personPersister->persist($person)->shouldBeCalled();

        $this->sut->updatePersonPersonalInfo($personId, 'newName');

        self::assertEquals('newName', $person->getName());
    }

    public function test_updatePersonName_personNotExists_mustThrowException(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->personRepo->findActive(Argument::any())->willReturn(null);
        $this->expectException(InvalidArgumentException::class);

        $this->sut->updatePersonPersonalInfo($personId, 'newName');
    }

    public function test_addPersonToGroup_mustWork(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $person = Person::createByIdAndName($personId, "name");
        $group = Group::createByIdAndName($groupId, "groupName");

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($personId)->willReturn($person);

        $this->personPersister->persist($person)->shouldBeCalled();

        $this->sut->addPersonToGroup($personId, $groupId);

        self::assertCount(1, $person->getGroups());
        self::assertEquals($groupId, $person->getGroups()->first()->getId());
    }

    public function test_addPersonToGroup_personNotExists_MustThrowException(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $group = Group::createByIdAndName($groupId, "groupName");

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($personId)->willReturn(null);

        $this->personPersister->persist(Argument::any())->shouldNotBeCalled();

        $this->expectException(InvalidArgumentException::class);

        $this->sut->addPersonToGroup($personId, $groupId);
    }

    public function test_addPersonToGroup_groupNotExists_MustThrowException(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $person = Person::createByIdAndName($personId, "name");

        $this->groupRepo->findActive($groupId)->willReturn(null);
        $this->personRepo->findActive($personId)->willReturn($person);

        $this->personPersister->persist(Argument::any())->shouldNotBeCalled();

        $this->expectException(InvalidArgumentException::class);

        $this->sut->addPersonToGroup($personId, $groupId);
    }

    public function test_addPersonToGroup_userIsAlreadyInGroup_MustWork(): void
    {
        $personId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $groupId = 'b0c09227-5cc1-4869-bbdb-008cae9c3e3d';
        $person = Person::createByIdAndName($personId, "name");
        $group = Group::createByIdAndName($groupId, "groupName");
        $person->addGroup($group);

        $this->groupRepo->findActive($groupId)->willReturn($group);
        $this->personRepo->findActive($personId)->willReturn($person);

        $this->personPersister->persist($person)->shouldBeCalled();

        $this->sut->addPersonToGroup($personId, $groupId);

        self::assertCount(1, $person->getGroups());
        self::assertEquals($groupId, $person->getGroups()->first()->getId());
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
        $this->personRepo->countActivePersons()->willReturn($totalItems);
        $paginationInfo = $this->sut->calculatePaginationInfo($pageSize);

        self::assertEquals($excpectedResults, $paginationInfo['totalPages']);
    }
}
