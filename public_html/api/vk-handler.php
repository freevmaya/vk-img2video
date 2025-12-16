<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://vk.com');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-VK-App-ID');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Проверяем App ID из заголовков
$appId = $_SERVER['HTTP_X_VK_APP_ID'] ?? '';
if ($appId !== VK_APP_ID) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid App ID']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$action = $input['action'] ?? '';

switch ($action) {
    case 'saveData':
        handleSaveData($input);
        break;
        
    case 'getUserData':
        handleGetUserData($input);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}

function handleSaveData($data) {
    // Валидация данных
    if (!isset($data['user']) || !isset($data['user']['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid user data']);
        return;
    }
    
    // Сохранение в файл (в реальном приложении - в БД)
    $filename = 'data/user_' . $data['user']['id'] . '.json';
    $dir = dirname(__FILE__) . '/../data';
    
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $saveData = [
        'user_id' => $data['user']['id'],
        'user_name' => $data['user']['first_name'] . ' ' . $data['user']['last_name'],
        'action' => 'saveData',
        'timestamp' => $data['timestamp'],
        'server_time' => date('c'),
        'ip' => $_SERVER['REMOTE_ADDR']
    ];
    
    file_put_contents($dir . '/' . $filename, json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode([
        'success' => true,
        'message' => 'Данные пользователя ' . $data['user']['first_name'] . ' успешно сохранены!',
        'saved_at' => date('c'),
        'user_id' => $data['user']['id']
    ]);
}

function handleGetUserData($data) {
    // Здесь можно получить дополнительные данные пользователя из БД
    
    echo json_encode([
        'success' => true,
        'data' => [
            'statistics' => [
                'visits' => rand(1, 100),
                'last_visit' => date('c')
            ],
            'achievements' => ['Новичок', 'Активный пользователь']
        ]
    ]);
}
?>