<?php

declare(strict_types=1);

namespace Lottery\Domain\Entities;

use Lottery\Domain\Exceptions\ValidationException;

class WinningTicket extends Ticket
{
    /**
     * @throws ValidationException
     */
    public function __construct()
    {
        parent::__construct(self::generateWinningNumbers());
    }

    private static function generateWinningNumbers(): array
    {
        $numbers = [];
        while (count($numbers) < 6) {
            $number = random_int(1, 60);
            if (!in_array($number, $numbers)) {
                $numbers[] = $number;
            }
        }
        return $numbers;
    }
}
