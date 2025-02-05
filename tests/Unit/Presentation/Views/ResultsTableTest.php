<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Views;

use Lottery\Domain\Entities\Ticket;
use Lottery\Domain\Entities\WinningTicket;
use Lottery\Presentation\Views\ResultsTable;
use PHPUnit\Framework\TestCase;

class ResultsTableTest extends TestCase
{
    private ResultsTable $resultsTable;

    protected function setUp(): void
    {
        $this->resultsTable = new ResultsTable();
    }

    private function createTicket(array $numbers): Ticket
    {
        return new Ticket($numbers);
    }

    private function createMockWinningTicket(array $numbers): WinningTicket
    {

        $winningTicket = $this->createMock(WinningTicket::class);
        $winningTicket->method('getNumbers')
            ->willReturn($numbers);
        return $winningTicket;
    }

    public function testRendersWinningTicketSection(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $tickets = [$this->createTicket([7, 8, 9, 10, 11, 12])];

        $html = $this->resultsTable->render($tickets, $winningTicket);


        $this->assertStringContainsString('<h2>Bilhete Premiado</h2>', $html);


        foreach ($winningTicket->getNumbers() as $number) {
            $this->assertStringContainsString(
                sprintf('<span class="number winning">%02d</span>', $number),
                $html
            );
        }
    }

    public function testRendersTicketsTable(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $tickets = [
            $this->createTicket([1, 7, 8, 9, 10, 11]),
            $this->createTicket([1, 2, 8, 9, 10, 11])
        ];

        $html = $this->resultsTable->render($tickets, $winningTicket);


        $this->assertStringContainsString('<table class="tickets-table">', $html);
        $this->assertStringContainsString('<thead>', $html);
        $this->assertStringContainsString('<tbody>', $html);


        $this->assertStringContainsString('<th>Bilhete</th>', $html);
        $this->assertStringContainsString('<th>Números</th>', $html);
        $this->assertStringContainsString('<th>Acertos</th>', $html);
    }

    public function testHighlightsMatchingNumbers(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $tickets = [$this->createTicket([1, 2, 8, 9, 10, 11])];

        $html = $this->resultsTable->render($tickets, $winningTicket);


        $this->assertStringContainsString('<span class="number match">01</span>', $html);
        $this->assertStringContainsString('<span class="number match">02</span>', $html);


        $this->assertStringContainsString('<span class="number">08</span>', $html);
    }

    public function testDisplaysCorrectMatchCount(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $tickets = [
            $this->createTicket([1, 7, 8, 9, 10, 11]),
            $this->createTicket([1, 2, 8, 9, 10, 11]),
            $this->createTicket([1, 2, 3, 9, 10, 11])
        ];

        $html = $this->resultsTable->render($tickets, $winningTicket);


        $this->assertStringContainsString('1 acerto</td>', $html);
        $this->assertStringContainsString('2 acertos</td>', $html);
        $this->assertStringContainsString('3 acertos</td>', $html);
    }

    public function testHandlesEmptyTicketsList(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $html = $this->resultsTable->render([], $winningTicket);


        $this->assertStringContainsString('<table class="tickets-table">', $html);
        $this->assertStringContainsString('<thead>', $html);
        $this->assertStringContainsString('<tbody>', $html);


        foreach ($winningTicket->getNumbers() as $number) {
            $this->assertStringContainsString(
                sprintf('<span class="number winning">%02d</span>', $number),
                $html
            );
        }
    }

    public function testIncludesRequiredStyles(): void
    {
        $winningTicket = $this->createMockWinningTicket([1, 2, 3, 4, 5, 6]);
        $html = $this->resultsTable->render([], $winningTicket);


        $this->assertStringContainsString('<style>', $html);
        $this->assertStringContainsString('.lottery-results', $html);
        $this->assertStringContainsString('.winning-ticket', $html);
        $this->assertStringContainsString('.number', $html);
        $this->assertStringContainsString('.tickets-table', $html);
        $this->assertStringContainsString('@media', $html);
    }
}
