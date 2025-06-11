<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Iniciar Sesión</h1>
            <p class="text-gray-600 mt-2">Ingresa tus credenciales para acceder</p>
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

        <!-- Formulario de login -->
        <form action="<?= APP_URL ?>/auth/login" method="POST" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Correo Electrónico
                </label>
                <div class="mt-1">
                    <input type="email" 
                           name="email" 
                           id="email" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="ejemplo@correo.com">
                </div>
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Contraseña
                </label>
                <div class="mt-1">
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Botón de submit -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Iniciar Sesión
                </button>
            </div>
        </form>

        <!-- Enlaces adicionales -->
        <div class="mt-6 text-center text-sm">
            <p class="text-gray-600">
                ¿No tienes una cuenta? 
                <a href="<?= APP_URL ?>/auth/registro" class="font-medium text-blue-600 hover:text-blue-500">
                    Regístrate aquí
                </a>
            </p>
            <p class="mt-2">
                <a href="<?= APP_URL ?>" class="font-medium text-gray-600 hover:text-gray-500">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Volver al inicio
                </a>
            </p>
        </div>
    </div>
</div>
