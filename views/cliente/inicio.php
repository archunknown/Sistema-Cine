<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<!-- Hero Section con gradiente -->
<section class="relative min-h-[600px] flex items-center justify-center mb-16 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900"></div>
    <div class="absolute inset-0">
        <div class="absolute inset-0 opacity-20">
            <!-- Patrón de fondo animado -->
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
    </div>

    <!-- Contenido del Hero -->
    <div class="container mx-auto px-4 relative z-10 text-white">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                La Mejor Experiencia en Cine
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-300">
                Disfruta de las mejores películas en la mejor calidad y el mayor confort
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#peliculas"
                    class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-full font-semibold transition-all transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-film mr-2"></i>
                    Ver Cartelera
                </a>
                <a href="#promociones"
                    class="bg-transparent border-2 border-white hover:bg-white hover:text-gray-900 text-white px-8 py-3 rounded-full font-semibold transition-all flex items-center justify-center">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Ver Promociones
                </a>
            </div>
        </div>
    </div>

    <!-- Decoración de fondo -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
</section>

<!-- Estadísticas -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center p-6 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 shadow-lg transform hover:scale-105 transition-all">
                <i class="fas fa-film text-4xl text-red-600 mb-4"></i>
                <div class="text-3xl font-bold text-gray-800 mb-2">15+</div>
                <div class="text-gray-600">Salas Premium</div>
            </div>
            <div class="text-center p-6 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 shadow-lg transform hover:scale-105 transition-all">
                <i class="fas fa-chair text-4xl text-red-600 mb-4"></i>
                <div class="text-3xl font-bold text-gray-800 mb-2">2000+</div>
                <div class="text-gray-600">Asientos Confortables</div>
            </div>
            <div class="text-center p-6 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 shadow-lg transform hover:scale-105 transition-all">
                <i class="fas fa-star text-4xl text-red-600 mb-4"></i>
                <div class="text-3xl font-bold text-gray-800 mb-2">4.8</div>
                <div class="text-gray-600">Calificación Promedio</div>
            </div>
            <div class="text-center p-6 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 shadow-lg transform hover:scale-105 transition-all">
                <i class="fas fa-users text-4xl text-red-600 mb-4"></i>
                <div class="text-3xl font-bold text-gray-800 mb-2">50K+</div>
                <div class="text-gray-600">Clientes Satisfechos</div>
            </div>
        </div>
    </div>
</section>

<!-- Cartelera -->
<section id="peliculas" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Películas en Cartelera</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Disfruta de los últimos estrenos en la mejor calidad de imagen y sonido
            </p>
        </div>

        <?php if (empty($peliculas)): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded max-w-2xl mx-auto">
                <p class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    No hay películas disponibles en este momento.
                </p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($peliculas as $pelicula): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 group">
                        <!-- Imagen de la película -->
                        <div class="relative h-[400px] overflow-hidden">
                            <?php if ($pelicula['imagen_ruta']): ?>
                                <img src="<?= APP_URL . '/' . $pelicula['imagen_ruta'] ?>"
                                    alt="<?= htmlspecialchars($pelicula['titulo']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                    onerror="this.src='<?= APP_URL ?>/public/img/no-image.png'; this.onerror=null;">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-film text-gray-400 text-5xl"></i>
                                </div>
                            <?php endif; ?>
                            <!-- Overlay con clasificación -->
                            <div class="absolute top-4 right-4 bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm">
                                <?= htmlspecialchars($pelicula['clasificacion']) ?>
                            </div>
                        </div>

                        <!-- Resto del contenido de la tarjeta -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-red-600 transition-colors">
                                <?= htmlspecialchars($pelicula['titulo']) ?>
                            </h3>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                    <i class="fas fa-film mr-2"></i><?= htmlspecialchars($pelicula['genero']) ?>
                                </span>
                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                    <i class="fas fa-clock mr-2"></i><?= $pelicula['duracion'] ?> min
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4 line-clamp-2">
                                <?= htmlspecialchars($pelicula['sinopsis']) ?>
                            </p>

                            <!-- Botones de acción -->
                            <div class="flex">
                                <a href="<?= APP_URL ?>/home/pelicula/<?= $pelicula['id'] ?>"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-3 rounded-lg transition-colors transform hover:scale-105">
                                    <i class="fas fa-info-circle mr-2"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Promociones -->
<section id="promociones" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Promociones Especiales</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Aprovecha nuestras increíbles ofertas y disfruta más por menos
            </p>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-gray-100 rounded-xl p-8 text-center">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ticket-alt text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Por ahora no hay promociones disponibles</h3>
                <p class="text-gray-600">
                    En este momento no contamos con promociones activas. ¡Vuelve pronto para descubrir nuestras increíbles ofertas!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Característica 1 -->
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                    <i class="fas fa-ticket-alt text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Reserva Fácil</h3>
                <p class="text-gray-600">
                    Compra tus entradas en línea de manera rápida y segura.
                </p>
            </div>

            <!-- Característica 2 -->
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                    <i class="fas fa-couch text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Máximo Confort</h3>
                <p class="text-gray-600">
                    Asientos reclinables y espaciosos para tu comodidad.
                </p>
            </div>

            <!-- Característica 3 -->
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                    <i class="fas fa-film text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Última Tecnología</h3>
                <p class="text-gray-600">
                    Proyección 4K y sonido envolvente para una experiencia inmersiva.
                </p>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
</style>