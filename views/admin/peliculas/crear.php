<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Nueva Película</h1>
            <a href="<?= APP_URL ?>/pelicula" 
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
        <form action="<?= APP_URL ?>/pelicula/crear" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-6">
            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                    Título <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       name="titulo" 
                       id="titulo" 
                       required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       value="<?= isset($datos['titulo']) ? htmlspecialchars($datos['titulo']) : '' ?>">
            </div>

            <!-- Sinopsis -->
            <div class="mb-6">
                <label for="sinopsis" class="block text-sm font-medium text-gray-700 mb-2">
                    Sinopsis
                </label>
                <textarea name="sinopsis" 
                          id="sinopsis" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= isset($datos['sinopsis']) ? htmlspecialchars($datos['sinopsis']) : '' ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Duración -->
                <div>
                    <label for="duracion" class="block text-sm font-medium text-gray-700 mb-2">
                        Duración (minutos) <span class="text-red-600">*</span>
                    </label>
                    <input type="number" 
                           name="duracion" 
                           id="duracion" 
                           required 
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= isset($datos['duracion']) ? htmlspecialchars($datos['duracion']) : '' ?>">
                </div>

                <!-- Clasificación -->
                <div>
                    <label for="clasificacion" class="block text-sm font-medium text-gray-700 mb-2">
                        Clasificación
                    </label>
                    <select name="clasificacion" 
                            id="clasificacion" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar...</option>
                        <option value="G" <?= (isset($datos['clasificacion']) && $datos['clasificacion'] === 'G') ? 'selected' : '' ?>>G (Público General)</option>
                        <option value="PG" <?= (isset($datos['clasificacion']) && $datos['clasificacion'] === 'PG') ? 'selected' : '' ?>>PG (Guía Parental)</option>
                        <option value="PG-13" <?= (isset($datos['clasificacion']) && $datos['clasificacion'] === 'PG-13') ? 'selected' : '' ?>>PG-13 (Mayores de 13)</option>
                        <option value="R" <?= (isset($datos['clasificacion']) && $datos['clasificacion'] === 'R') ? 'selected' : '' ?>>R (Restringido)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Género -->
                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                        Género
                    </label>
                    <input type="text" 
                           name="genero" 
                           id="genero" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= isset($datos['genero']) ? htmlspecialchars($datos['genero']) : '' ?>">
                </div>

                <!-- Director -->
                <div>
                    <label for="director" class="block text-sm font-medium text-gray-700 mb-2">
                        Director
                    </label>
                    <input type="text" 
                           name="director" 
                           id="director" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           value="<?= isset($datos['director']) ? htmlspecialchars($datos['director']) : '' ?>">
                </div>
            </div>

            <!-- Imagen -->
            <div class="mb-6">
                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
                    Imagen del Póster
                </label>
                <input type="file" 
                       name="imagen" 
                       id="imagen" 
                       accept="image/jpeg,image/png"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">
                    Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 5MB
                </p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="<?= APP_URL ?>/pelicula" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Película
                </button>
            </div>
        </form>
    </div>
</div>
