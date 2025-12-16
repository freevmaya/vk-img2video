<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru" class="dark-theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VK Image to Video | Конвертер</title>
    
    <!-- VK Bridge -->
    <script src="https://unpkg.com/@vkontakte/vk-bridge/dist/browser.min.js"></script>
    
    <!-- Modern CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Фон с анимацией частиц -->
    <div class="particles-background" id="particles"></div>
    
    <!-- Основной контейнер -->
    <main class="app-container glass-container">
        <!-- Хедер -->
        <header class="app-header">
            <div class="header-content">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-film"></i>
                        <div class="logo-pulse"></div>
                    </div>
                    <div class="logo-text">
                        <h1 class="gradient-text">Image to Video</h1>
                        <p class="subtitle">Modern Video Converter</p>
                    </div>
                </div>
                <div class="platform-badge" id="platformInfo">
                    <i class="fab fa-vk"></i> VK Mini Apps
                </div>
            </div>
        </header>
        
        <!-- Основной контент -->
        <div class="app-content">
            <!-- Карточка пользователя -->
            <div class="card glass-card user-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h2>Профиль пользователя</h2>
                </div>
                <div class="user-content">
                    <div class="user-avatar-container">
                        <div class="user-avatar" id="userAvatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="avatar-ring"></div>
                    </div>
                    <div class="user-details">
                        <h3 id="userName">Загрузка профиля...</h3>
                        <div class="user-meta">
                            <div class="meta-item">
                                <i class="fas fa-id-badge"></i>
                                <span id="userId">ID: --</span>
                            </div>
                            <div class="status-indicator" id="userStatus">
                                <span class="status-dot status-connecting"></span>
                                <span class="status-text" id="statusText">Подключение к VK...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Карточка функций -->
            <div class="card glass-card functions-card">
                <div class="card-header">
                    <i class="fas fa-sliders-h"></i>
                    <h2>Функции приложения</h2>
                </div>
                <div class="functions-grid">
                    <button class="function-button" onclick="getUserInfo()" id="btnUserInfo">
                        <div class="function-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="function-info">
                            <h3>Данные профиля</h3>
                            <p>Получить информацию о пользователе</p>
                        </div>
                        <div class="function-badge">VK API</div>
                    </button>
                    
                    <button class="function-button" onclick="showCommunityWidget()" id="btnCommunity">
                        <div class="function-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="function-info">
                            <h3>Сообщество</h3>
                            <p>Присоединиться к группе</p>
                        </div>
                        <div class="function-badge"><i class="fab fa-vk"></i></div>
                    </button>
                    
                    <button class="function-button" onclick="shareContent()" id="btnShare">
                        <div class="function-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="function-info">
                            <h3>Поделиться</h3>
                            <p>Рассказать друзьям</p>
                        </div>
                        <div class="function-badge pulse">New</div>
                    </button>
                    
                    <button class="function-button" onclick="sendToBackend()" id="btnBackend">
                        <div class="function-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="function-info">
                            <h3>Тест API</h3>
                            <p>Проверить работу сервера</p>
                        </div>
                        <div class="function-badge">PHP</div>
                    </button>
                    
                    <button class="function-button" onclick="toggleTheme()" id="btnTheme">
                        <div class="function-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="function-info">
                            <h3>Тема</h3>
                            <p>Сменить оформление</p>
                        </div>
                        <div class="function-badge"><i class="fas fa-moon"></i></div>
                    </button>
                    
                    <button class="function-button button-danger" onclick="closeApp()" id="btnClose">
                        <div class="function-icon">
                            <i class="fas fa-power-off"></i>
                        </div>
                        <div class="function-info">
                            <h3>Выход</h3>
                            <p>Закрыть приложение</p>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Лог событий -->
            <div class="card glass-card logs-card">
                <div class="card-header">
                    <i class="fas fa-terminal"></i>
                    <h2>Лог событий</h2>
                    <div class="header-actions">
                        <button class="icon-button" onclick="clearLogs()" title="Очистить логи">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="icon-button" onclick="toggleLogs()" title="Свернуть/развернуть">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
                <div class="log-container" id="eventLog">
                    <div class="log-entry log-info">
                        <div class="log-time">[12:00:00]</div>
                        <div class="log-message">Запуск VK Image to Video...</div>
                    </div>
                </div>
            </div>
            
            <!-- Информация о системе -->
            <div class="system-info">
                <div class="info-chip">
                    <i class="fas fa-code"></i>
                    <span>VK Bridge</span>
                    <span class="chip-value" id="bridgeStatus">Загрузка...</span>
                </div>
                <div class="info-chip">
                    <i class="fas fa-shield-alt"></i>
                    <span>Безопасность</span>
                    <span class="chip-value chip-success">HTTPS</span>
                </div>
                <div class="info-chip">
                    <i class="fas fa-bolt"></i>
                    <span>Статус</span>
                    <span class="chip-value" id="appStatus">Инициализация</span>
                </div>
            </div>
        </div>
        
        <!-- Футер -->
        <footer class="app-footer">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#" class="footer-link">Документация</a>
                    <span class="footer-separator">•</span>
                    <a href="#" class="footer-link">Поддержка</a>
                    <span class="footer-separator">•</span>
                    <a href="#" class="footer-link">Конфиденциальность</a>
                </div>
                <div class="footer-copyright">
                    <span>VK Image to Video © 2025</span>
                    <span class="version">v1.0.0</span>
                </div>
            </div>
        </footer>
    </main>
    
    <!-- Основной JavaScript -->
    <script>
    // Глобальные переменные приложения
    const App = {
        bridge: null,
        user: null,
        isInitialized: false,
        appId: <?php echo VK_APP_ID; ?>,
        isDarkTheme: true,
        
        // Инициализация приложения
        async init() {
            this.log('Инициализация приложения...', 'info');
            this.updateStatus('connecting', 'Проверка VK Bridge...');
            this.updateSystemInfo('bridgeStatus', 'Проверка...');
            
            // Проверяем загрузку VK Bridge
            if (typeof vkBridge === 'undefined') {
                this.log('VK Bridge не загружен!', 'error');
                this.updateStatus('error', 'VK Bridge недоступен');
                this.updateSystemInfo('bridgeStatus', '❌ Ошибка');
                this.showFallbackUI();
                return false;
            }
            
            this.bridge = vkBridge;
            this.updateSystemInfo('bridgeStatus', '✅ Загружен');
            this.log('VK Bridge успешно загружен', 'success');
            
            try {
                // Инициализируем VK Mini App
                this.updateStatus('connecting', 'Инициализация VK API...');
                this.log('Отправка VKWebAppInit...', 'info');
                
                const initResult = await this.bridge.send('VKWebAppInit', {});
                this.log('VKWebAppInit успешно выполнен', 'success');
                
                this.isInitialized = true;
                this.updateStatus('online', 'Подключено к VK');
                this.updateSystemInfo('appStatus', '✅ Активно');
                
                // Обновляем информацию о платформе
                document.getElementById('platformInfo').innerHTML = 
                    '<i class="fab fa-vk"></i> VK Mini Apps • <span class="online-dot"></span> Online';
                
                // Автоматически получаем данные пользователя
                await this.getUserInfo();
                
                // Активируем кнопки
                this.enableButtons();
                
                return true;
                
            } catch (error) {
                this.log(`Ошибка инициализации: ${error.message}`, 'error');
                this.updateStatus('error', `Ошибка: ${error.message}`);
                this.updateSystemInfo('appStatus', '❌ Ошибка');
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
                this.updateStatus('connecting', 'Получение данных...');
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
                    avatarElement.innerHTML = '';
                } else {
                    avatarElement.innerHTML = `<i class="fas fa-user"></i>`;
                    avatarElement.style.backgroundImage = '';
                }
                
                this.log(`Пользователь: ${userInfo.first_name} ${userInfo.last_name}`, 'success');
                this.updateStatus('online', `Добро пожаловать, ${userInfo.first_name}!`);
                
                return userInfo;
                
            } catch (error) {
                this.log(`Ошибка получения данных: ${error.message}`, 'error');
                this.updateStatus('error', 'Не удалось получить данные');
                return null;
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
                    group_id: 1,
                    type: 'text',
                    code: `return {
                        title: "Добро пожаловать!",
                        text: "Присоединяйтесь к нашему сообществу."
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
                    text: 'Конвертируйте изображения в видео прямо в VK!'
                });
                
                if (result) {
                    this.log('Контент успешно опубликован!', 'success');
                    this.showNotification('Успешно опубликовано!');
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
                    this.showNotification('Данные успешно сохранены!');
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
        
        // Показать alert
        showAlert(message) {
            if (this.isInitialized) {
                this.bridge.send('VKWebAppShowAlert', { message: message });
            } else {
                alert(message);
            }
        },
        
        // Fallback UI для веб-версии
        showFallbackUI() {
            this.log('Используем веб-версию', 'warning');
            document.getElementById('platformInfo').innerHTML = 
                '<i class="fas fa-globe"></i> Веб-версия';
            
            const urlParams = new URLSearchParams(window.location.search);
            const vkUserId = urlParams.get('vk_user_id');
            
            if (vkUserId) {
                document.getElementById('userName').textContent = 'Пользователь ВКонтакте';
                document.getElementById('userId').textContent = `ID: ${vkUserId}`;
                this.log(`Используем ID из URL: ${vkUserId}`, 'info');
            }
            
            this.enableButtons();
            this.updateSystemInfo('bridgeStatus', '🌐 Веб-режим');
        },
        
        // Логирование
        log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const eventLog = document.getElementById('eventLog');
            const logEntry = document.createElement('div');
            
            logEntry.className = `log-entry log-${type}`;
            logEntry.innerHTML = `
                <div class="log-time">[${timestamp}]</div>
                <div class="log-message">${message}</div>
            `;
            
            eventLog.prepend(logEntry);
            
            // Ограничиваем количество записей
            const logs = eventLog.getElementsByClassName('log-entry');
            if (logs.length > 20) {
                eventLog.removeChild(logs[logs.length - 1]);
            }
            
            console.log(`[${type.toUpperCase()}] ${message}`);
        },
        
        // Обновление статуса
        updateStatus(status, text = '') {
            const indicator = document.querySelector('.status-dot');
            const statusText = document.getElementById('statusText');
            
            // Обновляем индикатор
            indicator.className = 'status-dot';
            indicator.classList.add(`status-${status}`);
            
            // Обновляем текст
            if (text) {
                statusText.textContent = text;
            }
        },
        
        // Обновление информации о системе
        updateSystemInfo(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = value;
            }
        },
        
        // Включение кнопок
        enableButtons() {
            const buttons = document.querySelectorAll('.function-button:not(.button-danger)');
            buttons.forEach(button => {
                button.disabled = false;
                button.classList.remove('disabled');
            });
            this.log('Функции активированы', 'success');
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
        }
    };
    
    // Глобальные функции для кнопок
    function getUserInfo() { App.getUserInfo(); }
    function showCommunityWidget() { App.showCommunityWidget(); }
    function shareContent() { App.shareContent(); }
    function sendToBackend() { App.sendToBackend(); }
    function closeApp() { App.closeApp(); }
    
    // Вспомогательные функции
    function toggleTheme() {
        App.isDarkTheme = !App.isDarkTheme;
        document.body.classList.toggle('dark-theme', App.isDarkTheme);
        document.body.classList.toggle('light-theme', !App.isDarkTheme);
        App.log(`Тема изменена: ${App.isDarkTheme ? 'Темная' : 'Светлая'}`, 'info');
    }
    
    function clearLogs() {
        const eventLog = document.getElementById('eventLog');
        eventLog.innerHTML = '';
        App.log('Логи очищены', 'warning');
    }
    
    function toggleLogs() {
        const logContainer = document.querySelector('.log-container');
        logContainer.classList.toggle('collapsed');
        const icon = document.querySelector('.card-header .fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }
    
    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        // Создаем эффект частиц на фоне
        createParticles();
        
        // Инициализируем приложение
        setTimeout(() => {
            App.init();
        }, 500);
    });
    
    // Создание эффекта частиц
    function createParticles() {
        const particles = document.getElementById('particles');
        const particleCount = 50;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Случайные параметры
            const size = Math.random() * 3 + 1;
            const posX = Math.random() * 100;
            const posY = Math.random() * 100;
            const duration = Math.random() * 20 + 10;
            const delay = Math.random() * 5;
            
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${posX}%`;
            particle.style.top = `${posY}%`;
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `${delay}s`;
            
            particles.appendChild(particle);
        }
    }
    </script>
</body>
</html>