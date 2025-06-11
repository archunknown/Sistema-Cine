<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Películas</h1>
        <a href="<?= APP_URL ?>/pelicula/crear" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Nueva Película
        </a>
    </div>

    <!-- Mensajes de error/éxito -->
    <?php $mensajes = $this->getMensajes(); ?>
    <?php if ($mensajes['error']): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-lg" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <i class="fas fa-exclamation-circle text-2xl mr-4"></i>
                </div>
                <div>
                    <p class="font-bold">Error</p>
                    <p class="text-sm"><?= $mensajes['error'] ?></p>
                    <?php if (strpos($mensajes['error'], 'funciones asociadas') !== false): ?>
                        <p class="text-sm mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Debe eliminar o desactivar las funciones asociadas antes de eliminar la película.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($mensajes['exito']): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-lg" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <i class="fas fa-check-circle text-2xl mr-4"></i>
                </div>
                <div>
                    <p class="font-bold">¡Éxito!</p>
                    <p class="text-sm"><?= $mensajes['exito'] ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tabla de películas -->
    <?php if (empty($peliculas)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
            <p>No hay películas registradas.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Película
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detalles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($peliculas as $pelicula): ?>
                        <tr>
                            <!-- Película -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-20 w-16">
                                        <?php if (!empty($pelicula['imagen_ruta'])): ?>
                                            <?php 
                                                $rutaImagenUrl = str_replace('peliculas/', 'Peliculas/', $pelicula['imagen_ruta']);
                                            ?>
                                            <img class="h-20 w-16 object-cover rounded" 
                                                 src="<?= APP_URL . '/' . htmlspecialchars($rutaImagenUrl) ?>" 
                                                 alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
                                        <?php else: ?>
                                            <div class="h-20 w-16 bg-gray-300 rounded flex items-center justify-center">
                                                <i class="fas fa-film text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($pelicula['titulo']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars($pelicula['director']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Detalles -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <p><strong>Género:</strong> <?= htmlspecialchars($pelicula['genero']) ?></p>
                                    <p><strong>Duración:</strong> <?= $pelicula['duracion'] ?> minutos</p>
                                    <p><strong>Clasificación:</strong> <?= htmlspecialchars($pelicula['clasificacion']) ?></p>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $pelicula['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $pelicula['estado'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?= APP_URL ?>/pelicula/editar/<?= $pelicula['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/pelicula/cambiarEstado/<?= $pelicula['id'] ?>" 
                                       class="text-yellow-600 hover:text-yellow-900"
                                       onclick="return confirm('¿Estás seguro de cambiar el estado de esta película?')">
                                        <i class="fas fa-toggle-on"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/pelicula/eliminar/<?= $pelicula['id'] ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('¿Estás seguro de eliminar esta película?\n\nNota: No se podrá eliminar si tiene funciones asociadas.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
