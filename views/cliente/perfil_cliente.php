<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mi Perfil</h1>
            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">
                <?= ucfirst($usuario['rol']) ?>
            </span>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['mensaje'] ?>
                <?php unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Información Personal -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <form action="<?= APP_URL ?>/perfil/actualizar" method="POST" class="divide-y divide-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Información Personal</h2>
                        <span class="text-sm text-gray-500">* Campos obligatorios</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre completo *
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   value="<?= htmlspecialchars($usuario['nombre']) ?>"
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo electrónico *
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="<?= htmlspecialchars($usuario['email']) ?>"
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   required>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono
                            </label>
                            <input type="tel" name="telefono" id="telefono" 
                                   value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   placeholder="Ej: +51 999 999 999">
                        </div>

                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">
                                Dirección
                            </label>
                            <input type="text" name="direccion" id="direccion" 
                                   value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>"
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   placeholder="Ingrese su dirección">
                        </div>
                    </div>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Cambiar Contraseña</h2>
                        <span class="text-sm text-gray-500">Opcional</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="password_actual" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña actual
                            </label>
                            <input type="password" name="password_actual" id="password_actual" 
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                        </div>

                        <div>
                            <label for="password_nuevo" class="block text-sm font-medium text-gray-700 mb-1">
                                Nueva contraseña
                            </label>
                            <input type="password" name="password_nuevo" id="password_nuevo" 
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   minlength="6">
                            <p class="mt-1 text-sm text-gray-500">Mínimo 6 caracteres</p>
                        </div>

                        <div>
                            <label for="password_confirmar" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar nueva contraseña
                            </label>
                            <input type="password" name="password_confirmar" id="password_confirmar" 
                                   class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                                   minlength="6">
                        </div>
                    </div>
                </div>

                <!-- Botón de guardar -->
                <div class="px-8 py-4 bg-gray-50">
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mis últimas compras -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Mis últimas compras</h2>
                    <a href="<?= APP_URL ?>/mis-compras" 
                       class="text-red-600 hover:text-red-700 font-medium flex items-center">
                        <span>Ver historial completo</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <?php if (isset($ultimas_compras) && !empty($ultimas_compras)): ?>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($ultimas_compras as $compra): ?>
                            <div class="py-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <?= htmlspecialchars($compra['pelicula']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        <?= htmlspecialchars($compra['fecha']) ?> - 
                                        <?= htmlspecialchars($compra['hora']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-medium text-gray-900">
                                        S/ <?= number_format($compra['total'], 2) ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?= htmlspecialchars($compra['cantidad']) ?> boleto(s)
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">Aún no tienes compras realizadas</p>
                        <a href="<?= APP_URL ?>/" 
                           class="mt-4 inline-block px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            Ver cartelera
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
