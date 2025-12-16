// Определение платформы
function detectPlatform() {
    const platformElement = document.getElementById('platformInfo');
    
    if (typeof VK !== 'undefined') {
        platformElement.textContent = 'Платформа: ВКонтакте';
        initVK();
    } else if (typeof OK !== 'undefined') {
        platformElement.textContent = 'Платформа: Одноклассники';
        initOK();
    } else {
        platformElement.textContent = 'Платформа: Веб-браузер';
        initWeb();
    }
}

// Инициализация для VK
function initVK() {
    VK.init(function() {
        console.log('VK API инициализирован');
        
        // Получение информации о пользователе
        VK.api('users.get', {fields: 'photo_100, city, country'}, function(response) {
            if (response && response.response) {
                const user = response.response[0];
                document.getElementById('userInfo').innerHTML = `
                    <strong>${user.first_name} ${user.last_name}</strong><br>
                    ID: ${user.id}<br>
                    <img src="${user.photo_100}" alt="Фото" style="border-radius:50%; margin-top:10px;">
                `;
            }
        });
    }, function() {
        console.log('Ошибка инициализации VK API');
    }, '5.131');
}

// Инициализация для OK
function initOK() {
    // Для OK API потребуется дополнительная настройка в настройках приложения
    console.log('OK API инициализирован');
    
    // Здесь можно добавить вызовы OK API
    document.getElementById('userInfo').innerHTML = 
        'Мини-приложение в Одноклассниках<br>Для доступа к данным пользователя настройте OK API';
}

// Инициализация для веб-версии
function initWeb() {
    document.getElementById('userInfo').innerHTML = 
        'Это веб-версия приложения<br>В мини-приложении здесь будет информация о пользователе';
}

// Обновление времени
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('ru-RU');
    document.getElementById('currentTime').textContent = timeString;
}

// Показать сообщение
function showAlert() {
    alert('Привет! Это простое мини-приложение работает! 🎉');
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function() {
    detectPlatform();
    updateTime();
    
    // Обновление времени каждую секунду
    setInterval(updateTime, 1000);
});