<?php
// Archivo: debug_images.php
// Coloca este archivo en la raíz de tu proyecto para diagnosticar el problema

// Definir rutas
define('BASE_PATH', __DIR__ . '/');
define('PUBLIC_PATH', BASE_PATH . 'public/');
define('APP_URL', 'http://localhost/sistema_cine');

echo "<h1>Diagnóstico de Imágenes - Sistema Cine</h1>";

// 1. Verificar estructura de directorios
echo "<h2>1. Estructura de Directorios</h2>";
$uploadDir = PUBLIC_PATH . 'uploads/Peliculas/';
echo "<p><strong>Directorio de uploads:</strong> " . $uploadDir . "</p>";
echo "<p><strong>¿Existe el directorio?</strong> " . (file_exists($uploadDir) ? 'SÍ' : 'NO') . "</p>";

if (file_exists($uploadDir)) {
    echo "<p><strong>¿Es escribible?</strong> " . (is_writable($uploadDir) ? 'SÍ' : 'NO') . "</p>";
    echo "<p><strong>Permisos:</strong> " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "</p>";
}

// 2. Listar archivos en el directorio
echo "<h2>2. Archivos en el Directorio</h2>";
if (file_exists($uploadDir)) {
    $archivos = scandir($uploadDir);
    if ($archivos) {
        echo "<ul>";
        foreach ($archivos as $archivo) {
            if ($archivo != '.' && $archivo != '..') {
                $rutaCompleta = $uploadDir . $archivo;
                $urlArchivo = APP_URL . '/uploads/Peliculas/' . $archivo;
                echo "<li>";
                echo "<strong>Archivo:</strong> " . $archivo . "<br>";
                echo "<strong>Ruta física:</strong> " . $rutaCompleta . "<br>";
                echo "<strong>URL esperada:</strong> " . $urlArchivo . "<br>";
                echo "<strong>¿Existe físicamente?</strong> " . (file_exists($rutaCompleta) ? 'SÍ' : 'NO') . "<br>";
                echo "<strong>Tamaño:</strong> " . (file_exists($rutaCompleta) ? filesize($rutaCompleta) . ' bytes' : 'N/A') . "<br>";
                echo "<strong>Test de imagen:</strong> <img src='" . $urlArchivo . "' style='max-width:100px;max-height:100px;' onerror=\"this.style.display='none'; this.nextSibling.style.display='inline';\" /><span style='display:none; color:red;'>ERROR AL CARGAR</span><br><br>";
                echo "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>No se pudieron leer los archivos del directorio.</p>";
    }
} else {
    echo "<p>El directorio no existe.</p>";
}

// 3. Verificar configuración del servidor
echo "<h2>3. Configuración del Servidor</h2>";
echo "<p><strong>Servidor web:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// 4. Test de acceso directo
echo "<h2>4. Test de Acceso Directo</h2>";
$testUrls = [
    APP_URL . '/uploads/Peliculas/',
    APP_URL . '/public/uploads/Peliculas/',
    'http://localhost/sistema_cine/uploads/Peliculas/',
    'http://localhost/sistema_cine/public/uploads/Peliculas/'
];

foreach ($testUrls as $url) {
    echo "<p><strong>Probando:</strong> <a href='" . $url . "' target='_blank'>" . $url . "</a></p>";
}

// 5. Verificar .htaccess
echo "<h2>5. Verificar .htaccess</h2>";
$htaccessPath = BASE_PATH . '.htaccess';
echo "<p><strong>¿Existe .htaccess?</strong> " . (file_exists($htaccessPath) ? 'SÍ' : 'NO') . "</p>";
if (file_exists($htaccessPath)) {
    echo "<p><strong>¿Es legible?</strong> " . (is_readable($htaccessPath) ? 'SÍ' : 'NO') . "</p>";
}

// 6. Verificar mod_rewrite
echo "<h2>6. Verificar mod_rewrite</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p><strong>mod_rewrite habilitado:</strong> " . (in_array('mod_rewrite', $modules) ? 'SÍ' : 'NO') . "</p>";
} else {
    echo "<p>No se puede verificar mod_rewrite (función apache_get_modules no disponible)</p>";
}

// 7. Crear imagen de prueba
echo "<h2>7. Crear Imagen de Prueba</h2>";
if (extension_loaded('gd')) {
    $testImage = $uploadDir . 'test_image.png';
    $img = imagecreate(200, 100);
    $bg = imagecolorallocate($img, 255, 255, 255);
    $textColor = imagecolorallocate($img, 0, 0, 0);
    imagestring($img, 5, 50, 40, 'TEST', $textColor);
    
    if (imagepng($img, $testImage)) {
        echo "<p><strong>Imagen de prueba creada:</strong> " . $testImage . "</p>";
        $testUrl = APP_URL . '/uploads/Peliculas/test_image.png';
        echo "<p><strong>URL de prueba:</strong> <a href='" . $testUrl . "' target='_blank'>" . $testUrl . "</a></p>";
        echo "<p><strong>Test visual:</strong> <img src='" . $testUrl . "' style='border:1px solid #ccc;' /></p>";
    } else {
        echo "<p style='color:red;'>Error al crear imagen de prueba</p>";
    }
    imagedestroy($img);
} else {
    echo "<p>Extensión GD no disponible para crear imagen de prueba</p>";
}

echo "<h2>8. Recomendaciones</h2>";
echo "<ul>";
echo "<li>Verificar que el directorio uploads/Peliculas tenga permisos 755 o 777</li>";
echo "<li>Asegurar que .htaccess permita acceso a archivos estáticos</li>";
echo "<li>Verificar que APP_URL esté configurado correctamente</li>";
echo "<li>Comprobar que no hay conflictos en las rutas</li>";
echo "</ul>";
?>
