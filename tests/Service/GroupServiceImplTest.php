<?php

declare(strict_types=1);

namespace Service;

use App\Model\Group;
use App\Service\GroupServiceImpl;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class GroupServiceImplTest extends TestCase
{
    private GroupServiceImpl $sut;
    private Prophet $prophet;
    private ObjectProphecy $groupPersister;
    private ObjectProphecy $repo;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->groupPersister = $this->prophet->prophesize("App\Persister\GroupPersister");
        $this->repo = $this->prophet->prophesize("App\Repository\GroupRepository");

        $this->sut = new GroupServiceImpl(
            $this->groupPersister->reveal(),
            $this->repo->reveal()
        );
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function test_DeleteGroup(): void
    {
        $groupId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $group = Group::createByIdAndName($groupId, "groupName");;
        $this->repo->findActive($groupId)->willReturn($group);

        $this->sut->deleteGroup($groupId);

        $this->groupPersister->persist($group)->shouldBeCalled();


        self::assertTrue($group->isDeleted());
    }

    public function test_DeleteGroup_groupNotExists_mustWork(): void
    {
        $groupId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->repo->findActive(Argument::any())->willReturn(null);

        $this->sut->deleteGroup($groupId);

        self::assertTrue(true);
    }

    public function test_CreateGroup(): void
    {
        $this->groupPersister->persist(Argument::any())->shouldBeCalled();

        $this->sut->createGroup('name');

        self:self::assertTrue(true);
    }

    public function test_updateGroupName(): void
    {
        $groupId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $group = Group::createByIdAndName($groupId, "groupName");
        $this->repo->findActive('da480bf3-8adb-4626-ba03-68de2d1c8368')->willReturn($group);

        $this->sut->updateGroupName($groupId, 'newName');

        $this->groupPersister->persist($group)->shouldBeCalled();

        self::assertEquals('newName', $group->getName());
    }

    public function test_updateGroupName_groupNotExists_mustThrowException(): void
    {
        $groupId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $this->repo->findActive(Argument::any())->willReturn(null);
        $this->expectException(InvalidArgumentException::class);

        $this->sut->updateGroupName($groupId, 'newName');
    }
}
