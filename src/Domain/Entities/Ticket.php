<?php

declare(strict_types=1);

namespace Lottery\Domain\Entities;

use Lottery\Domain\Exceptions\ValidationException;

class Ticket
{
    private array $numbers;

    /**
     * @throws ValidationException
     */
    public function __construct(array $numbers)
    {
        $this->validate($numbers);
        sort($numbers);
        $this->numbers = $numbers;
    }

    /**
     * @throws ValidationException
     */
    private function validate(array $numbers): void
    {
        if (count($numbers) !== count(array_unique($numbers))) {
            throw new ValidationException('O bilhete não pode conter números duplicados');
        }

        if (count($numbers) < 6 || count($numbers) > 10) {
            throw new ValidationException('O bilhete deve conter entre 6 e 10 números');
        }

        foreach ($numbers as $number) {
            if (!is_int($number) || $number < 1 || $number > 60) {
                throw new ValidationException('Os números devem ser inteiros entre 1 e 60');
            }
        }
    }

    public function getNumbers(): array
    {
        return $this->numbers;
    }

    public function matchCount(Ticket $other): int
    {
        return count(array_intersect($this->numbers, $other->getNumbers()));
    }

    public function toString(): string
    {
        return implode(
            ', ',
            array_map(
                fn($number) =>
                str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                $this->numbers
            )
        );
    }
}
