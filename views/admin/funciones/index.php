<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Funciones</h1>
        <a href="<?= APP_URL ?>/funcion/crear" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Nueva Función
        </a>
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

    <!-- Tabla de funciones -->
    <?php if (empty($funciones)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
            <p>No hay funciones registradas.</p>
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
                            Sala
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio
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
                    <?php foreach ($funciones as $funcion): ?>
                        <tr>
                            <!-- Película -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($funcion['pelicula_titulo']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Duración: <?= $funcion['pelicula_duracion'] ?> min
                                </div>
                            </td>

                            <!-- Sala -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($funcion['sala_nombre']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Capacidad: <?= $funcion['sala_capacidad'] ?> personas
                                </div>
                            </td>

                            <!-- Fecha y Hora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($funcion['fecha'])) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= date('H:i', strtotime($funcion['hora'])) ?> hrs
                                </div>
                            </td>

                            <!-- Precio -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    S/ <?= number_format($funcion['precio'], 2) ?>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $funcion['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $funcion['estado'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?= APP_URL ?>/funcion/editar/<?= $funcion['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/funcion/cambiarEstado/<?= $funcion['id'] ?>" 
                                       class="text-yellow-600 hover:text-yellow-900"
                                       onclick="return confirm('¿Estás seguro de cambiar el estado de esta función?')">
                                        <i class="fas fa-toggle-on"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/funcion/eliminar/<?= $funcion['id'] ?>" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('¿Estás seguro de eliminar esta función?')">
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
