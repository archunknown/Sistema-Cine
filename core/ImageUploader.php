<?php
class ImageUploader {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    private $errors = [];

    public function __construct($uploadDir) {
        $this->uploadDir = rtrim($uploadDir, '/\\');
        $this->allowedTypes = ['image/jpeg', 'image/png'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
    }

    public function upload($file, $customName = null) {
        Debug::log("ImageUploader: Iniciando carga de imagen");
        
        if (!$this->validateUpload($file)) {
            Debug::log("ImageUploader: Validación fallida: " . implode(", ", $this->errors));
            return false;
        }

        // Crear directorio si no existe
        if (!$this->ensureDirectoryExists()) {
            Debug::log("ImageUploader: Error al crear directorio");
            return false;
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $customName ? $customName : uniqid('img_');
        $fileName = $this->sanitizeFileName($fileName) . '.' . strtolower($extension);
        $targetPath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;

        Debug::log("ImageUploader: Intentando guardar en: " . $targetPath);

        // Mover el archivo
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->errors[] = "Error al mover el archivo subido";
            Debug::log("ImageUploader: Error al mover archivo");
            return false;
        }

        Debug::log("ImageUploader: Archivo guardado exitosamente");
        return [
            'fileName' => $fileName,
            'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $targetPath)
        ];
    }

    private function validateUpload($file) {
        // Verificar errores de subida
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return false;
        }

        // Verificar tipo de archivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            $this->errors[] = "Tipo de archivo no permitido. Solo se permiten imágenes JPG y PNG.";
            return false;
        }

        // Verificar tamaño
        if ($file['size'] > $this->maxSize) {
            $this->errors[] = "El archivo es demasiado grande. Máximo permitido: 5MB.";
            return false;
        }

        return true;
    }

    private function ensureDirectoryExists() {
        if (!is_dir($this->uploadDir)) {
            Debug::log("ImageUploader: Creando directorio: " . $this->uploadDir);
            if (!mkdir($this->uploadDir, 0755, true)) {
                $this->errors[] = "No se pudo crear el directorio de subida";
                return false;
            }
        }

        if (!is_writable($this->uploadDir)) {
            $this->errors[] = "El directorio de subida no tiene permisos de escritura";
            return false;
        }

        return true;
    }

    private function sanitizeFileName($fileName) {
        // Eliminar caracteres especiales y espacios
        $fileName = preg_replace("/[^a-zA-Z0-9]/", "_", $fileName);
        return strtolower($fileName);
    }

    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "El archivo excede el tamaño máximo permitido por PHP";
            case UPLOAD_ERR_FORM_SIZE:
                return "El archivo excede el tamaño máximo permitido por el formulario";
            case UPLOAD_ERR_PARTIAL:
                return "El archivo solo fue subido parcialmente";
            case UPLOAD_ERR_NO_FILE:
                return "No se subió ningún archivo";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Falta la carpeta temporal";
            case UPLOAD_ERR_CANT_WRITE:
                return "No se pudo escribir el archivo";
            case UPLOAD_ERR_EXTENSION:
                return "Una extensión de PHP detuvo la subida del archivo";
            default:
                return "Error desconocido al subir el archivo";
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
