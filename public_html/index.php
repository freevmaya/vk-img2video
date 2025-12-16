<?php
require_once dirname(__DIR__).'/config.php';
require_once 'vk-bridge-init.php';

// Определяем платформу
$isVK = isset($_GET['vk_access_token_settings']) || isset($_GET['vk_user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VK Mini App с PHP + VK Bridge</title>
    <script src="https://unpkg.com/@vkontakte/vk-bridge/dist/browser.min.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="app-container">
        <div class="app-header">
            <h1>🎮 VK Mini App с PHP</h1>
            <p>Используем VK Bridge для взаимодействия</p>
            <div id="platformInfo" style="margin-top: 10px; font-size: 14px;"></div>
        </div>
        
        <div class="app-content">
            <div class="user-card">
                <img id="userAvatar" src="https://via.placeholder.com/80" class="user-avatar" alt="Аватар">
                <div class="user-info">
                    <h3 id="userName">Загрузка...</h3>
                    <div id="userId">ID: --</div>
                    <div id="userStatus">
                        <span class="status-indicator status-offline"></span>
                        <span>Статус: Не подключен</span>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <button class="button" onclick="getUserInfo()">
                    👤 Получить данные пользователя
                </button>
                
                <button class="button success" onclick="showCommunityWidget()">
                    👥 Показать сообщество
                </button>
                
                <button class="button secondary" onclick="shareContent()">
                    📤 Поделиться
                </button>
                
                <button class="button danger" onclick="closeApp()">
                    ❌ Закрыть приложение
                </button>
                
                <button class="button" onclick="sendToBackend()">
                    🔄 Отправить на PHP сервер
                </button>
            </div>
            
            <div class="result-area">
                <h3>📝 Лог событий:</h3>
                <div id="eventLog"></div>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        // Инициализация при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            initVKBridge();
            detectPlatform();
        });
    </script>
</body>
</html>