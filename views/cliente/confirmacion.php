<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Mensaje de éxito -->
    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-8">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-3xl mr-4"></i>
            <div>
                <h2 class="text-xl font-bold">¡Compra realizada con éxito!</h2>
                <p>Se ha enviado un correo electrónico con los detalles de tu compra.</p>
            </div>
        </div>
    </div>

    <!-- Detalles de la compra -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-4">Detalles de la Compra</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Información del cliente -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información del Cliente</h3>
                    <div class="space-y-2">
                        <p>
                            <strong>Nombre:</strong> 
                            <?= htmlspecialchars($venta['cliente_nombre']) ?>
                        </p>
                        <p>
                            <strong>DNI:</strong> 
                            <?= htmlspecialchars($venta['cliente_dni']) ?>
                        </p>
                        <p>
                            <strong>Email:</strong> 
                            <?= htmlspecialchars($venta['cliente_email']) ?>
                        </p>
                    </div>
                </div>

                <!-- Información de la función -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Información de la Función</h3>
                    <div class="space-y-2">
                        <p>
                            <strong>Película:</strong> 
                            <?= htmlspecialchars($venta['pelicula_titulo']) ?>
                        </p>
                        <p>
                            <strong>Fecha:</strong> 
                            <?= date('d/m/Y', strtotime($venta['funcion_fecha'])) ?>
                        </p>
                        <p>
                            <strong>Hora:</strong> 
                            <?= date('H:i', strtotime($venta['funcion_hora'])) ?> hrs
                        </p>
                        <p>
                            <strong>Sala:</strong> 
                            <?= htmlspecialchars($venta['sala_nombre']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Resumen de la compra -->
            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-lg font-semibold mb-2">Resumen de la Compra</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Asientos:</strong> <?= $venta['asientos'] ?></p>
                        <p><strong>Cantidad de boletas:</strong> <?= substr_count($venta['asientos'], ',') + 1 ?></p>
                    </div>
                    <div class="text-right">
                        <p><strong>Precio por boleta:</strong> S/ <?= number_format($venta['funcion_precio'], 2) ?></p>
                        <p class="text-xl font-bold">
                            <strong>Total pagado:</strong> S/ <?= number_format($venta['total'], 2) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boletas -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold">Tus Boletas</h2>
        <?php foreach (explode(',', $venta['asientos']) as $asiento): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($venta['pelicula_titulo']) ?></h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($venta['funcion_fecha'])) ?></p>
                                    <p><strong>Hora:</strong> <?= date('H:i', strtotime($venta['funcion_hora'])) ?> hrs</p>
                                </div>
                                <div>
                                    <p><strong>Sala:</strong> <?= htmlspecialchars($venta['sala_nombre']) ?></p>
                                    <p><strong>Asiento:</strong> <?= trim($asiento) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Código de Boleta</p>
                            <p class="font-mono"><?= substr(md5($venta['id'] . $asiento), 0, 8) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Botones de acción -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="<?= APP_URL ?>" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-home mr-2"></i>
            Volver al Inicio
        </a>
        <button onclick="window.print()" 
                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-print mr-2"></i>
            Imprimir Boletas
        </button>
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
