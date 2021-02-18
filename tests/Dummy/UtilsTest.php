<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UtilsTest extends TestCase
{
    public function test_uuid_always_diff(): void
    {
        $uid1 = UUid::v4();
        $uid2 = UUid::v4();
        self::assertFalse($uid1->equals($uid2));
    }

    public function test_uuid_equal(): void
    {
        $uuid = 'd9e7a184-5d5b-11ea-a62a-3499710062d0';
        $uid1 = UUid::fromString($uuid);
        $uid2 = UUid::fromString($uuid);
        self::assertTrue($uid1->equals($uid2));
    }
}
