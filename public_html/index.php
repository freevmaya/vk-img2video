<?php
// Начало PHP секции для возможной будущей логики
session_start();

// Конфигурация (можно вынести в отдельный файл)
define('VK_APP_ID', '54399156');
define('APP_NAME', 'VK Image to Video');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VK Image to Video</title>
    
    <!-- ОБЯЗАТЕЛЬНО: Подключаем VK Bridge -->
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
            color: #333;
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
        
        .app-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
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
            border: 2px solid #e0e5ff;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3b49df;
            background: #3b49df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        
        .user-info h3 {
            color: #3b49df;
            margin-bottom: 5px;
            font-size: 1.5em;
        }
        
        .user-info div {
            color: #666;
            margin: 3px 0;
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
            box-shadow: 0 0 8px #28a745;
        }
        
        .status-offline {
            background: #dc3545;
        }
        
        .status-connecting {
            background: #ffc107;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .button-group {
            text-align: center;
            margin: 30px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        
        .button {
            background: #3b49df;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 200px;
            justify-content: center;
        }
        
        .button:hover {
            background: #2d3ab5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 58, 181, 0.3);
        }
        
        .button:active {
            transform: translateY(0);
        }
        
        .button-success {
            background: #28a745;
        }
        
        .button-success:hover {
            background: #218838;
        }
        
        .button-secondary {
            background: #6c757d;
        }
        
        .button-secondary:hover {
            background: #5a6268;
        }
        
        .button-danger {
            background: #dc3545;
        }
        
        .button-danger:hover {
            background: #c82333;
        }
        
        .button:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .result-area {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        
        .result-area h3 {
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .log-container {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .log {
            background: #e9ecef;
            padding: 12px;
            border-radius: 6px;
            margin: 8px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            border-left: 4px solid #3b49df;
            word-break: break-word;
        }
        
        .log-error {
            border-left-color: #dc3545;
            background: #f8d7da;
            color: #721c24;
        }
        
        .log-success {
            border-left-color: #28a745;
            background: #d4edda;
            color: #155724;
        }
        
        .log-warning {
            border-left-color: #ffc107;
            background: #fff3cd;
            color: #856404;
        }
        
        .debug-info {
            margin-top: 20px;
            padding: 15px;
            background: #e7f1ff;
            border-radius: 8px;
            font-size: 14px;
            color: #004085;
            border: 1px solid #b8daff;
        }
        
        .platform-badge {
            display: inline-block;
            padding: 5px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .app-container {
                border-radius: 10px;
            }
            
            .app-header {
                padding: 20px;
            }
            
            .app-header h1 {
                font-size: 1.8em;
            }
            
            .app-content {
                padding: 20px;
            }
            
            .user-card {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .button {
                min-width: 100%;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="app-header">
            <h1>🔄 VK Image to Video</h1>
            <p>Конвертируйте изображения в видео прямо в VK</p>
            <div class="platform-badge" id="platformInfo">Загрузка...</div>
        </div>
        
        <div class="app-content">
            <!-- Карточка пользователя -->
            <div class="user-card">
                <div class="user-avatar" id="userAvatar">
                    VK
                </div>
                <div class="user-info">
                    <h3 id="userName">Инициализация приложения...</h3>
                    <div id="userId">ID: --</div>
                    <div id="userStatus">
                        <span class="status-indicator status-connecting"></span>
                        <span id="statusText">Подключение к VK...</span>
                    </div>
                </div>
            </div>
            
            <!-- Основные функции -->
            <div class="button-group">
                <button class="button" onclick="getUserInfo()" id="btnUserInfo">
                    <span>👤</span> Получить данные
                </button>
                
                <button class="button button-success" onclick="showCommunityWidget()" id="btnCommunity">
                    <span>👥</span> Сообщество
                </button>
                
                <button class="button button-secondary" onclick="shareContent()" id="btnShare">
                    <span>📤</span> Поделиться
                </button>
                
                <button class="button" onclick="sendToBackend()" id="btnBackend">
                    <span>🔄</span> Тест PHP API
                </button>
                
                <button class="button button-danger" onclick="closeApp()" id="btnClose">
                    <span>❌</span> Закрыть
                </button>
            </div>
            
            <!-- Лог событий -->
            <div class="result-area">
                <h3><span>📝</span> Лог событий</h3>
                <div class="log-container" id="eventLog">
                    <div class="log">Запуск VK Mini App...</div>
                </div>
            </div>
            
            <!-- Отладочная информация -->
            <div class="debug-info" id="debugInfo" style="display: none;">
                <strong>Отладка:</strong> <span id="debugText"></span>
            </div>
        </div>
    </div>

    <!-- Основной JavaScript код -->
    <script>
    // Глобальные переменные приложения
    const App = {
        bridge: null,
        user: null,
        isInitialized: false,
        appId: <?php echo VK_APP_ID; ?>,
        
        // Инициализация приложения
        async init() {
            this.log('Начинаем инициализацию приложения...', 'info');
            this.updateStatus('connecting', 'Проверка VK Bridge...');
            
            // Проверяем загрузку VK Bridge
            if (typeof vkBridge === 'undefined') {
                this.log('VK Bridge не загружен!', 'error');
                this.updateStatus('error', 'Ошибка: VK Bridge недоступен');
                this.showFallbackUI();
                return false;
            }
            
            this.bridge = vkBridge;
            this.log('VK Bridge успешно загружен', 'success');
            
            try {
                // Инициализируем VK Mini App
                this.updateStatus('connecting', 'Инициализация VK...');
                this.log('Отправка VKWebAppInit...', 'info');
                
                const initResult = await this.bridge.send('VKWebAppInit', {});
                this.log('VKWebAppInit успешно выполнен', 'success');
                
                this.isInitialized = true;
                this.updateStatus('online', 'Подключено к VK');
                
                // Обновляем информацию о платформе
                document.getElementById('platformInfo').textContent = '✅ VK Mini Apps';
                
                // Автоматически получаем данные пользователя
                await this.getUserInfo();
                
                // Активируем кнопки
                this.enableButtons();
                
                return true;
                
            } catch (error) {
                this.log(`Ошибка инициализации: ${error.message}`, 'error');
                this.updateStatus('error', `Ошибка: ${error.message}`);
                this.showFallbackUI();
                return false;
            }
        },
        
        // Получение информации о пользователе
        async getUserInfo() {
            if (!this.isInitialized) {
                this.log('Приложение не инициализировано', 'warning');
                return null;
            }
            
            try {
                this.updateStatus('connecting', 'Получение данных пользователя...');
                this.log('Запрос VKWebAppGetUserInfo...', 'info');
                
                const userInfo = await this.bridge.send('VKWebAppGetUserInfo', {});
                this.user = userInfo;
                
                // Обновляем интерфейс
                document.getElementById('userName').textContent = 
                    `${userInfo.first_name} ${userInfo.last_name}`;
                document.getElementById('userId').textContent = 
                    `ID: ${userInfo.id}`;
                
                // Обновляем аватар
                const avatarElement = document.getElementById('userAvatar');
                if (userInfo.photo_200) {
                    avatarElement.style.backgroundImage = `url(${userInfo.photo_200})`;
                    avatarElement.style.backgroundSize = 'cover';
                    avatarElement.textContent = '';
                } else {
                    avatarElement.textContent = userInfo.first_name.charAt(0);
                }
                
                this.log(`Пользователь: ${userInfo.first_name} ${userInfo.last_name}`, 'success');
                this.updateStatus('online', `Добро пожаловать, ${userInfo.first_name}!`);
                
                // Получаем дополнительные данные через API
                await this.getUserProfile();
                
                return userInfo;
                
            } catch (error) {
                this.log(`Ошибка получения данных: ${error.message}`, 'error');
                this.updateStatus('error', 'Не удалось получить данные');
                return null;
            }
        },
        
        // Получение дополнительной информации о пользователе
        async getUserProfile() {
            if (!this.user) return;
            
            try {
                const response = await this.bridge.send('VKWebAppCallAPIMethod', {
                    method: 'users.get',
                    params: {
                        user_ids: this.user.id,
                        fields: 'photo_200,city,country,sex,bdate',
                        v: '5.199'
                    }
                });
                
                if (response.response && response.response[0]) {
                    const profile = response.response[0];
                    if (profile.city) {
                        this.log(`Город: ${profile.city.title}`, 'info');
                    }
                    if (profile.bdate) {
                        this.log(`Дата рождения: ${profile.bdate}`, 'info');
                    }
                }
            } catch (error) {
                // Не критичная ошибка, просто логируем
                console.log('Не удалось получить расширенный профиль:', error);
            }
        },
        
        // Показать сообщество
        async showCommunityWidget() {
            if (!this.isInitialized) {
                this.showAlert('Приложение не инициализировано');
                return;
            }
            
            try {
                this.log('Открытие виджета сообщества...', 'info');
                
                await this.bridge.send('VKWebAppShowCommunityWidgetPreviewBox', {
                    group_id: 1, // ЗАМЕНИТЕ на ID вашего сообщества
                    type: 'text',
                    code: `return {
                        title: "Добро пожаловать в VK Image to Video!",
                        text: "Присоединяйтесь к нашему сообществу для обновлений и поддержки."
                    };`
                });
                
                this.log('Виджет сообщества открыт', 'success');
                
            } catch (error) {
                this.log(`Ошибка открытия сообщества: ${error.message}`, 'error');
            }
        },
        
        // Поделиться контентом
        async shareContent() {
            if (!this.isInitialized) {
                this.showAlert('Приложение не инициализировано');
                return;
            }
            
            try {
                this.log('Публикация контента...', 'info');
                
                const result = await this.bridge.send('VKWebAppShare', {
                    link: `https://vk.com/app${this.appId}`,
                    title: 'VK Image to Video',
                    text: 'Попробуйте это крутое приложение для конвертации изображений в видео!'
                });
                
                if (result) {
                    this.log('Контент успешно опубликован!', 'success');
                }
                
            } catch (error) {
                this.log(`Ошибка публикации: ${error.message}`, 'error');
            }
        },
        
        // Отправить данные на PHP сервер
        async sendToBackend() {
            if (!this.user) {
                this.log('Нет данных пользователя для отправки', 'warning');
                return;
            }
            
            try {
                this.log('Отправка данных на сервер...', 'info');
                
                const response = await fetch('/api/vk-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-VK-App-ID': this.appId.toString()
                    },
                    body: JSON.stringify({
                        action: 'saveData',
                        user: this.user,
                        timestamp: new Date().toISOString(),
                        launchParams: this.getLaunchParams()
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.log(`Данные сохранены: ${result.message}`, 'success');
                    this.showNotification(result.message);
                } else {
                    this.log(`Ошибка сервера: ${result.error}`, 'error');
                }
                
            } catch (error) {
                this.log(`Ошибка отправки: ${error.message}`, 'error');
            }
        },
        
        // Закрыть приложение
        async closeApp() {
            if (!this.isInitialized) {
                // Если не инициализировано в VK, просто обновляем страницу
                window.location.href = 'https://vk.com';
                return;
            }
            
            try {
                await this.bridge.send('VKWebAppClose', {
                    status: 'success'
                });
            } catch (error) {
                this.log(`Ошибка закрытия: ${error.message}`, 'error');
                window.location.href = 'https://vk.com';
            }
        },
        
        // Показать уведомление
        async showNotification(message) {
            if (!this.isInitialized) return;
            
            try {
                await this.bridge.send('VKWebAppShowNotification', {
                    message: message
                });
            } catch (error) {
                console.log('Не удалось показать уведомление:', error);
            }
        },
        
        // Показать alert (fallback)
        showAlert(message) {
            if (this.isInitialized) {
                this.bridge.send('VKWebAppShowAlert', { message: message });
            } else {
                alert(message);
            }
        },
        
        // Fallback UI для веб-версии
        showFallbackUI() {
            this.log('Используем fallback режим (веб-версия)', 'warning');
            document.getElementById('platformInfo').textContent = '🌐 Веб-версия';
            
            // Пробуем получить данные из URL параметров
            const urlParams = new URLSearchParams(window.location.search);
            const vkUserId = urlParams.get('vk_user_id');
            
            if (vkUserId) {
                document.getElementById('userName').textContent = 'Пользователь ВКонтакте';
                document.getElementById('userId').textContent = `ID: ${vkUserId}`;
                this.log(`Используем ID из URL: ${vkUserId}`, 'info');
            }
            
            this.enableButtons();
        },
        
        // Логирование
        log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const eventLog = document.getElementById('eventLog');
            const logEntry = document.createElement('div');
            
            logEntry.className = `log log-${type}`;
            logEntry.innerHTML = `<strong>[${timestamp}]</strong> ${message}`;
            
            eventLog.prepend(logEntry);
            
            // Ограничиваем количество записей
            const logs = eventLog.getElementsByClassName('log');
            if (logs.length > 15) {
                eventLog.removeChild(logs[logs.length - 1]);
            }
            
            // Прокручиваем к началу
            eventLog.scrollTop = 0;
            
            // Для отладки в консоли
            console.log(`[${type.toUpperCase()}] ${message}`);
        },
        
        // Обновление статуса
        updateStatus(status, text = '') {
            const indicator = document.querySelector('.status-indicator');
            const statusText = document.getElementById('statusText');
            
            // Обновляем индикатор
            indicator.className = 'status-indicator';
            indicator.classList.add(`status-${status}`);
            
            // Обновляем текст
            if (text) {
                statusText.textContent = text;
            }
        },
        
        // Включение кнопок
        enableButtons() {
            const buttons = document.querySelectorAll('.button:not(#btnClose)');
            buttons.forEach(button => {
                button.disabled = false;
            });
            this.log('Кнопки активированы', 'success');
        },
        
        // Получение launch параметров
        getLaunchParams() {
            const params = {};
            window.location.search.substring(1).split('&').forEach(pair => {
                const [key, value] = pair.split('=');
                if (key && value) {
                    params[key] = decodeURIComponent(value);
                }
            });
            return params;
        },
        
        // Отладка
        showDebugInfo() {
            const debugInfo = document.getElementById('debugInfo');
            const debugText = document.getElementById('debugText');
            
            const info = {
                url: window.location.href,
                userAgent: navigator.userAgent,
                vkBridge: typeof vkBridge !== 'undefined',
                appInitialized: this.isInitialized,
                hasUser: !!this.user,
                launchParams: this.getLaunchParams()
            };
            
            debugText.textContent = JSON.stringify(info, null, 2);
            debugInfo.style.display = 'block';
        }
    };
    
    // Глобальные функции для кнопок
    function getUserInfo() { App.getUserInfo(); }
    function showCommunityWidget() { App.showCommunityWidget(); }
    function shareContent() { App.shareContent(); }
    function sendToBackend() { App.sendToBackend(); }
    function closeApp() { App.closeApp(); }
    
    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        // Ждем немного, чтобы VK Bridge точно загрузился
        setTimeout(() => {
            App.init();
            
            // Показываем отладочную информацию (можно отключить)
            App.showDebugInfo();
        }, 100);
    });
    </script>
</body>
</html>