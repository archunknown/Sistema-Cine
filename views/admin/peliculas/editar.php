<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Película</h1>
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
        <form action="<?= APP_URL ?>/pelicula/editar/<?= $pelicula['id'] ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-6">
            <!-- Imagen actual -->
            <?php if (!empty($pelicula['imagen_ruta'])): ?>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen Actual
                    </label>
                    <div class="w-48 h-64 relative">
                        <?php 
                            $rutaImagenRaw = $pelicula['imagen_ruta'];
                            
                            // Construir URL para mostrar la imagen
                            $rutaImagenUrl = APP_URL . '/' . $rutaImagenRaw;
                            Debug::log("Vista editar - URL de imagen: " . $rutaImagenUrl);
                            
                            // Construir ruta física para verificación
                            $rutaFisica = PUBLIC_PATH . str_replace('/', DIRECTORY_SEPARATOR, $rutaImagenRaw);
                            Debug::log("Vista editar - Verificando archivo en: " . $rutaFisica);
                            
                            // Verificar existencia y permisos
                            if (file_exists($rutaFisica)) {
                                Debug::log("Vista editar - Archivo encontrado");
                                Debug::log("Vista editar - Permisos: " . substr(sprintf('%o', fileperms($rutaFisica)), -4));
                                Debug::log("Vista editar - Tamaño: " . filesize($rutaFisica) . " bytes");
                            } else {
                                Debug::log("Vista editar - Archivo NO encontrado");
                                Debug::log("Vista editar - Directorio existe: " . (is_dir(dirname($rutaFisica)) ? "Sí" : "No"));
                            }
                        ?>
                        <img src="<?= htmlspecialchars($rutaImagenUrl) ?>" 
                             alt="<?= htmlspecialchars($pelicula['titulo']) ?>"
                             class="w-full h-full object-cover rounded"
                             onerror="console.error('Error al cargar la imagen: ' + this.src);">
                        <a href="<?= APP_URL ?>/pelicula/borrarImagen/<?= $pelicula['id'] ?>"
                           onclick="return confirm('¿Estás seguro de eliminar esta imagen?')"
                           class="absolute top-0 right-0 mt-2 mr-2 text-red-600 hover:text-red-800 bg-white rounded-full p-1 shadow cursor-pointer">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

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
                       value="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>

            <!-- Sinopsis -->
            <div class="mb-6">
                <label for="sinopsis" class="block text-sm font-medium text-gray-700 mb-2">
                    Sinopsis
                </label>
                <textarea name="sinopsis" 
                          id="sinopsis" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($pelicula['sinopsis']) ?></textarea>
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
                           value="<?= htmlspecialchars($pelicula['duracion']) ?>">
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
                        <option value="G" <?= $pelicula['clasificacion'] === 'G' ? 'selected' : '' ?>>G (Público General)</option>
                        <option value="PG" <?= $pelicula['clasificacion'] === 'PG' ? 'selected' : '' ?>>PG (Guía Parental)</option>
                        <option value="PG-13" <?= $pelicula['clasificacion'] === 'PG-13' ? 'selected' : '' ?>>PG-13 (Mayores de 13)</option>
                        <option value="R" <?= $pelicula['clasificacion'] === 'R' ? 'selected' : '' ?>>R (Restringido)</option>
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
                           value="<?= htmlspecialchars($pelicula['genero']) ?>">
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
                           value="<?= htmlspecialchars($pelicula['director']) ?>">
                </div>
            </div>

            <!-- Nueva Imagen -->
            <div class="mb-6">
                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-2">
                    Nueva Imagen del Póster
                </label>
                <input type="file" 
                       name="imagen" 
                       id="imagen" 
                       accept="image/jpeg,image/png"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">
                    Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 5MB. 
                    Deja este campo vacío si no deseas cambiar la imagen actual.
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
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
