<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Detalles de la película -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Imagen de la película -->
            <div class="md:w-1/3 w-full max-w-[400px] aspect-w-1 aspect-h-1 relative">
                <?php if (!empty($pelicula['imagen_ruta'])): ?>
                    <?php 
                        $rutaImagenUrl = str_replace('peliculas/', 'Peliculas/', $pelicula['imagen_ruta']);
                    ?>
                    <img src="<?= APP_URL . '/' . htmlspecialchars($rutaImagenUrl) ?>" 
                         alt="<?= htmlspecialchars($pelicula['titulo']) ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                <?php else: ?>
                    <div class="absolute inset-0 bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-film text-gray-400 text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Información de la película -->
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold mb-4">
                    <?= htmlspecialchars($pelicula['titulo']) ?>
                </h1>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-film mr-2"></i>
                            <strong>Género:</strong> <?= htmlspecialchars($pelicula['genero']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <strong>Duración:</strong> <?= $pelicula['duracion'] ?> minutos
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-star mr-2"></i>
                            <strong>Clasificación:</strong> <?= htmlspecialchars($pelicula['clasificacion']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <strong>Director:</strong> <?= htmlspecialchars($pelicula['director']) ?>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-2">Sinopsis</h2>
                    <p class="text-gray-700">
                        <?= nl2br(htmlspecialchars($pelicula['sinopsis'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funciones disponibles -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-6">Funciones Disponibles</h2>
        
        <?php if (empty($funciones)): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <p>No hay funciones disponibles para esta película.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($funciones as $funcion): ?>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="mb-4">
                            <p class="text-lg font-bold">
                                <?= htmlspecialchars($funcion['sala_nombre']) ?>
                            </p>
                            <p class="text-gray-600">
                                <i class="far fa-calendar mr-2"></i>
                                <?= date('d/m/Y', strtotime($funcion['fecha'])) ?>
                            </p>
                            <p class="text-gray-600">
                                <i class="far fa-clock mr-2"></i>
                                <?= date('H:i', strtotime($funcion['hora'])) ?>
                            </p>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-green-600">
                                S/ <?= number_format($funcion['precio'], 2) ?>
                            </span>
                            <?php if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin'): ?>
                                <a href="<?= APP_URL ?>/venta/comprar/<?= $funcion['id'] ?>" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                    Comprar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Botón para volver -->
    <div class="mt-8">
        <button onclick="history.back()" 
                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </button>
    </div>
</div>
