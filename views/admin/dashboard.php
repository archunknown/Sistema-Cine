<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Panel de Administración</h1>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Películas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Películas</p>
                    <h3 class="text-3xl font-bold"><?= $total_peliculas ?></h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-film text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Funciones -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Funciones</p>
                    <h3 class="text-3xl font-bold"><?= $total_funciones ?></h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Ventas</p>
                    <h3 class="text-3xl font-bold"><?= $total_ventas ?></h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Salas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Salas</p>
                    <h3 class="text-3xl font-bold"><?= $total_salas ?></h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-door-open text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="<?= APP_URL ?>/admin/peliculas" class="bg-blue-600 text-white rounded-lg p-6 hover:bg-blue-700 transition-colors">
            <i class="fas fa-film text-3xl mb-4"></i>
            <h3 class="text-xl font-bold">Gestionar Películas</h3>
        </a>

        <a href="<?= APP_URL ?>/admin/funciones" class="bg-green-600 text-white rounded-lg p-6 hover:bg-green-700 transition-colors">
            <i class="fas fa-calendar text-3xl mb-4"></i>
            <h3 class="text-xl font-bold">Gestionar Funciones</h3>
        </a>

        <a href="<?= APP_URL ?>/admin/ventas" class="bg-purple-600 text-white rounded-lg p-6 hover:bg-purple-700 transition-colors">
            <i class="fas fa-ticket-alt text-3xl mb-4"></i>
            <h3 class="text-xl font-bold">Gestionar Ventas</h3>
        </a>

        <a href="<?= APP_URL ?>/admin/salas" class="bg-yellow-600 text-white rounded-lg p-6 hover:bg-yellow-700 transition-colors">
            <i class="fas fa-door-open text-3xl mb-4"></i>
            <h3 class="text-xl font-bold">Gestionar Salas</h3>
        </a>
    </div>

    <!-- Estadísticas adicionales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Ventas de hoy -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Ventas de Hoy</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total</p>
                    <h4 class="text-2xl font-bold">S/ <?= number_format($ventas_hoy, 2) ?></h4>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Ingresos del mes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Ingresos del Mes</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total</p>
                    <h4 class="text-2xl font-bold">S/ <?= number_format($ingresos_mes, 2) ?></h4>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
