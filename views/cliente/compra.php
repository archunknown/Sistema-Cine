<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Detalles de la película y función -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="md:flex">
            <!-- Imagen de la película -->
            <div class="md:w-1/3 w-full max-w-[400px] aspect-w-1 aspect-h-1 relative">
                <?php if ($funcion['pelicula_imagen']): ?>
                    <?php 
                        $rutaImagenUrl = str_replace('peliculas/', 'Peliculas/', $funcion['pelicula_imagen']);
                    ?>
                    <img src="<?= APP_URL . '/' . htmlspecialchars($rutaImagenUrl) ?>" 
                         alt="<?= htmlspecialchars($funcion['pelicula_titulo']) ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                <?php else: ?>
                    <div class="absolute inset-0 bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-film text-gray-400 text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Información -->
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    <?= htmlspecialchars($funcion['pelicula_titulo']) ?>
                </h1>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-calendar mr-2"></i>
                            <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($funcion['fecha'])) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            <strong>Hora:</strong> <?= date('H:i', strtotime($funcion['hora'])) ?> hrs
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-door-open mr-2"></i>
                            <strong>Sala:</strong> <?= htmlspecialchars($funcion['sala_nombre']) ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            <strong>Precio:</strong> S/ <?= number_format($funcion['precio'], 2) ?>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-2">Sinopsis</h2>
                    <p class="text-gray-700">
                        <?= nl2br(htmlspecialchars($funcion['pelicula_sinopsis'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de error/éxito -->
    <?php $mensajes = $this->getMensajes(); ?>
    <?php if ($mensajes['error']): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $mensajes['error'] ?></span>
        </div>
    <?php endif; ?>

    <!-- Selección de asientos -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Selección de Asientos</h2>

        <!-- Leyenda -->
        <div class="flex items-center space-x-6 mb-6">
            <div class="flex items-center">
                <div class="w-6 h-6 bg-gray-200 rounded mr-2"></div>
                <span>Disponible</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 bg-blue-500 rounded mr-2"></div>
                <span>Seleccionado</span>
            </div>
            <div class="flex items-center">
                <div class="w-6 h-6 bg-red-500 rounded mr-2"></div>
                <span>Ocupado</span>
            </div>
        </div>

        <!-- Pantalla -->
        <div class="w-full bg-gray-800 text-white text-center py-2 mb-8 rounded">
            PANTALLA
        </div>

        <!-- Grid de asientos -->
        <?php
        // Calcular número de filas y columnas basado en la capacidad
        $capacidad = $funcion['sala_capacidad'];
        $columnas = min(10, ceil(sqrt($capacidad))); // Máximo 10 columnas
        $filas = ceil($capacidad / $columnas);
        $letrasFilas = range('A', 'Z');
        ?>
        <div class="grid gap-2 mb-8" id="asientos-grid" style="grid-template-columns: repeat(<?= $columnas ?>, minmax(0, 1fr));">
            <?php
            $asientoActual = 1;
            for ($i = 0; $i < $filas && $asientoActual <= $capacidad; $i++) {
                for ($j = 1; $j <= $columnas && $asientoActual <= $capacidad; $j++) {
                    $asiento = $letrasFilas[$i] . $j;
                    $ocupado = in_array($asiento, $asientosOcupados);
                    ?>
                    <button type="button"
                            class="w-full aspect-square rounded flex items-center justify-center text-sm font-medium transition-colors
                                   <?= $ocupado ? 'bg-red-500 text-white cursor-not-allowed' : 'bg-gray-200 hover:bg-blue-500 hover:text-white' ?>"
                            data-asiento="<?= $asiento ?>"
                            <?= $ocupado ? 'disabled' : '' ?>>
                        <?= $asiento ?>
                    </button>
                    <?php
                    $asientoActual++;
                }
            }
            ?>
        </div>

        <!-- Formulario de compra -->
        <form action="<?= APP_URL ?>/venta/procesar" method="POST" id="formulario-compra" class="space-y-6">
            <input type="hidden" name="funcion_id" value="<?= $funcion['id'] ?>">
            <input type="hidden" name="asientos" id="asientos-seleccionados">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="cliente_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="cliente_nombre" 
                           id="cliente_nombre" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- DNI -->
                <div>
                    <label for="cliente_dni" class="block text-sm font-medium text-gray-700 mb-2">
                        DNI <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="cliente_dni" 
                           id="cliente_dni" 
                           required 
                           pattern="[0-9]{8}"
                           maxlength="8"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="cliente_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico <span class="text-red-600">*</span>
                </label>
                <input type="email" 
                       name="cliente_email" 
                       id="cliente_email" 
                       required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Resumen -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-bold mb-2">Resumen de Compra</h3>
                <div class="space-y-2 mb-4">
                    <p>Asientos seleccionados: <span id="resumen-asientos">Ninguno</span></p>
                    <p>Cantidad: <span id="resumen-cantidad">0</span></p>
                    <p>Precio unitario: S/ <?= number_format($funcion['precio'], 2) ?></p>
                    <p class="text-lg font-bold">Total: S/ <span id="resumen-total">0.00</span></p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="<?= APP_URL ?>/pelicula/<?= $funcion['pelicula_id'] ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        id="btn-comprar"
                        disabled
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Comprar Entradas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const asientosGrid = document.getElementById('asientos-grid');
    const asientosSeleccionados = new Set();
    const precioUnitario = <?= $funcion['precio'] ?>;
    const btnComprar = document.getElementById('btn-comprar');
    const inputAsientos = document.getElementById('asientos-seleccionados');
    
    // Actualizar resumen
    function actualizarResumen() {
        const cantidad = asientosSeleccionados.size;
        const total = cantidad * precioUnitario;
        
        document.getElementById('resumen-asientos').textContent = 
            cantidad > 0 ? Array.from(asientosSeleccionados).join(', ') : 'Ninguno';
        document.getElementById('resumen-cantidad').textContent = cantidad;
        document.getElementById('resumen-total').textContent = total.toFixed(2);
        
        // Actualizar input hidden y estado del botón
        inputAsientos.value = Array.from(asientosSeleccionados).join(',');
        btnComprar.disabled = cantidad === 0;
    }

    // Manejar clic en asientos
    asientosGrid.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn || btn.disabled) return;

        const asiento = btn.dataset.asiento;
        
        if (asientosSeleccionados.has(asiento)) {
            asientosSeleccionados.delete(asiento);
            btn.classList.remove('bg-blue-500', 'text-white');
            btn.classList.add('bg-gray-200');
        } else {
            asientosSeleccionados.add(asiento);
            btn.classList.remove('bg-gray-200');
            btn.classList.add('bg-blue-500', 'text-white');
        }

        actualizarResumen();
    });

    // Validar formulario antes de enviar
    document.getElementById('formulario-compra').addEventListener('submit', function(e) {
        if (asientosSeleccionados.size === 0) {
            e.preventDefault();
            alert('Por favor, selecciona al menos un asiento.');
        }
    });
});
</script>
