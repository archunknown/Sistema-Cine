# Sistema de Cine

## Configuración

El sistema está diseñado para ser portable y funcionar en cualquier entorno sin necesidad de modificaciones manuales. La configuración es automática y se adapta al servidor donde se instale.

### URLs y Rutas

- La URL base de la aplicación (APP_URL) se detecta automáticamente basándose en:
  - Protocolo (http/https)
  - Host y puerto
  - Ruta base del proyecto

No es necesario modificar ninguna configuración de URLs, ya que el sistema:
- Detecta automáticamente la ruta base del proyecto
- Maneja correctamente diferentes puertos (80, 8080, etc.)
- Funciona en cualquier subdirectorio

### Instalación

1. Clonar o descargar el proyecto en el directorio deseado (por ejemplo, en xampp/htdocs/sistema_cine)

2. Configurar la base de datos en `config/configuracion.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistema_cine');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. Requisitos del servidor:
   - Asegurarse que el módulo mod_rewrite de Apache esté habilitado
   - Si usa un puerto diferente al 80 (por ejemplo, 8080):
     - En XAMPP: Modificar el puerto en httpd.conf
     - En otros servidores: Configurar el puerto según la documentación
   - No es necesario modificar ninguna configuración en el código del proyecto

4. Importar la base de datos:
   - Crear una base de datos llamada 'sistema_cine'
   - Importar el archivo SQL proporcionado

5. Acceder al sistema:
   - Si usa el puerto 80: http://localhost/sistema_cine
   - Si usa otro puerto: http://localhost:8080/sistema_cine
   - El sistema detectará automáticamente el puerto y la ruta base

### Ejemplos de URLs soportadas

El sistema funcionará correctamente en cualquiera de estas configuraciones:
- http://localhost/sistema_cine
- http://localhost:8080/sistema_cine
- http://localhost:8080/mi_cine
- http://mi-dominio.com/cine
- https://mi-dominio.com/app/cine

### Notas Técnicas

- Las URLs se generan usando la constante APP_URL que se configura automáticamente
- El archivo .htaccess usa reglas dinámicas para detectar la ruta base
- Los errores 404 y 403 se manejan con rutas relativas
- La caché y seguridad están configuradas independientemente de la ruta base

### Requisitos

- PHP 7.4 o superior
- Apache con mod_rewrite habilitado
- MySQL/MariaDB
