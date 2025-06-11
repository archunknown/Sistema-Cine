<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mis Compras</h1>

    <?php if (empty($compras)): ?>
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <i class="fas fa-ticket-alt text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Aún no has realizado ninguna compra.</p>
            <a href="<?= APP_URL ?>" class="inline-block mt-4 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                Ver Cartelera
            </a>
        </div>
    <?php else: ?>
        <div class="grid gap-6">
            <?php foreach ($compras as $compra): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    <?= htmlspecialchars($compra['pelicula_titulo']) ?>
                                </h3>
                                <p class="text-gray-600">
                                    Función: <?= date('d/m/Y', strtotime($compra['funcion_fecha'])) ?> 
                                    a las <?= date('H:i', strtotime($compra['funcion_hora'])) ?>
                                </p>
                            </div>
                            <span class="px-4 py-2 rounded-full <?= $compra['estado'] === 'completada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= ucfirst($compra['estado']) ?>
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600">Cantidad de Boletas:</p>
                                    <p class="font-semibold"><?= $compra['cantidad_boletas'] ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Total:</p>
                                    <p class="font-semibold">S/ <?= number_format($compra['total'], 2) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($compra['boletas'])): ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="font-semibold mb-2">Asientos:</h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($compra['boletas'] as $boleta): ?>
                                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                                            <?= htmlspecialchars($boleta['asiento']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($compra['estado'] === 'completada'): ?>
                            <div class="mt-6 flex justify-end">
                                <a href="<?= APP_URL ?>/venta/detalles/<?= $compra['id'] ?>" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-download mr-2"></i>
                                    Descargar Boletas
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
