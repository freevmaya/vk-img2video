<?php
// Файл: api/vk-handler.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://vk.com');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-VK-App-ID');

// Разрешаем предварительные запросы OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Конфигурация
define('VK_APP_ID', '54399156');

// Проверяем App ID из заголовков
$appId = $_SERVER['HTTP_X_VK_APP_ID'] ?? '';
if ($appId !== VK_APP_ID) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid App ID',
        'received' => $appId,
        'expected' => VK_APP_ID
    ]);
    exit;
}

// Получаем входные данные
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid JSON data'
    ]);
    exit;
}

// Определяем действие
$action = $input['action'] ?? '';

switch ($action) {
    case 'saveData':
        handleSaveData($input);
        break;
        
    case 'getUserData':
        handleGetUserData($input);
        break;
        
    case 'testConnection':
        handleTestConnection($input);
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Unknown action',
            'received_action' => $action
        ]);
}

/**
 * Обработчик сохранения данных
 */
function handleSaveData($data) {
    // Валидация данных
    if (!isset($data['user']) || !isset($data['user']['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid user data: user ID is required'
        ]);
        return;
    }
    
    $userId = (int) $data['user']['id'];
    $userName = $data['user']['first_name'] . ' ' . $data['user']['last_name'];
    $timestamp = $data['timestamp'] ?? date('c');
    
    // Подготавливаем данные для сохранения
    $saveData = [
        'user_id' => $userId,
        'user_name' => $userName,
        'action' => 'saveData',
        'timestamp' => $timestamp,
        'server_time' => date('c'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ];
    
    // Создаем директорию для данных, если её нет
    $dataDir = dirname(__FILE__) . '/../data';
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Сохраняем в файл (в реальном приложении используйте БД)
    $filename = sprintf('user_%d_%s.json', 
        $userId, 
        date('Y-m-d')
    );
    
    $filePath = $dataDir . '/' . $filename;
    
    // Читаем существующие данные, если файл есть
    $existingData = [];
    if (file_exists($filePath)) {
        $existingData = json_decode(file_get_contents($filePath), true) ?? [];
    }
    
    // Добавляем новую запись
    $existingData[] = $saveData;
    
    // Сохраняем обратно в файл
    $result = file_put_contents(
        $filePath, 
        json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
    
    if ($result === false) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to save data to file'
        ]);
        return;
    }
    
    // Успешный ответ
    echo json_encode([
        'success' => true,
        'message' => "Данные пользователя {$userName} успешно сохранены!",
        'saved_at' => date('c'),
        'user_id' => $userId,
        'file' => $filename,
        'records_count' => count($existingData)
    ]);
}

/**
 * Обработчик получения данных пользователя
 */
function handleGetUserData($data) {
    $userId = $data['user_id'] ?? null;
    
    if (!$userId) {
        echo json_encode([
            'success' => true,
            'data' => [
                'statistics' => [
                    'total_users' => 0,
                    'today_visits' => rand(1, 50),
                    'app_launches' => rand(100, 1000)
                ],
                'features' => [
                    'image_to_video' => true,
                    'video_effects' => false,
                    'social_sharing' => true
                ],
                'version' => '1.0.0'
            ]
        ]);
        return;
    }
    
    // Здесь можно получить данные пользователя из БД
    echo json_encode([
        'success' => true,
        'data' => [
            'user_id' => $userId,
            'statistics' => [
                'total_uses' => rand(1, 100),
                'last_use' => date('c'),
                'favorite_effect' => 'slide_show'
            ],
            'achievements' => ['Новичок', 'Первое видео', 'Активный пользователь'],
            'settings' => [
                'auto_save' => true,
                'notifications' => true,
                'quality' => 'high'
            ]
        ]
    ]);
}

/**
 * Тестовое соединение
 */
function handleTestConnection($data) {
    echo json_encode([
        'success' => true,
        'message' => 'VK Mini App PHP API работает корректно!',
        'timestamp' => date('c'),
        'server_info' => [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'request_method' => $_SERVER['REQUEST_METHOD']
        ],
        'received_data' => $data
    ]);
}
?>