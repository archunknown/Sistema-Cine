<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Cambiar Contraseña</h1>
            <p class="text-gray-600 mt-2">Ingresa tu nueva contraseña</p>
        </div>

        <!-- Mensajes de error/éxito -->
        <?php $mensajes = $this->getMensajes(); ?>
        <?php if ($mensajes['error']): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $mensajes['error'] ?></span>
            </div>
        <?php endif; ?>

        <?php if ($mensajes['exito']): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $mensajes['exito'] ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario de cambio de contraseña -->
        <form action="<?= APP_URL ?>/auth/cambiarPassword" method="POST" class="space-y-6">
            <!-- Contraseña Actual -->
            <div>
                <label for="password_actual" class="block text-sm font-medium text-gray-700">
                    Contraseña Actual
                </label>
                <div class="mt-1">
                    <input type="password" 
                           name="password_actual" 
                           id="password_actual" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ingresa tu contraseña actual">
                </div>
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <label for="password_nuevo" class="block text-sm font-medium text-gray-700">
                    Nueva Contraseña
                </label>
                <div class="mt-1">
                    <input type="password" 
                           name="password_nuevo" 
                           id="password_nuevo" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Mínimo 6 caracteres">
                    <p class="mt-1 text-sm text-gray-500">
                        La contraseña debe tener al menos 6 caracteres
                    </p>
                </div>
            </div>

            <!-- Confirmar Nueva Contraseña -->
            <div>
                <label for="password_confirmar" class="block text-sm font-medium text-gray-700">
                    Confirmar Nueva Contraseña
                </label>
                <div class="mt-1">
                    <input type="password" 
                           name="password_confirmar" 
                           id="password_confirmar" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Repite tu nueva contraseña">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cambiar Contraseña
                </button>
                
                <a href="<?= APP_URL ?>" 
                   class="flex-1 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                    Cancelar
                </a>
            </div>
        </form>

        <!-- Nota de seguridad -->
        <div class="mt-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Por seguridad, serás redirigido al inicio de sesión después de cambiar tu contraseña.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
