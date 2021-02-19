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
    private ObjectProphecy $em;
    private ObjectProphecy $repo;

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->em = $this->prophet->prophesize("Doctrine\ORM\EntityManagerInterface");
        $this->repo = $this->prophet->prophesize("App\Repository\GroupRepository");

        $this->sut = new GroupServiceImpl(
            $this->em->reveal(),
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
        $group = new Group();
        $group->setId($groupId);
        $this->repo->findActive($groupId)->willReturn($group);

        $this->sut->deleteGroup($groupId);

        $this->em->persist($group)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

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
        $this->em->persist(Argument::any())->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();
        $this->sut->createGroup('name');

        self:self::assertTrue(true);
    }

    public function test_updateGroupName(): void
    {
        $groupId = 'da480bf3-8adb-4626-ba03-68de2d1c8368';
        $group = new Group();
        $group->setId($groupId);
        $this->repo->findActive('da480bf3-8adb-4626-ba03-68de2d1c8368')->willReturn($group);

        $this->sut->updateGroupName($groupId, 'newName');

        $this->em->persist($group)->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

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
