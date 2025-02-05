<?php

declare(strict_types=1);

namespace Lottery\Presentation\Views;

use Lottery\Domain\Entities\WinningTicket;

class ResultsTable
{
    public function render(array $tickets, WinningTicket $winningTicket): string
    {
        $winningNumbers = $winningTicket->getNumbers();

        $html = $this->getStyles();
        $html .= '<body>';

        $html .= '<div class="lottery-results">';

        // Header
        $html .= '<div class="winning-ticket">';
        $html .= '<h2>Bilhete Premiado</h2>';
        $html .= '<div class="numbers">';
        foreach ($winningNumbers as $number) {
            $html .= sprintf('<span class="number winning">%02d</span>', $number);
        }

        $html .= '</div>';
        $html .= '</div>';

        // Table Tickets
        $html .= '<table class="tickets-table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Bilhete</th>';
        $html .= '<th>Números</th>';
        $html .= '<th>Acertos</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($tickets as $index => $ticket) {
            $matches = $ticket->matchCount($winningTicket);
            $numbers = $ticket->getNumbers();

            $html .= '<tr>';
            $html .= sprintf('<td>%d</td>', $index + 1);
            $html .= '<td class="numbers-cell">';

            foreach ($numbers as $number) {
                $isMatch = in_array($number, $winningNumbers);
                $class = $isMatch ? 'number match' : 'number';
                $html .= sprintf('<span class="%s">%02d</span>', $class, $number);
            }

            $html .= '</td>';
            $html .= sprintf(
                '<td class="matches">%d acerto%s</td>',
                $matches,
                $matches !== 1 ? 's' : ''
            );
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '</body>';
        $html .= '</html>';

        return $html;
    }

    private function getStyles(): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Loteria</title>
                <style>
                    .lottery-results {
                        font-family: Arial, sans-serif;
                        max-width: 800px;
                        margin: 20px auto;
                        padding: 20px;
                    }
                    
                    .winning-ticket {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    
                    .winning-ticket h2 {
                        color: #2c3e50;
                        margin-bottom: 15px;
                    }
                    
                    .numbers {
                        display: flex;
                        gap: 8px;
                        flex-wrap: wrap;
                        justify-content: center;
                    }
                    
                    .number {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background-color: #f0f0f0;
                        font-weight: bold;
                        color: #2c3e50;
                    }
                    
                    .number.winning {
                    background-color: #3498db;
                    color: white;
                }
                
                .number.match {
                    background-color: #2ecc71;
                    color: white;
                }
                
                .tickets-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                
                .tickets-table th,
                .tickets-table td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                
                .tickets-table th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                    color: #2c3e50;
                }
                
                .numbers-cell {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }
                
                .matches {
                    font-weight: bold;
                    color: #2ecc71;
                }
                
                @media (max-width: 600px) {
                    .number {
                        width: 32px;
                        height: 32px;
                        font-size: 14px;
                    }
                    
                    .tickets-table th,
                    .tickets-table td {
                        padding: 8px;
                        font-size: 14px;
                    }
                }
                </style>
            </head>
        HTML;
    }
}
