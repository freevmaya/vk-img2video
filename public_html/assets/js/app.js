// Основной файл приложения с VK Bridge
class VKApp {
    constructor() {
        this.bridge = window.vkBridge;
        this.user = null;
        this.isInitialized = false;
        this.eventLog = document.getElementById('eventLog');
    }

    // Инициализация VK Bridge
    async init() {
        try {
            // Сначала отправляем событие инициализации
            await this.bridge.send('VKWebAppInit');
            this.logEvent('✅ VK Bridge инициализирован');
            
            // Получаем информацию о устройстве
            const deviceInfo = await this.bridge.send('VKWebAppGetDeviceInfo');
            this.logEvent(`📱 Устройство: ${deviceInfo.device_model}`);
            
            // Получаем информацию о пользователе
            await this.getUserData();
            
            this.isInitialized = true;
            this.updateStatus(true);
            
        } catch (error) {
            this.logEvent(`❌ Ошибка инициализации: ${error.message}`);
            this.updateStatus(false);
        }
    }

    // Получение данных пользователя
    async getUserData() {
        try {
            // Получаем информацию о пользователе
            const userInfo = await this.bridge.send('VKWebAppGetUserInfo');
            
            this.user = userInfo;
            
            // Обновляем UI
            document.getElementById('userName').textContent = 
                `${userInfo.first_name} ${userInfo.last_name}`;
            document.getElementById('userId').textContent = 
                `ID: ${userInfo.id}`;
            
            if (userInfo.photo_200) {
                document.getElementById('userAvatar').src = userInfo.photo_200;
            }
            
            this.logEvent(`👤 Пользователь: ${userInfo.first_name} ${userInfo.last_name}`);
            
            // Дополнительно получаем профиль через API
            await this.getUserProfile();
            
        } catch (error) {
            this.logEvent(`❌ Ошибка получения данных: ${error.message}`);
            
            // Fallback: используем данные из launch params
            if (window.VK_BRIDGE_CONFIG.launchParams.vk_user_id) {
                this.user = {
                    id: window.VK_BRIDGE_CONFIG.launchParams.vk_user_id,
                    first_name: 'Пользователь',
                    last_name: 'ВКонтакте'
                };
                
                document.getElementById('userName').textContent = 'Пользователь ВКонтакте';
                document.getElementById('userId').textContent = 
                    `ID: ${this.user.id}`;
            }
        }
    }

    // Получение расширенного профиля
    async getUserProfile() {
        try {
            // Используем VK API для получения дополнительной информации
            const params = {
                user_ids: this.user.id,
                fields: 'photo_200, city, country, sex, bdate',
                v: window.VK_APP_CONFIG.apiVersion
            };
            
            const response = await this.bridge.send('VKWebAppCallAPIMethod', {
                method: 'users.get',
                params: params
            });
            
            if (response.response) {
                const userData = response.response[0];
                this.logEvent(`📍 Город: ${userData.city?.title || 'Не указан'}`);
                this.logEvent(`🎂 Дата рождения: ${userData.bdate || 'Не указана'}`);
            }
            
        } catch (error) {
            console.warn('Не удалось получить расширенный профиль:', error);
        }
    }

    // Показать сообщество
    async showCommunityWidget() {
        try {
            await this.bridge.send('VKWebAppShowCommunityWidgetPreviewBox', {
                group_id: 1, // ID вашего сообщества
                type: 'text',
                code: 'return { title: "Добро пожаловать!", text: "Это наше сообщество!" };'
            });
            this.logEvent('👥 Открыто окно сообщества');
        } catch (error) {
            this.logEvent(`❌ Ошибка отображения сообщества: ${error.message}`);
        }
    }

    // Поделиться контентом
    async shareContent() {
        try {
            const result = await this.bridge.send('VKWebAppShare', {
                link: 'https://vk.com/app' + window.VK_APP_CONFIG.appId
            });
            
            if (result) {
                this.logEvent('📤 Контент успешно опубликован');
            }
        } catch (error) {
            this.logEvent(`❌ Ошибка публикации: ${error.message}`);
        }
    }

    // Закрыть приложение
    async closeApp() {
        try {
            await this.bridge.send('VKWebAppClose', {
                status: 'success'
            });
        } catch (error) {
            this.logEvent(`❌ Ошибка закрытия: ${error.message}`);
        }
    }

    // Отправить данные на PHP сервер
    async sendToBackend() {
        try {
            this.logEvent('🔄 Отправка данных на сервер...');
            
            const response = await fetch('/api/vk-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-VK-App-ID': window.VK_APP_CONFIG.appId
                },
                body: JSON.stringify({
                    action: 'saveData',
                    user: this.user,
                    timestamp: new Date().toISOString(),
                    launchParams: window.VK_BRIDGE_CONFIG.launchParams
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.logEvent(`✅ Данные сохранены: ${result.message}`);
                this.showNotification(result.message);
            } else {
                this.logEvent(`❌ Ошибка сервера: ${result.error}`);
            }
            
        } catch (error) {
            this.logEvent(`❌ Ошибка отправки: ${error.message}`);
        }
    }

    // Показать уведомление
    async showNotification(message) {
        try {
            await this.bridge.send('VKWebAppShowNotification', {
                message: message
            });
        } catch (error) {
            console.warn('Не удалось показать уведомление:', error);
        }
    }

    // Логирование событий
    logEvent(message) {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = document.createElement('div');
        logEntry.className = 'log';
        logEntry.innerHTML = `<strong>[${timestamp}]</strong> ${message}`;
        
        this.eventLog.prepend(logEntry);
        
        // Ограничиваем количество записей
        const logs = this.eventLog.getElementsByClassName('log');
        if (logs.length > 10) {
            this.eventLog.removeChild(logs[logs.length - 1]);
        }
    }

    // Обновление статуса
    updateStatus(isOnline) {
        const statusElement = document.getElementById('userStatus');
        const indicator = statusElement.querySelector('.status-indicator');
        
        if (isOnline) {
            indicator.className = 'status-indicator status-online';
            statusElement.querySelector('span:last-child').textContent = 'Статус: Подключен к VK';
        } else {
            indicator.className = 'status-indicator status-offline';
            statusElement.querySelector('span:last-child').textContent = 'Статус: Не подключен';
        }
    }
}

// Глобальные функции для кнопок
let vkApp;

function initVKBridge() {
    if (!window.vkBridge) {
        console.error('VK Bridge не загружен!');
        return;
    }
    
    vkApp = new VKApp();
    vkApp.init();
}

function getUserInfo() {
    if (vkApp) {
        vkApp.getUserData();
    }
}

function showCommunityWidget() {
    if (vkApp) {
        vkApp.showCommunityWidget();
    }
}

function shareContent() {
    if (vkApp) {
        vkApp.shareContent();
    }
}

function closeApp() {
    if (vkApp) {
        vkApp.closeApp();
    }
}

function sendToBackend() {
    if (vkApp) {
        vkApp.sendToBackend();
    }
}

// Определение платформы
function detectPlatform() {
    const platformInfo = document.getElementById('platformInfo');
    
    if (window.vkBridge) {
        platformInfo.innerHTML = '🌐 Платформа: VK Mini Apps';
    } else if (window.VK_BRIDGE_CONFIG && VK_BRIDGE_CONFIG.isValid) {
        platformInfo.innerHTML = '✅ Подпись VK проверена';
    } else {
        platformInfo.innerHTML = '⚠️ Веб-версия (без VK Bridge)';
    }
}

// Обработчик событий VK Bridge
if (window.vkBridge) {
    // Подписываемся на события
    vkBridge.subscribe((e) => {
        if (!vkApp) return;
        
        switch (e.detail.type) {
            case 'VKWebAppUpdateConfig':
                vkApp.logEvent('⚙️ Конфигурация обновлена');
                break;
                
            case 'VKWebAppViewHide':
                vkApp.logEvent('👁️ Приложение скрыто');
                break;
                
            case 'VKWebAppViewRestore':
                vkApp.logEvent('👁️ Приложение восстановлено');
                break;
        }
    });
}