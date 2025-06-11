<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-lock text-6xl text-red-500"></i>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            403 - Acceso Denegado
        </h1>
        
        <p class="text-xl text-gray-600 mb-8">
            <?= isset($mensaje) ? $mensaje : 'No tienes permiso para acceder a esta página.' ?>
        </p>
        
        <div class="space-x-4">
            <a href="<?= APP_URL ?>" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Volver al inicio
            </a>
            
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <a href="<?= APP_URL ?>/auth/login" 
                   class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </a>
            <?php else: ?>
                <button onclick="history.back()" 
                        class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Regresar
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
