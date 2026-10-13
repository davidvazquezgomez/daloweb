<?php

// Preparación local de assets: requiere cwebp en PATH. No modifica los PNG originales.
$root = dirname(__DIR__);
$source = $root . '/public/img/proyectos';
$destination = $source . '/optimized';
if (!is_dir($destination) && !mkdir($destination, 0755, true)) {
    throw new RuntimeException('No se pudo crear el directorio de imágenes.');
}

$images = [];
foreach (glob($source . '/*.png') as $file) {
    [$width, $height] = getimagesize($file);
    $stem = pathinfo($file, PATHINFO_FILENAME);
    $variants = [];
    $widths = array_values(array_unique(array_filter([320, 640, $width], fn ($size) => $size <= $width)));
    sort($widths);
    foreach ($widths as $size) {
        $filename = $stem . '-' . $size . '.webp';
        $command = ['cwebp', '-quiet', '-q', '88', '-m', '6', '-sharp_yuv'];
        if ($size < $width) {
            $command = array_merge($command, ['-resize', (string) $size, '0']);
        }
        $command = array_merge($command, [$file, '-o', $destination . '/' . $filename]);
        $process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            throw new RuntimeException('No se pudo ejecutar cwebp.');
        }
        fclose($pipes[0]);
        stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new RuntimeException('Falló cwebp para ' . basename($file) . ': ' . $error);
        }
        $variants[$size] = 'optimized/' . $filename;
    }
    $images[basename($file)] = ['width' => $width, 'height' => $height, 'webp' => $variants];
}

file_put_contents($root . '/config/imagenes.php', "<?php\n\n// Generado por scripts/optimizar-imagenes.php.\nreturn " . var_export($images, true) . ";\n");
echo 'Preparadas ' . count($images) . " capturas con variantes WebP; originales conservados.\n";
