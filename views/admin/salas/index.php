<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Salas</h1>
        <a href="<?= APP_URL ?>/sala/crear" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Nueva Sala
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

    <!-- Tabla de salas -->
    <?php if (empty($salas)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
            <p>No hay salas registradas.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Capacidad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Funciones Activas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($salas as $sala): ?>
                        <tr>
                            <!-- Nombre -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($sala['nombre']) ?>
                                </div>
                            </td>

                            <!-- Capacidad -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= $sala['capacidad'] ?> asientos
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $sala['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $sala['estado'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>

                            <!-- Funciones Activas -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php 
                                    $funcionesActivas = isset($sala['funciones_activas']) ? $sala['funciones_activas'] : 0;
                                    echo $funcionesActivas . ' función' . ($funcionesActivas !== 1 ? 'es' : '');
                                    ?>
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?= APP_URL ?>/sala/editar/<?= $sala['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/sala/cambiarEstado/<?= $sala['id'] ?>" 
                                       class="text-yellow-600 hover:text-yellow-900"
                                       onclick="return confirm('¿Estás seguro de cambiar el estado de esta sala?')"
                                       title="Cambiar Estado">
                                        <i class="fas fa-toggle-on"></i>
                                    </a>
                                    <?php if (!isset($sala['funciones_activas']) || $sala['funciones_activas'] == 0): ?>
                                        <a href="<?= APP_URL ?>/sala/eliminar/<?= $sala['id'] ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('¿Estás seguro de eliminar esta sala?')"
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Nota informativa -->
    <div class="mt-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Las salas con funciones activas no pueden ser eliminadas. 
                        Debes cancelar o finalizar todas las funciones asociadas antes de eliminar una sala.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
