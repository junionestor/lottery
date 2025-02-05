<?php

declare(strict_types=1);

use Lottery\Infrastructure\Http\Controllers\LotteryController;

require_once __DIR__ . '/../vendor/autoload.php';



// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Basic Routing
$controller = new LotteryController();

$path = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ("$method $path") {
        case 'POST /draw':
            $controller->generateTickets();
            break;
        case 'POST /draw/table':
            $controller->generateTicketsTable();
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Rota não encontrada'
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro interno do servidor: ' . $e->getMessage()
    ]);
}
