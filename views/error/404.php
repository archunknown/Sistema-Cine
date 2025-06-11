<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-6xl text-yellow-500"></i>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            404 - Página no encontrada
        </h1>
        
        <p class="text-xl text-gray-600 mb-8">
            <?= isset($mensaje) ? $mensaje : 'Lo sentimos, la página que buscas no existe.' ?>
        </p>
        
        <div class="space-x-4">
            <a href="<?= APP_URL ?>" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Volver al inicio
            </a>
            
            <button onclick="history.back()" 
                    class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Regresar
            </button>
        </div>
    </div>
</div>
