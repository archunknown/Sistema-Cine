<?php

class ImagenController extends Controller {
    public function mostrar($params = []) {
        // Obtener el nombre del archivo de la URL
        $nombreArchivo = isset($params[0]) ? $params[0] : null;
        
        if (!$nombreArchivo) {
            header("HTTP/1.0 404 Not Found");
            exit('Archivo no especificado');
        }

        // Construir la ruta completa
        $rutaImagen = PUBLIC_PATH . 'uploads/Peliculas/' . $nombreArchivo;

        // Verificar si el archivo existe
        if (!file_exists($rutaImagen)) {
            // Si no existe, mostrar imagen por defecto
            $rutaImagen = PUBLIC_PATH . 'img/no-image.png';
            if (!file_exists($rutaImagen)) {
                header("HTTP/1.0 404 Not Found");
                exit('Imagen no encontrada');
            }
        }

        // Obtener el tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $rutaImagen);
        finfo_close($finfo);

        // Enviar headers apropiados
        header('Content-Type: ' . $tipoMime);
        header('Content-Length: ' . filesize($rutaImagen));
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));

        // Leer y enviar el archivo
        readfile($rutaImagen);
        exit;
    }
}
