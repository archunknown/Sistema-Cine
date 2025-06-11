<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Promociones Especiales</h1>
            <p class="text-xl opacity-90">Descubre nuestras increíbles ofertas y disfruta más del cine</p>
        </div>
    </div>
</section>

<!-- Promociones -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($promociones as $promocion): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 aspect-w-1 aspect-h-1 relative">
                    <div class="p-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">
                                    <?= htmlspecialchars($promocion['titulo']) ?>
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    <?= htmlspecialchars($promocion['descripcion']) ?>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <?php if (strpos(strtolower($promocion['titulo']), '2x1') !== false): ?>
                                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                                        2x1
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-ticket-alt text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-600 mb-2">Términos y condiciones:</h4>
                            <p class="text-sm text-gray-500">
                                <?= htmlspecialchars($promocion['terminos']) ?>
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="<?= APP_URL ?>/venta/comprar" 
                               class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i>
                                Comprar Entradas
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Información Adicional -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Información Importante</h2>
            
            <div class="bg-white rounded-lg shadow-lg p-6">
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <p class="text-gray-700">Las promociones no son acumulables con otras ofertas o descuentos.</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-calendar-alt text-blue-600 mt-1 mr-3"></i>
                        <p class="text-gray-700">Válido solo en los días especificados para cada promoción.</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-blue-600 mt-1 mr-3"></i>
                        <p class="text-gray-700">Sujeto a disponibilidad de asientos en la sala.</p>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-id-card text-blue-600 mt-1 mr-3"></i>
                        <p class="text-gray-700">Es necesario presentar identificación para validar descuentos especiales.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">¡No te pierdas ninguna promoción!</h2>
            <p class="text-gray-600 mb-8">Suscríbete a nuestro newsletter y recibe las últimas promociones directamente en tu correo.</p>
            
            <form class="flex flex-col sm:flex-row gap-4 justify-center">
                <input type="email" 
                       placeholder="Tu correo electrónico" 
                       class="flex-1 px-6 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent max-w-md">
                <button type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Suscribirse
                </button>
            </form>
        </div>
    </div>
</section>
