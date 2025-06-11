<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Función</h1>
            <a href="<?= APP_URL ?>/funcion" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>

        <!-- Mensajes de error/éxito -->
        <?php $mensajes = $this->getMensajes(); ?>
        <?php if ($mensajes['error']): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $mensajes['error'] ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="<?= APP_URL ?>/funcion/editar/<?= $funcion['id'] ?>" method="POST" class="bg-white rounded-lg shadow-lg p-6">
            <!-- Película -->
            <div class="mb-6">
                <label for="pelicula_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Película <span class="text-red-600">*</span>
                </label>
                <select name="pelicula_id" 
                        id="pelicula_id" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar película...</option>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <option value="<?= $pelicula['id'] ?>" 
                            <?= $funcion['pelicula_id'] == $pelicula['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pelicula['titulo']) ?> 
                            (<?= $pelicula['duracion'] ?> min)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sala -->
            <div class="mb-6">
                <label for="sala_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Sala <span class="text-red-600">*</span>
                </label>
                <select name="sala_id" 
                        id="sala_id" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar sala...</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id'] ?>"
                            <?= $funcion['sala_id'] == $sala['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nombre']) ?> 
                            (Capacidad: <?= $sala['capacidad'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Fecha -->
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha <span class="text-red-600">*</span>
                    </label>
                    <input type="date" 
                           name="fecha" 
                           id="fecha" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= $funcion['fecha'] ?>">
                </div>

                <!-- Hora -->
                <div>
                    <label for="hora" class="block text-sm font-medium text-gray-700 mb-2">
                        Hora <span class="text-red-600">*</span>
                    </label>
                    <input type="time" 
                           name="hora" 
                           id="hora" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= substr($funcion['hora'], 0, 5) ?>">
                </div>
            </div>

            <!-- Precio -->
            <div class="mb-6">
                <label for="precio" class="block text-sm font-medium text-gray-700 mb-2">
                    Precio <span class="text-red-600">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">S/</span>
                    </div>
                    <input type="number" 
                           name="precio" 
                           id="precio" 
                           required 
                           min="0.01" 
                           step="0.01"
                           class="w-full pl-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= $funcion['precio'] ?>"
                           placeholder="0.00">
                </div>
            </div>

            <!-- Estado actual -->
            <div class="mb-6">
                <div class="flex items-center">
                    <span class="mr-2 text-sm text-gray-700">Estado actual:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?= $funcion['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $funcion['estado'] ? 'Activa' : 'Inactiva' ?>
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Puedes cambiar el estado de la función desde la lista de funciones.
                </p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="<?= APP_URL ?>/funcion" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Cambios
                </button>
            </div>
        </form>

        <!-- Advertencia -->
        <div class="mt-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Ten en cuenta que modificar la fecha, hora o sala de una función 
                            podría afectar a las reservas existentes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
