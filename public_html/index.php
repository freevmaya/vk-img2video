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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #4f6af5 0%, #3b49df 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .app-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .app-header {
            background: #3b49df;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .app-content {
            padding: 30px;
        }
        
        .user-card {
            background: #f5f7ff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3b49df;
        }
        
        .user-info h3 {
            color: #3b49df;
            margin-bottom: 5px;
        }
        
        .button {
            background: #3b49df;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin: 10px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .button:hover {
            background: #2d3ab5;
            transform: translateY(-2px);
        }
        
        .button.secondary {
            background: #6c757d;
        }
        
        .button.success {
            background: #28a745;
        }
        
        .button.danger {
            background: #dc3545;
        }
        
        .result-area {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            min-height: 100px;
        }
        
        .log {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            font-family: monospace;
            font-size: 14px;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-online {
            background: #28a745;
        }
        
        .status-offline {
            background: #dc3545;
        }
    </style>
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