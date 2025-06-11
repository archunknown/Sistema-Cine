<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestión de Ventas</h1>
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

    <!-- Tabla de ventas -->
    <?php if (empty($ventas)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
            <p>No hay ventas registradas.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Código
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Película
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Función
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Boletas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
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
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <!-- Código -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($venta['created_at'])) ?>
                                </div>
                            </td>

                            <!-- Cliente -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($venta['cliente_nombre']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    DNI: <?= htmlspecialchars($venta['cliente_dni']) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= htmlspecialchars($venta['cliente_email']) ?>
                                </div>
                            </td>

                            <!-- Película -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($venta['pelicula_titulo']) ?>
                                </div>
                            </td>

                            <!-- Función -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($venta['funcion_fecha'])) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= date('H:i', strtotime($venta['funcion_hora'])) ?> hrs
                                </div>
                                <div class="text-sm text-gray-500">
                                    Sala: <?= htmlspecialchars($venta['sala_nombre']) ?>
                                </div>
                            </td>

                            <!-- Boletas -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= $venta['cantidad_boletas'] ?> boletas
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    S/ <?= number_format($venta['total'], 2) ?>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $venta['estado'] === 'completada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($venta['estado']) ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="<?= APP_URL ?>/venta/detalles/<?= $venta['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($venta['estado'] === 'completada'): ?>
                                        <a href="<?= APP_URL ?>/venta/cancelar/<?= $venta['id'] ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('¿Estás seguro de cancelar esta venta?')">
                                            <i class="fas fa-times"></i>
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
</div>
