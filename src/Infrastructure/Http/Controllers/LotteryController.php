<?php

declare(strict_types=1);

namespace Lottery\Infrastructure\Http\Controllers;

use Lottery\Domain\Exceptions\ValidationException;
use Lottery\Domain\Services\LotteryService;
use Lottery\Infrastructure\Http\Responses\Response;

class LotteryController
{
    public function __construct(
        private readonly LotteryService $lotteryService = new LotteryService()
    ) {
    }

    public function generateWinningTicket(): void
    {
        try {
            $winningTicket = $this->lotteryService->generateWinningTicket();

            Response::success([
                'ticket' => $winningTicket->getNumbers(),
                'formatted' => $winningTicket->toString()
            ])->send();
        } catch (\Exception $e) {
            Response::error(
                'Erro ao gerar bilhete premiado: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            )
                ->send();
        }
    }

    public function generateTickets(): void
    {
        try {
            $requestData = $this->getValidatedRequestData();

            $winningTicket = $this->lotteryService->generateWinningTicket();

            $tickets = $this->lotteryService->generateTickets(
                $requestData['quantity'],
                $requestData['numbers_per_ticket']
            );

            // TODO - Implementar a geração da tabela de resultados
            $resultsHtml = '';

            $response = [
                'winning_ticket' => [
                    'numbers' => $winningTicket->getNumbers(),
                    'formatted' => $winningTicket->toString()
                ],
                'tickets' => array_map(fn($ticket) => [
                    'numbers' => $ticket->getNumbers(),
                    'formatted' => $ticket->toString(),
                    'matches' => $ticket->matchCount($winningTicket)
                ], $tickets),
                'results_table' => $resultsHtml
            ];

            Response::success($response)->send();
        } catch (ValidationException $e) {
            Response::error($e->getMessage(), Response::HTTP_BAD_REQUEST)->send();
        } catch (\Exception $e) {
            Response::error(
                'Erro ao gerar bilhetes: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            )
                ->send();
        }
    }

    private function getValidatedRequestData(): array
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (!$data) {
            throw new ValidationException('Dados da requisição inválidos');
        }

        if (!isset($data['quantity']) || !is_int($data['quantity'])) {
            throw new ValidationException('Quantidade de bilhetes não especificada ou inválida');
        }

        if (!isset($data['numbers_per_ticket']) || !is_int($data['numbers_per_ticket'])) {
            throw new ValidationException('Quantidade de números por bilhete não especificada ou inválida');
        }

        return $data;
    }
}
