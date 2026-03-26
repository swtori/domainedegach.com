<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ReservationModelTest extends TestCase
{
    protected function setUp(): void
    {
        test_resetDatabase();
    }

    public function testReservationOverlapDetection(): void
    {
        $this->assertTrue(reservationModel_foreignKeysExist(1, 1));

        $created = reservationModel_insert('2026-06-10', '2026-06-15', 1, 1);
        $this->assertTrue($created);

        $overlap = reservationModel_hasOverlap(1, '2026-06-12', '2026-06-13', null);
        $this->assertTrue($overlap);

        $noOverlap = reservationModel_hasOverlap(1, '2026-06-20', '2026-06-22', null);
        $this->assertFalse($noOverlap);
    }
}
