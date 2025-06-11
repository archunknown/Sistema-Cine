<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white mt-8">
        <!-- Newsletter Section -->
        <div class="border-b border-gray-700">
            <div class="container mx-auto px-4 py-12">
                <div class="max-w-3xl mx-auto text-center">
                    <h3 class="text-2xl font-bold mb-4">¡No te pierdas nuestros estrenos!</h3>
                    <p class="text-gray-400 mb-6">Suscríbete a nuestro newsletter y recibe las últimas novedades.</p>
                    <form id="newsletterForm" class="flex flex-col sm:flex-row gap-4 justify-center">
                        <input type="email" 
                               id="emailInput"
                               placeholder="Tu correo electrónico" 
                               class="px-6 py-3 rounded-full bg-gray-800 border border-gray-700 focus:border-red-500 focus:outline-none flex-1 max-w-md">
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-full transition-colors transform hover:scale-105">
                            Suscribirse
                        </button>
                    </form>

                    <!-- Modal de Suscripción -->
                    <div id="subscriptionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-lg p-8 max-w-md mx-4 relative transform transition-all">
                            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="text-center">
                                <i class="fas fa-envelope-open-text text-5xl text-red-600 mb-4"></i>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Gracias por suscribirte!</h3>
                                <p class="text-gray-600 mb-6">Te mantendremos informado sobre nuestros últimos estrenos y promociones.</p>
                                <button onclick="closeModal()" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full transition-colors">
                                    Aceptar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Logo y descripción -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <i class="fas fa-film text-red-500 text-3xl"></i>
                        <span class="text-2xl font-bold"><?= APP_NAME ?></span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Tu destino cinematográfico preferido, donde cada película se convierte en una experiencia inolvidable.
                    </p>
                    <div class="flex space-x-4">
                        <span class="text-gray-400 cursor-not-allowed">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </span>
                        <span class="text-gray-400 cursor-not-allowed">
                            <i class="fab fa-twitter text-xl"></i>
                        </span>
                        <span class="text-gray-400 cursor-not-allowed">
                            <i class="fab fa-instagram text-xl"></i>
                        </span>
                        <span class="text-gray-400 cursor-not-allowed">
                            <i class="fab fa-youtube text-xl"></i>
                        </span>
                    </div>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Enlaces Rápidos</h4>
                    <ul class="space-y-4">
                        <li>
                            <span class="text-gray-400 flex items-center cursor-not-allowed">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Cartelera
                            </span>
                        </li>
                        <li>
                            <span class="text-gray-400 flex items-center cursor-not-allowed">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Promociones
                            </span>
                        </li>
                        <li>
                            <span class="text-gray-400 flex items-center cursor-not-allowed">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Snacks & Bebidas
                            </span>
                        </li>
                        <li>
                            <span class="text-gray-400 flex items-center cursor-not-allowed">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Nuestras Salas
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Información de contacto -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Contacto</h4>
                    <ul class="space-y-4">
                        <li class="flex items-center text-gray-400">
                            <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span>Av. Principal 123, Lima</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <span>(01) 123-4567</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>contacto@cinemvc.com</span>
                        </li>
                    </ul>
                </div>

                <!-- Horarios -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Horarios</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between text-gray-400">
                            <span>Lunes - Viernes</span>
                            <span>12:00 - 23:00</span>
                        </li>
                        <li class="flex justify-between text-gray-400">
                            <span>Sábados</span>
                            <span>11:00 - 23:00</span>
                        </li>
                        <li class="flex justify-between text-gray-400">
                            <span>Domingos</span>
                            <span>11:00 - 22:00</span>
                        </li>
                        <li class="flex justify-between text-gray-400">
                            <span>Feriados</span>
                            <span>12:00 - 22:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Línea divisoria -->
            <hr class="border-gray-700 my-8">

            <!-- Copyright y enlaces legales -->
            <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                <div class="mb-4 md:mb-0">
                    &copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="text-gray-400 cursor-not-allowed">
                        Términos y Condiciones
                    </span>
                    <span class="hidden md:inline text-gray-400">|</span>
                    <span class="text-gray-400 cursor-not-allowed">
                        Política de Privacidad
                    </span>
                    <span class="hidden md:inline text-gray-400">|</span>
                    <span class="text-gray-400 cursor-not-allowed">
                        Preguntas Frecuentes
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Panel de Debug -->
    <?php
    if (class_exists('Debug')) {
        Debug::render();
    }
    ?>

    <!-- Scripts -->
    <script>
        // Animación suave para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Manejo del formulario de suscripción
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('emailInput').value;
            
            if (email) {
                // Aquí podrías agregar una llamada AJAX para procesar el email
                document.getElementById('subscriptionModal').style.display = 'flex';
                this.reset(); // Limpiar el formulario
            }
        });

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('subscriptionModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera de él
        document.getElementById('subscriptionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
