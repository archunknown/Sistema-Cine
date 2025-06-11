<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Detalles de Venta #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?>
        </h1>
        <a href="<?= APP_URL ?>/venta" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
    </div>

    <!-- Estado de la venta -->
    <div class="mb-8">
        <span class="px-4 py-2 inline-flex text-lg leading-5 font-semibold rounded-full 
            <?= $venta['estado'] === 'completada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <?= ucfirst($venta['estado']) ?>
        </span>
        <span class="ml-4 text-gray-600">
            Fecha: <?= date('d/m/Y H:i', strtotime($venta['created_at'])) ?>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Información del cliente y la venta -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Información del Cliente</h2>
            <div class="space-y-3">
                <p>
                    <strong class="text-gray-700">Nombre:</strong><br>
                    <?= htmlspecialchars($venta['cliente_nombre']) ?>
                </p>
                <p>
                    <strong class="text-gray-700">DNI:</strong><br>
                    <?= htmlspecialchars($venta['cliente_dni']) ?>
                </p>
                <p>
                    <strong class="text-gray-700">Email:</strong><br>
                    <?= htmlspecialchars($venta['cliente_email']) ?>
                </p>
            </div>
        </div>

        <!-- Información de la función -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Información de la Función</h2>
            <div class="space-y-3">
                <p>
                    <strong class="text-gray-700">Película:</strong><br>
                    <?= htmlspecialchars($venta['pelicula_titulo']) ?>
                </p>
                <p>
                    <strong class="text-gray-700">Sala:</strong><br>
                    <?= htmlspecialchars($venta['sala_nombre']) ?>
                </p>
                <p>
                    <strong class="text-gray-700">Fecha y Hora:</strong><br>
                    <?= date('d/m/Y', strtotime($venta['funcion_fecha'])) ?> 
                    <?= date('H:i', strtotime($venta['funcion_hora'])) ?> hrs
                </p>
            </div>
        </div>
    </div>

    <!-- Detalles de las boletas -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Boletas</h2>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-700">
                            <strong>Asientos:</strong> <?= $venta['asientos'] ?>
                        </p>
                        <p class="text-gray-700">
                            <strong>Cantidad de boletas:</strong> 
                            <?= substr_count($venta['asientos'], ',') + 1 ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-700">
                            <strong>Precio por boleta:</strong> 
                            S/ <?= number_format($venta['funcion_precio'], 2) ?>
                        </p>
                        <p class="text-xl font-bold text-gray-900">
                            <strong>Total:</strong> 
                            S/ <?= number_format($venta['total'], 2) ?>
                        </p>
                    </div>
                </div>

                <!-- Lista de boletas individuales -->
                <div class="space-y-4">
                    <?php foreach (explode(',', $venta['asientos']) as $asiento): ?>
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">Asiento: <?= trim($asiento) ?></p>
                                    <p class="text-sm text-gray-600">
                                        Código: <?= substr(md5($venta['id'] . $asiento), 0, 8) ?>
                                    </p>
                                </div>
                                <div>
                                    <span class="px-2 py-1 text-sm rounded-full 
                                        <?= $venta['estado'] === 'completada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($venta['estado']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="mt-8 flex justify-end space-x-4">
        <button onclick="window.print()" 
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-print mr-2"></i>
            Imprimir Detalles
        </button>
        <?php if ($venta['estado'] === 'completada'): ?>
            <a href="<?= APP_URL ?>/venta/cancelar/<?= $venta['id'] ?>" 
               class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors"
               onclick="return confirm('¿Estás seguro de cancelar esta venta? Esta acción no se puede deshacer.')">
                <i class="fas fa-times mr-2"></i>
                Cancelar Venta
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Estilos para impresión -->
<style media="print">
    nav, footer, .no-print {
        display: none !important;
    }
    body {
        background-color: white;
    }
    .container {
        max-width: none;
        padding: 0;
        margin: 0;
    }
    @page {
        margin: 1cm;
    }
</style>
