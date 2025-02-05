<?php

namespace Tests\Unit\Domain\Services;

use Lottery\Domain\Exceptions\ValidationException;
use Lottery\Domain\Services\LotteryService;
use PHPUnit\Framework\TestCase;

class LotteryServiceTest extends TestCase
{
    private LotteryService $service;

    protected function setUp(): void
    {
        $this->service = new LotteryService();
    }

    public function testGenerateWinningTicket(): void
    {
        $ticket = $this->service->generateWinningTicket();
        $numbers = $ticket->getNumbers();

        $this->assertCount(6, $numbers);
        $this->assertEquals($numbers, array_unique($numbers));
        $this->assertEquals($numbers, array_values(array_filter($numbers, fn($n) => $n >= 1 && $n <= 60)));
    }

    public function testGenerateTickets(): void
    {
        $tickets = $this->service->generateTickets(5, 6);

        $this->assertCount(5, $tickets);
        foreach ($tickets as $ticket) {
            $numbers = $ticket->getNumbers();
            $this->assertCount(6, $numbers);
            $this->assertEquals($numbers, array_unique($numbers));
        }
    }

    public function testThrowsExceptionForInvalidQuantity(): void
    {
        $this->expectException(ValidationException::class);
        $this->service->generateTickets(51, 6);
    }

    public function testThrowsExceptionForInvalidNumbersPerTicket(): void
    {
        $this->expectException(ValidationException::class);
        $this->service->generateTickets(5, 11);
    }
}
