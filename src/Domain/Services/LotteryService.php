<?php

declare(strict_types=1);

namespace Lottery\Domain\Services;

use Lottery\Domain\Entities\Ticket;
use Lottery\Domain\Entities\WinningTicket;
use Lottery\Domain\Exceptions\ValidationException;

class LotteryService
{
    /**
     * @throws ValidationException
     */
    public function generateTickets(int $quantity, int $numbersPerTicket): array
    {
        $this->validateTicketGeneration($quantity, $numbersPerTicket);

        $tickets = [];
        for ($i = 0; $i < $quantity; $i++) {
            do {
                $ticket = $this->generateSingleTicket($numbersPerTicket);
            } while (in_array($ticket, $tickets));

            $tickets[] = $ticket;
        }

        return $tickets;
    }

    /**
     * @throws ValidationException
     */
    private function validateTicketGeneration(int $quantity, int $numbersPerTicket): void
    {
        if ($quantity <= 0 || $quantity > 50) {
            throw new ValidationException('A quantidade de bilhetes deve estar entre 1 e 50');
        }

        if ($numbersPerTicket < 6 || $numbersPerTicket > 10) {
            throw new ValidationException('A quantidade de números por bilhete deve estar entre 6 e 10');
        }
    }

    /**
     * @throws ValidationException
     */
    private function generateSingleTicket(int $numbersPerTicket): Ticket
    {
        $numbers = [];
        while (count($numbers) < $numbersPerTicket) {
            $number = random_int(1, 60);
            if (!in_array($number, $numbers)) {
                $numbers[] = $number;
            }
        }

        return new Ticket($numbers);
    }

    public function generateWinningTicket(): WinningTicket
    {
        return new WinningTicket();
    }
}
