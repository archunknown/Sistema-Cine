<?php
// Prevenir acceso directo al archivo
if (!defined('BASE_PATH')) {
    exit('No se permite el acceso directo al script');
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Sala</h1>
            <a href="<?= APP_URL ?>/sala" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>

        <!-- Mensajes de error/éxito -->
        <?php $mensajes = $this->getMensajes(); ?>
        <?php if ($mensajes['error']): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $mensajes['error'] ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="<?= APP_URL ?>/sala/editar/<?= $sala['id'] ?>" method="POST" class="bg-white rounded-lg shadow-lg p-6">
            <!-- Estado actual -->
            <div class="mb-6">
                <div class="flex items-center">
                    <span class="mr-2 text-sm text-gray-700">Estado actual:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?= $sala['estado'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $sala['estado'] ? 'Activa' : 'Inactiva' ?>
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Puedes cambiar el estado de la sala desde la lista de salas.
                </p>
            </div>

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre de la Sala <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       name="nombre" 
                       id="nombre" 
                       required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       value="<?= htmlspecialchars($sala['nombre']) ?>"
                       placeholder="Ej: Sala 1">
                <p class="mt-1 text-sm text-gray-500">
                    El nombre debe ser único y descriptivo.
                </p>
            </div>

            <!-- Capacidad -->
            <div class="mb-6">
                <label for="capacidad" class="block text-sm font-medium text-gray-700 mb-2">
                    Capacidad (Número de Asientos) <span class="text-red-600">*</span>
                </label>
                <input type="number" 
                       name="capacidad" 
                       id="capacidad" 
                       required 
                       min="1"
                       max="500"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                              <?= isset($sala['tiene_funciones']) && $sala['tiene_funciones'] ? 'bg-gray-100' : '' ?>"
                       value="<?= htmlspecialchars($sala['capacidad']) ?>"
                       <?= isset($sala['tiene_funciones']) && $sala['tiene_funciones'] ? 'readonly' : '' ?>
                       placeholder="Ej: 80">
                <?php if (isset($sala['tiene_funciones']) && $sala['tiene_funciones']): ?>
                    <p class="mt-1 text-sm text-red-500">
                        La capacidad no puede ser modificada porque la sala tiene funciones programadas.
                    </p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-gray-500">
                        Ingresa el número total de asientos disponibles en la sala.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Distribución de asientos (Vista previa) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Vista Previa de la Distribución
                </label>
                <div class="border border-gray-300 rounded-lg p-4">
                    <!-- Pantalla -->
                    <div class="w-full bg-gray-800 text-white text-center py-2 mb-4 rounded">
                        PANTALLA
                    </div>

                    <!-- Ejemplo de distribución -->
                    <div id="preview-grid" class="grid gap-1 justify-center">
                        <!-- Se llenará dinámicamente con JavaScript -->
                    </div>

                    <p class="mt-4 text-sm text-gray-500 text-center">
                        Esta es una vista previa aproximada de cómo se verán los asientos en la sala.
                    </p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="<?= APP_URL ?>/sala" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Cambios
                </button>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="mt-6 space-y-4">
            <?php if (isset($sala['funciones_activas']) && $sala['funciones_activas'] > 0): ?>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Esta sala tiene <?= $sala['funciones_activas'] ?> función(es) programada(s).
                                Algunas opciones de edición están limitadas.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Los cambios en la configuración de la sala pueden afectar a las funciones programadas.
                            Asegúrate de revisar cuidadosamente antes de guardar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const capacidadInput = document.getElementById('capacidad');
    const previewGrid = document.getElementById('preview-grid');

    function actualizarVistaPrevia() {
        const capacidad = parseInt(capacidadInput.value) || 0;
        
        // Limpiar vista previa
        previewGrid.innerHTML = '';
        
        if (capacidad > 0) {
            // Calcular número de filas y columnas
            const columnas = Math.min(10, Math.ceil(Math.sqrt(capacidad)));
            const filas = Math.ceil(capacidad / columnas);
            
            // Establecer grid
            previewGrid.style.gridTemplateColumns = `repeat(${columnas}, minmax(0, 1fr))`;
            
            // Crear asientos
            let asientoActual = 1;
            const letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            
            for (let i = 0; i < filas && asientoActual <= capacidad; i++) {
                for (let j = 0; j < columnas && asientoActual <= capacidad; j++) {
                    const asiento = document.createElement('div');
                    asiento.className = 'w-8 h-8 bg-gray-200 rounded flex items-center justify-center text-xs';
                    asiento.textContent = letras[i] + (j + 1);
                    previewGrid.appendChild(asiento);
                    asientoActual++;
                }
            }
        }
    }

    // Actualizar vista previa cuando cambie la capacidad
    capacidadInput.addEventListener('input', actualizarVistaPrevia);
    
    // Actualizar vista previa inicial
    actualizarVistaPrevia();
});
</script>
