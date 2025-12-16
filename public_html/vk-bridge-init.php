<?php
// Этот файл генерирует JavaScript код для инициализации VK Bridge
// с серверной валидацией подписи

$launchParams = getLaunchParams();
$isValidSignature = verifyVKSignature($launchParams, VK_APP_SECRET);

// Генерируем JavaScript для передачи в фронтенд
$vkBridgeConfig = [
    'appId' => VK_APP_ID,
    'isValid' => $isValidSignature,
    'launchParams' => $launchParams,
    'apiVersion' => VK_API_VERSION
];

// Преобразуем в JSON для передачи в JavaScript
$vkBridgeConfigJSON = json_encode($vkBridgeConfig, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>

<script>
// Конфигурация из PHP
window.VK_BRIDGE_CONFIG = <?php echo $vkBridgeConfigJSON; ?>;

// Безопасная инициализация
window.VK_APP_CONFIG = {
    appId: <?php echo VK_APP_ID; ?>,
    apiVersion: '<?php echo VK_API_VERSION; ?>',
    isValidSignature: <?php echo $isValidSignature ? 'true' : 'false'; ?>
};
</script>