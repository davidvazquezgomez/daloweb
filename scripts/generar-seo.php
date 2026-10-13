<?php

// php scripts/generar-seo.php [https://www.daloweb.es]
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$url = rtrim($argv[1] ?? 'https://www.daloweb.es', '/');
if (!filter_var($url, FILTER_VALIDATE_URL) || parse_url($url, PHP_URL_SCHEME) !== 'https'
    || !in_array(parse_url($url, PHP_URL_PATH), [null, '', '/'], true)
    || parse_url($url, PHP_URL_QUERY) || parse_url($url, PHP_URL_FRAGMENT)
    || parse_url($url, PHP_URL_USER) || parse_url($url, PHP_URL_PASS)) {
    fwrite(STDERR, "Indica un dominio HTTPS sin ruta, parámetros ni credenciales.\n");
    exit(1);
}
config(['app.url' => $url]);
$controller = $app->make(\App\Http\Controllers\InicioController::class);
$sitemap = $controller->sitemap()->getContent();
$xml = simplexml_load_string($sitemap);
if ($xml === false || !count($xml->url)) {
    fwrite(STDERR, "El sitemap generado no es válido.\n");
    exit(1);
}
$document = new DOMDocument('1.0', 'UTF-8');
$document->preserveWhiteSpace = false;
$document->formatOutput = true;
$document->loadXML($sitemap);
$files = ['sitemap.xml' => $document->saveXML(), 'robots.txt' => $controller->robots()->getContent()];
foreach ($files as $name => $content) {
    if (file_put_contents(public_path($name), $content) === false) {
        fwrite(STDERR, "No se ha podido escribir public/$name.\n");
        exit(1);
    }
    echo "Generado public/$name\n";
}
echo count($xml->url) . " URL canónicas para $url\n";
