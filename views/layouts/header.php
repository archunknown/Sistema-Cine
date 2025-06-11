<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? $titulo . ' - ' . APP_NAME : APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= APP_URL ?>/public/img/icono.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= APP_URL ?>/public/img/icono.png">
    <link rel="shortcut icon" href="<?= APP_URL ?>/public/img/icono.png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .dropdown-animation {
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Barra de navegación -->
    <nav class="bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-xl relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="<?= APP_URL ?>" class="flex items-center space-x-3">
                    <i class="fas fa-film text-red-500 text-2xl"></i>
                    <span class="text-2xl font-bold bg-gradient-to-r from-white to-gray-300 text-transparent bg-clip-text">
                        <?= APP_NAME ?>
                    </span>
                </a>

                <!-- Menú de navegación -->
                <div class="hidden md:flex items-center space-x-8">
                    <?php
                    $currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                    $currentPath = explode('/', $currentPath);
                    $isHome = count($currentPath) <= 1 && empty($currentPath[0]);
                    ?>
                    <?php if ($isHome): ?>
                        <span id="nav-home" class="nav-link text-gray-200 bg-gray-700 px-4 py-2 rounded cursor-default">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </span>
                    <?php else: ?>
                        <a id="nav-home" href="<?= APP_URL ?>" class="nav-link hover:text-gray-200 transition-colors">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </a>
                    <?php endif; ?>

                    <a href="https://wa.link/jspt4d" 
                       target="_blank"
                       class="nav-link hover:text-gray-200 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>Ayuda
                    </a>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-1 nav-link hover:text-gray-200 transition-colors">
                                <i class="fas fa-user-circle mr-2"></i>
                                <span>Mi Cuenta</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 ease-in-out">
                                <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                                    <a href="<?= APP_URL ?>/admin" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Panel Admin
                                    </a>
                                    <a href="<?= APP_URL ?>/perfil" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Mi Perfil
                                    </a>
                                <?php else: ?>
                                    <a href="<?= APP_URL ?>/mis-compras" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-ticket-alt mr-2"></i>Mis Compras
                                    </a>
                                    <a href="<?= APP_URL ?>/perfil" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Mi Perfil
                                    </a>
                                <?php endif; ?>
                                <hr class="my-2 border-gray-200">
                                <a href="<?= APP_URL ?>/auth/logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/login" 
                           class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Menú móvil -->
                <button class="md:hidden text-2xl" id="mobile-menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Menú móvil desplegable -->
            <div class="md:hidden hidden transition-all duration-300 ease-in-out" id="mobile-menu">
                <div class="py-4 space-y-4 border-t border-gray-700">
                    <?php if ($isHome): ?>
                        <span class="block text-gray-200 py-2 cursor-default">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </span>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>" class="block hover:text-gray-200 py-2">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </a>
                    <?php endif; ?>

                    <?php if ($currentPath[0] === 'pelicula'): ?>
                        <span class="block text-gray-200 py-2 cursor-default">
                            <i class="fas fa-film mr-2"></i>Cartelera
                        </span>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/pelicula" class="block hover:text-gray-200 py-2">
                            <i class="fas fa-film mr-2"></i>Cartelera
                        </a>
                    <?php endif; ?>

                    <a href="https://wa.link/jspt4d" 
                       target="_blank"
                       class="block hover:text-gray-200 py-2">
                        <i class="fab fa-whatsapp mr-2"></i>Ayuda
                    </a>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                            <a href="<?= APP_URL ?>/admin" class="block hover:text-gray-200 py-2">
                                <i class="fas fa-cog mr-2"></i>Panel Admin
                            </a>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/mis-compras" class="block hover:text-gray-200 py-2">
                                <i class="fas fa-ticket-alt mr-2"></i>Mis Compras
                            </a>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/perfil" class="block hover:text-gray-200 py-2">
                            <i class="fas fa-user mr-2"></i>Mi Perfil
                        </a>
                        <a href="<?= APP_URL ?>/auth/logout" class="block text-red-400 hover:text-red-300 py-2">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/auth/login" class="block hover:text-gray-200 py-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mensajes de alerta -->
    <div class="fixed top-24 right-4 z-50 max-w-sm w-full">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-lg animate-fade-in-right" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['exito'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg animate-fade-in-right" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span><?= $_SESSION['exito'] ?></span>
                </div>
                <?php unset($_SESSION['exito']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Contenido principal -->
    <main class="flex-grow">

    <script>
        // Toggle menú móvil
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Auto-ocultar mensajes de alerta después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
