<?php
// Crear una imagen de 400x600 (proporción típica de póster de película)
$width = 400;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Colores
$bgColor = imagecolorallocate($image, 240, 240, 240); // Gris claro
$textColor = imagecolorallocate($image, 120, 120, 120); // Gris oscuro

// Rellenar fondo
imagefill($image, 0, 0, $bgColor);

// Texto a mostrar
$text = "No Image";

// Fuente y tamaño
$fontSize = 5;

// Obtener dimensiones del texto
$textWidth = imagefontwidth($fontSize) * strlen($text);
$textHeight = imagefontheight($fontSize);

// Centrar texto
$x = ($width - $textWidth) / 2;
$y = ($height - $textHeight) / 2;

// Dibujar texto
imagestring($image, $fontSize, $x, $y, $text, $textColor);

// Establecer headers para imagen PNG
header('Content-Type: image/png');

// Generar imagen
imagepng($image);
imagedestroy($image);
?>
